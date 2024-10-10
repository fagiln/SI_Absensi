<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MonitoringDataTable;
use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Perizinan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(MonitoringDataTable $dataTable)
    {
        // $filterDate = $request->get('filter_date', Carbon::today()->toDateString());

        // // Query data kehadiran yang sesuai dengan tanggal yang difilter
        // $filterKehadiran = Kehadiran::where('work_date', $filterDate)->get();
        $kehadirans = Kehadiran::all();
        $dataTable = new MonitoringDataTable(request()->get('work_date'));
        return $dataTable->render('admin.monitoring.monitoring', compact('kehadirans'));
    }
    public function getKaryawanCuti()
    {
        $today = Carbon::today();
        $karyawanCuti = Perizinan::with('user')
            ->where('status', 'diterima')
            ->whereDate('start_date', '>=', $today)
            ->whereDate('end_date', '<=', $today)
            ->get();
        return response()->json($karyawanCuti);
    }
}
