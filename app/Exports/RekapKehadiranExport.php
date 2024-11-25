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

class RekapKehadiranExport implements FromCollection, WithHeadings, WithEvents
{
    protected $presensi;
    protected $month;
    protected $year;
    protected $karyawan;
protected $perizinan;
    public function __construct($presensi, $month, $year, $karyawan, $perizinan )
    {
        $this->presensi = $presensi;
        $this->month = $month;
        $this->year = $year;
        $this->karyawan = $karyawan;
        $this->perizinan = $perizinan;
    }
    public function collection()
    {
        $data = [];
        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;
        $no = 1;
    
        foreach ($this->karyawan as $user) {
            $userPresensi = $this->presensi->where('user_id', $user->id);
            
            // Ambil data izin cuti untuk karyawan ini dari koleksi $perizinan
            $izinCuti = $this->perizinan->where('user_id', $user->id);
    
            $rowData = [
                'no' => $no++,
                'nik' => $user->nik,
                'name' => $user->name,
            ];
    
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = Carbon::create($this->year, $this->month, $day)->toDateString();
                $isWeekend = Carbon::create($this->year, $this->month, $day)->translatedFormat('l');
            
                // Cek izin cuti
                $isCuti = $izinCuti->first(function ($izin) use ($currentDate) {
                    return $currentDate >= $izin->start_date && $currentDate <= $izin->end_date;
                });
            
                // Cek absensi terlebih dahulu
                $presensiOnDay = $userPresensi->firstWhere('work_date', $currentDate);
                if ($presensiOnDay) {
                    // Jika ada presensi
                    $rowData["day{$day}"] = Carbon::parse($presensiOnDay->check_in_time)->format('H:i') . ' - ' . 
                        ($presensiOnDay->check_out_time ? Carbon::parse($presensiOnDay->check_out_time)->format('H:i') : 'Tidak absen pulang');
                } elseif ($isWeekend == 'Sabtu' || $isWeekend == 'Minggu') {
                    // Jika hari libur (akhir pekan)
                    $rowData["day{$day}"] = 'Libur';
                } elseif ($isCuti) {
                    // Jika hari cuti
                    $rowData["day{$day}"] = 'Izin Cuti';
                } else {
                    // Hari kerja tanpa presensi
                    $rowData["day{$day}"] = '';
                }
            }
            
            $data[] = $rowData;
        }
    
        return collect($data);
    }
    


    public function headings(): array
    {
        Carbon::setLocale('id');
        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

        $headings = ['No', 'NIK', 'Nama'];

        // Menambahkan header untuk tanggal 1-15
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $headings[] = Carbon::create($this->year, $this->month, $day)->translatedFormat('l') . ' ' . $day;
        }

        return $headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $startRow = 1;
                $numberOfRows = 5;
                $event->sheet->insertNewRowBefore($startRow, $numberOfRows);
                $event->sheet->getStyle('A1:Z1')->getFont()->setBold(true); // Menebalkan header
                $event->sheet->getStyle('A6:AI6')->getFont()->setBold(true); // Menebalkan header

                $event->sheet->mergeCells('A1:A4'); // Merge untuk judul
                $event->sheet->mergeCells('B1:F1'); // Merge untuk judul
                $event->sheet->setCellValue('B1', 'REKAP PRESENSI KARYAWAN');
                $event->sheet->getStyle('B1')->getFont()->setBold(true)->setSize(sizeInPoints: 11);

                $event->sheet->mergeCells('B2:F2');
                $event->sheet->setCellValue('B2', 'PT. MULTI POWER ABADI');
                $event->sheet->getStyle('B2')->getFont()->setBold(true)->setSize(sizeInPoints: 11);

                $event->sheet->mergeCells('B3:F3');
                $event->sheet->setCellValue('B3', 'Jl.Gn.Anyar Tambak IV No.50, Gn. Anyar Tambak, Kec. Gn. Anyar, Surabaya, Jawa Timur 60294');
                $event->sheet->getStyle('B3')->getFont()->setItalic(true)->setSize(sizeInPoints: 11);
                $event->sheet->mergeCells('B4:F4');
                $event->sheet->setCellValue('B4', 'Bulan: ' . Carbon::create()->month($this->month)->translatedFormat('F') . ' ' . $this->year);
                $event->sheet->getStyle('B4')->getAlignment();
                // Format tanggal
                $rowCount = $this->karyawan->count() + 2 + $startRow + $numberOfRows; // Menambah baris untuk header
                $cellRange = 'A6:AH' . ($this->karyawan->count() + $startRow + $numberOfRows);
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
                $event->sheet->getStyle('A1:AI' . ($rowCount + 8))->getFont()->setName('Times New Roman');
            },
        ];
    }
}
