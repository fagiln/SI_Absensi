<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PresensiExport;
use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PresensiController extends Controller
{
    public function index()
    {
        $karyawan = User::where('role', 'user')->get();

        return view('admin.presensi.presensi', compact('karyawan'));
    }

    public function export(Request $request)
    {
        $userId = $request->input('user_id');
        $month = $request->input('month');
        $year = $request->input('year');
   
        // Ambil data presensi berdasarkan user_id dan bulan serta tahun
        $presensi = Kehadiran::where('user_id', $userId)
            ->whereMonth('work_date', $month)
            ->whereYear('work_date', $year)
            ->get();

        // Ambil nama karyawan berdasarkan user_id
        $karyawan = User::find($userId);

        // Format nama file
        $filename = $karyawan ? "{$karyawan->name}_{$month}-{$year}_laporan_presensi.xlsx" : 'laporan_presensi.xlsx';

        // Ekspor ke Excel dengan data yang sesuai
        return Excel::download(new PresensiExport( $presensi, $karyawan, $month, $year), $filename);    }


    public function print(Request $request)
    {
        $userId = $request->input('user_id');
        $month = $request->input('month');
        $year = $request->input('year');

        $karyawan = User::findOrFail($userId);

        // Ambil data presensi berdasarkan user_id dan bulan
        $presensi = Kehadiran::where('user_id', $userId)
            ->whereMonth('work_date', $month)
            ->whereYear('work_date', $year)
            ->get();

        // Kirim data ke view print.blade.php
        return view('admin.presensi.print', compact('presensi', 'karyawan', 'month', 'year'));
    }
}
