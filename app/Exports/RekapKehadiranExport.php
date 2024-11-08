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
    
                if ($isCuti) {
                    $rowData["day{$day}"] = 'Izin Cuti';
                } 
                elseif($isWeekend == 'Sabtu' || $isWeekend == 'Minggu'){
                    $rowData["day{$day}"] = 'Libur';

                }
                else {
                    $presensiOnDay = $userPresensi->firstWhere('work_date', $currentDate);
                    $rowData["day{$day}"] = $presensiOnDay
                        ? Carbon::parse($presensiOnDay->check_in_time)->format('H:i') . ' - ' . Carbon::parse($presensiOnDay->check_out_time)->format('H:i')
                        : '';
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
                $event->sheet->insertNewRowBefore($startRow, 5);
                $event->sheet->getStyle('A1:Z1')->getFont()->setBold(true); // Menebalkan header
                $event->sheet->getStyle('A6:AI6')->getFont()->setBold(true); // Menebalkan header
                $event->sheet->mergeCells('A1:C1'); // Merge untuk judul
                $event->sheet->setCellValue('A1', 'Rekap Presensi Karyawan');
                $event->sheet->mergeCells('A2:C2');
                $event->sheet->setCellValue('A2', 'Bulan: ' . Carbon::create($this->year, $this->month)->translatedFormat('F'));
                $event->sheet->mergeCells('A3:C3');
                $event->sheet->setCellValue('A3', 'Tahun: ' . $this->year);
                // Format tanggal
                $rowCount = $this->karyawan->count() + 2 + $startRow + 5; // Menambah baris untuk header
                $cellRange = 'A6:AH' . ($this->karyawan->count() + $startRow + 5);
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
