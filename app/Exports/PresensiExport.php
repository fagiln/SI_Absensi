<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PresensiExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithEvents
{
    protected $presensi;
    protected $karyawan;
    protected $month;
    protected $year;

    public function __construct($presensi, $karyawan, $month, $year)
    {
        $this->presensi = $presensi;
        $this->karyawan = $karyawan;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return $this->presensi->map(function ($item) {
            $checkIn = Carbon::parse($item->check_in_time);
            $checkOut = Carbon::parse($item->check_out_time);
            $workHours = $checkOut->diff($checkIn)->format('%H:%I');

            return [
                'work_date' => $item->work_date,
                'check_in_time' => Carbon::parse($item->check_in_time)->format('H:i:s'),
                'check_out_time' => Carbon::parse($item->check_out_time)->format('H:i:s'),
                'work_hours' => $workHours,
                'status' => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal Kerja',
            'Jam Datang',
            'Jam Pulang',
            'Jumlah Jam Kerja',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Menebalkan header
            'A1:G1' => ['font' => ['size' => 11]], // Mengatur ukuran font header
        ];
    }

    public function title(): string
    {
        return "Rekap Presensi {$this->month}-{$this->year}";
    }
    public function registerEvents(): array
    {
        Carbon::setLocale('id');

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $dataTableStartRow = 1; // Tabel akan dimulai dari baris 10

                // Berikan jarak 2 baris sebelum tabel
                $event->sheet->insertNewRowBefore($dataTableStartRow, 8);

                // Menulis judul laporan presensi di baris 1
                $event->sheet->mergeCells('A1:E1');
                $event->sheet->setCellValue('A1', 'Laporan Presensi Karyawan');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Menulis data diri karyawan mulai dari baris 2 sampai 7
                $event->sheet->mergeCells('A2:E2');
                $event->sheet->setCellValue('A2', 'Bulan: ' . Carbon::create()->month($this->month)->translatedFormat('F') . ' ' . $this->year);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                $event->sheet->mergeCells('A3:E3');
                $event->sheet->setCellValue('A3', 'NIK: ' . $this->karyawan->nik);

                $event->sheet->mergeCells('A4:E4');
                $event->sheet->setCellValue('A4', 'Nama: ' . $this->karyawan->name);

                $event->sheet->mergeCells('A5:E5');
                $event->sheet->setCellValue('A5', 'Jabatan: ' . $this->karyawan->jabatan);

                $event->sheet->mergeCells('A6:E6');
                $event->sheet->setCellValue('A6', 'Departemen: ' . $this->karyawan->departemen->nama_departemen);

                $event->sheet->mergeCells('A7:E7');
                $event->sheet->setCellValue('A7', 'No. Hp: ' . $this->karyawan->no_hp);

                // Menambahkan jarak 2 baris kosong sebelum tabel data dimulai (baris 9 dan 10 kosong)

                // Menambahkan tempat tanda tangan setelah tabel
                $rowCount = $this->presensi->count() + $dataTableStartRow + 8;
                 // Menambah 1 untuk baris header
                 $cellRange = 'A9:E' . ($this->presensi->count() + $dataTableStartRow +8);
                 $event->sheet->getStyle($cellRange)->applyFromArray([
                     'borders' => [
                         'allBorders' => [
                             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                             'color' => ['argb' => '000000'], // Warna hitam
                         ],
                     ],
                 ]);
                $event->sheet->setCellValue('A' . ($rowCount + 2), 'Surabaya, ' . Carbon::now()->translatedFormat('d F Y'));
                $event->sheet->setCellValue('A' . ($rowCount + 6), '(____________________)');
                $event->sheet->setCellValue('A' . ($rowCount + 7), 'Mario Mariyadi');
                $event->sheet->setCellValue('A' . ($rowCount + 8), 'Direktur');

                $event->sheet->getStyle('A1:F' . ($rowCount + 8))->getFont()->setName('Times New Roman');
             },
        ];
    }
}
