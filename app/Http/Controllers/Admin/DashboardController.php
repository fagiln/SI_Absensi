<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Perizinan;
use Illuminate\Http\Request;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function show()
    {
        $today = Carbon::today()->toDateString(); // Mendapatkan tanggal hari ini

        // Mengambil data karyawan yang izinnya diterima dan berlaku untuk hari ini
        $karyawanIzin = Perizinan::where('status', 'diterima')
            ->where('reason', 'izin')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

        // Mengambil data karyawan yang hadir hari ini
        $karyawanHadir = Kehadiran::where('status', 'hadir')
            ->whereDate('work_date', $today)
            ->get();

        // Mengambil data karyawan yang telat hari ini
        $karyawanTelat = Kehadiran::where('status', 'telat')
            ->whereDate('work_date', $today)
            ->get();

        // Mengambil data karyawan yang sakit dan izinnya diterima untuk hari ini
        $karyawanSakit = Perizinan::where('status', 'diterima')
            ->where('reason', 'sakit')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

        // Hitung jumlah masing-masing kategori
        $karyawanHadirCount = $karyawanHadir->count();
        $karyawanTelatCount = $karyawanTelat->count();
        $karyawanIzinCount = $karyawanIzin->count();
        $karyawanSakitCount = $karyawanSakit->count();

        return view('admin.dashboard', compact(
            'karyawanHadir',
            'karyawanTelat',
            'karyawanIzin',
            'karyawanSakit',
            'karyawanHadirCount',
            'karyawanTelatCount',
            'karyawanIzinCount',
            'karyawanSakitCount'
        ));
    }

    public function karyawanIzin(Request $request)
    {
        $filterDate =  Carbon::today()->toDateString();

        $karyawanIzin = Perizinan::where('status', 'diterima')
            ->where('reason', 'izin')
            ->whereDate('start_date', '<=', $filterDate)
            ->whereDate('end_date', '>=', $filterDate)
            ->with('user')
            ->get();

        return response()->json($karyawanIzin);
    }
    public function karyawanSakit(Request $request)
    {
        $filterDate =  Carbon::today()->toDateString();

        $karyawanSakit = Perizinan::where('status', 'diterima')
            ->where('reason',  'sakit')
            ->whereDate('start_date', '<=', $filterDate)
            ->whereDate('end_date', '>=', $filterDate)
            ->with('user')
            ->get();

        return response()->json($karyawanSakit);
    }
}
