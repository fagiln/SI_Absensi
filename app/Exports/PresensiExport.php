<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PresensiExport implements FromCollection, WithHeadings
{
    protected $presensi;

    public function __construct($presensi)
    {
        $this->presensi = $presensi;
    }

    public function collection()
    {
        return $this->presensi->map(function($item) {
            return [
                'nik' => $item->user->nik, // Asumsi relasi user ada di model Kehadiran
                'name' => $item->user->name,
                'work_date' => $item->work_date,
                'check_in_time' => $item->check_in_time,
                'check_out_time' => $item->check_out_time,
                'status' => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Name',
            'Tanggal Kerja',
            'Jam Datang',
            'Jam Pulang',
            'Status',
        ];
    }
}
