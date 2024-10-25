<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PresensiExport;
use App\Exports\RekapKehadiranExport;
use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RekapPresensiController extends Controller
{
    public function index()
    {
        $karyawan = User::where('role', 'user')->get();

        return view('admin.rekap-presensi.rekap-presensi', compact('karyawan'));
    }
    public function export(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $presensi = Kehadiran::whereMonth('work_date', $month)
            ->whereYear('work_date', $year)
            ->whereHas('user', function ($query) {
                $query->where('role', 'user');
            })
            ->get();
        $karyawan = User::where('role', 'user')->get(); // Ambil semua data karyawan
    
        return Excel::download(new RekapKehadiranExport($presensi, $month, $year, $karyawan)," Rekap_Presensi_{$month}_{$year}.xlsx");
    }


    public function print(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');


        // Ambil data presensi berdasarkan user_id dan bulan
        $presensi = Kehadiran::whereMonth('work_date', $month)
            ->whereYear('work_date', $year)
            ->whereHas('user', function ($query) {
                $query->where('role', 'user');
            })
            ->get();

        // Kirim data ke view print.blade.php
        return view('admin.rekap-presensi.print', compact('presensi', 'month', 'year'));
    }
}
