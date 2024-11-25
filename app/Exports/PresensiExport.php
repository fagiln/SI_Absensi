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

        $no = 1;

        return $this->presensi->map(function ($item) use (&$no) {
            $checkIn = Carbon::parse($item->check_in_time);
            $checkOut = Carbon::parse($item->check_out_time);
            $workHours = $checkOut->diff($checkIn)->format('%H:%I');

            return [
                'no' => $no++,
                'work_date' => $item->work_date,
                'check_in_time' => Carbon::parse($item->check_in_time)->format('H:i:s'),
                'check_out_time' => $item->check_out_time == null ? '-' :  Carbon::parse($item->check_out_time)->format('H:i:s'),
                'work_hours' => $item->check_out_time == null ? '-' :  $workHours,
                'status' => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
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
                $numberOfRows = 11;
                // Berikan jarak 2 baris sebelum tabel
                $event->sheet->insertNewRowBefore($dataTableStartRow, $numberOfRows);
                $event->sheet->mergeCells('A1:A4');
                // Menulis judul laporan presensi di baris 1
                $event->sheet->mergeCells('B1:F1');
                $event->sheet->setCellValue('B1', 'LAPORAN PRESENSI KARYAWAN');
                $event->sheet->getStyle('B1')->getFont()->setBold(true)->setSize(11);
                $event->sheet->getStyle('B1')->getAlignment();
                $event->sheet->mergeCells('B2:F2');
                $event->sheet->setCellValue('B2', 'PT. MULTI POWER ABADI');
                $event->sheet->getStyle('B2')->getFont()->setBold(true)->setSize(11);
                $event->sheet->getStyle('B2')->getAlignment();
                $event->sheet->mergeCells('B3:F3');
                $event->sheet->setCellValue('B3', 'Jl.Gn.Anyar Tambak IV No.50, Gn. Anyar Tambak, Kec. Gn. Anyar, Surabaya, Jawa Timur 60294');
                $event->sheet->getStyle('B3')->getFont()->setItalic(true)->setSize(11);
                $event->sheet->getStyle('B3')->getAlignment();

                // Menulis data diri karyawan mulai dari baris 2 sampai 7
                $event->sheet->mergeCells('B4:F4');
                $event->sheet->setCellValue('B4', 'Bulan: ' . Carbon::create()->month($this->month)->translatedFormat('F') . ' ' . $this->year);
                $event->sheet->getStyle('B4')->getAlignment();

                $event->sheet->mergeCells('A6:E6');
                $event->sheet->setCellValue('A6', 'NIK: ' . $this->karyawan->nik);

                $event->sheet->mergeCells('A7:E7');
                $event->sheet->setCellValue('A7', 'Nama: ' . $this->karyawan->name);

                $event->sheet->mergeCells('A8:E8');
                $event->sheet->setCellValue('A8', 'Jabatan: ' . $this->karyawan->jabatan);

                $event->sheet->mergeCells('A9:E9');
                $event->sheet->setCellValue('A9', 'Departemen: ' . $this->karyawan->departemen->nama_departemen);

                $event->sheet->mergeCells('A10:E10');
                $event->sheet->setCellValue('A10', 'No. Hp: ' . $this->karyawan->no_hp);

                // Menambahkan jarak 2 baris kosong sebelum tabel data dimulai (baris 9 dan 10 kosong)

                // Menambahkan tempat tanda tangan setelah tabel
                $rowCount = $this->presensi->count() + $dataTableStartRow + $numberOfRows;
                // Menambah 1 untuk baris header
                $cellRange = 'A12:F' . ($this->presensi->count() + $dataTableStartRow + $numberOfRows);
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
                $event->sheet->setCellValue('D' . ($rowCount + 6), '(____________________)');
                $event->sheet->setCellValue('D' . ($rowCount + 7), 'Iqbal Firmansyah');
                $event->sheet->setCellValue('D' . ($rowCount + 8), 'Sekretaris');

                $event->sheet->getStyle('A1:F' . ($rowCount + 8))->getFont()->setName('Times New Roman');
            },
        ];
    }
}
