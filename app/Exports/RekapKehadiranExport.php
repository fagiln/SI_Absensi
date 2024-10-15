<?php

namespace App\Exports;

use App\Models\Kehadiran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class RekapKehadiranExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $karyawan;
    protected $presensi;
    protected $month;
    protected $year;

    public function __construct($presensi, $month, $year,$karyawan) 
    {
        $this->presensi = $presensi;
        $this->month = $month;
        $this->year = $year;
        $this->karyawan = $karyawan;
    }

public function collection()
{
    $data = [];

    // Mengelompokkan data berdasarkan NIK dan Nama
    foreach ($this->presensi as $item) {
        $nik = $item->user->nik;
        $name = $item->user->name;
        $workDate = Carbon::parse($item->work_date);
        $checkIn = Carbon::parse($item->check_in_time)->format('H:i');
        $checkOut = Carbon::parse($item->check_out_time)->format('H:i');

        // Pastikan ada entry untuk NIK dan Nama
        if (!isset($data[$nik])) {
            $data[$nik] = [
                'nik' => $nik,
                'name' => $name,
                // Inisialisasi kolom untuk hari 1-31
            ];
            for ($day = 1; $day <= 31; $day++) {
                $data[$nik]["day{$day}"] = ''; // Kosongkan terlebih dahulu
            }
        }
        
        $data[$nik]["day{$workDate->day}"] = "$checkIn - $checkOut"; // Isi jam
    }

    return collect(array_values($data)); // Kembalikan data dalam format koleksi
}

public function headings(): array
{
    Carbon::setLocale('id');
    $headings = ['NIK', 'Nama']; // Mulai dengan header NIK dan Nama

    // Ambil jumlah hari dalam bulan yang ditentukan
    $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

    // Header untuk hari 1 sampai jumlah hari di bulan tersebut
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $headings[] = Carbon::create()->day($day)->translatedFormat('l') . ' ' . $day; // Menambahkan hari dan tanggal
    }

    return $headings; // Mengembalikan array header
}


    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Menebalkan header
            'A1:E1' => ['font' => ['size' => 11]], // Mengatur ukuran font header
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $dataTableStartRow = 1; // Tabel akan dimulai dari baris 10
                $event->sheet->insertNewRowBefore($dataTableStartRow, 5);
                $event->sheet->getStyle('A1:AI' . (count($this->presensi) + 1))->getFont()->setName('Times New Roman');
                // Menulis judul laporan presensi di baris 1
                $event->sheet->mergeCells('A1:F1');
                $event->sheet->setCellValue('A1', 'Laporan Presensi Karyawan');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Menulis data diri karyawan mulai dari baris 2 sampai 7
                $event->sheet->mergeCells('A2:F2');
                $event->sheet->setCellValue('A2', 'Bulan: ' . Carbon::create()->month($this->month)->translatedFormat('F') . ' ' . $this->year);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal('center');


                $rowCount = $this->karyawan->count() + $dataTableStartRow + 5; // Menambah 1 untuk baris header
                $event->sheet->setCellValue('A' . ($rowCount + 2), 'Surabaya, ' . Carbon::now()->translatedFormat('d F Y'));
                $event->sheet->setCellValue('A' . ($rowCount + 6), '(____________________)');
                $event->sheet->setCellValue('A' . ($rowCount + 7), 'Mario Mariyadi');
                $event->sheet->setCellValue('A' . ($rowCount + 8), 'Direktur');

                // Menambahkan spasi antar header jika diperlukan
            },
        ];
    }
}
