<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MonitoringDataTable;
use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
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

















//     public function filter(Request $request)
//     {
//         // Ambil filter_date dari permintaan
//         $filterDate = $request->input('filter_date');

//         // Lakukan query untuk mengambil data berdasarkan filter_date
//         $kehadirans = Kehadiran::where('work_date', $filterDate)->get();

//         // Kembalikan data dalam format JSON untuk DataTables
//         return response()->json([
//             'draw' => intval($request->input('draw')),
//             'recordsTotal' => $kehadirans->count(),
//             'recordsFiltered' => $kehadirans->count(),
//             'data' => $kehadirans->map(function ($kehadiran) {
//                 return [
//                     '<div class="d-flex justify-content-center align-items-center gap-2">
//     <input type="hidden" name="id" value="' . $kehadiran->id . '">

//     <a href="#" class="btn btn-info mr-3" data-id="' . $kehadiran->id . '" data-toggle="modal"
//         data-target="#modalMapsIn_' . $kehadiran->id . '" data-latitude="' . $kehadiran->check_in_latitude . '"
//         data-name="' . $kehadiran->user->name . '" data-longitude="' . $kehadiran->check_in_longitude . '">
//         <i class="fas fa-map fs-2"></i>
//     </a>

// </div>
// ',
//                     '<div class="d-flex justify-content-center align-items-center gap-2">
//     <input type="hidden" name="id" value="' . $kehadiran->id . '">

//     <a href="#" class="btn btn-info mr-3" data-id="' . $kehadiran->id . '" data-toggle="modal"
//         data-target="#modalMapsOut_' . $kehadiran->id . '" data-latitude="' . $kehadiran->check_out_latitude . '"
//         data-name="' . $kehadiran->user->name . '" data-longitude="' . $kehadiran->check_out_longitude . '">
//         <i class="fas fa-map fs-2"></i>
//     </a>
// </div>
// ',
//                     $kehadiran->user->nik,
//                     $kehadiran->user->name,
//                     $kehadiran->check_in_time,
//                     $kehadiran->check_out_time,
//                     $kehadiran->check_in_photo,
//                     $kehadiran->check_out_photo,
//                     $kehadiran->status,
//                 ];
//             })
//         ]);
//     }
}
