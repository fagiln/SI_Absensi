<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\perizinan;

use App\DataTables\User\RiwayatDataTable;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;
// use Illuminate\Support\Collection;
// use Illuminate\Http\RedirectResponse;


class RiwayatController extends Controller
{
    // public function show(Request $request)
    // {
    //     // Ambil data kehadiran dan perizinan berdasarkan user yang login
    //     $kehadiranQuery = Kehadiran::where('user_id', Auth::id());
    //     $perizinanQuery = Perizinan::where('user_id', Auth::id());

    //     // Tambahkan filter berdasarkan tanggal jika ada
    //     if ($request->has('start_date') && $request->has('end_date')) {
    //         $startDate = Carbon::parse($request->start_date)->startOfDay();
    //         $endDate = Carbon::parse($request->end_date)->endOfDay();

    //         // Filter kehadiran berdasarkan check_in_time
    //         $kehadiranQuery->whereBetween('check_in_time', [$startDate, $endDate]);

    //         // Filter perizinan berdasarkan rentang tanggal (start_date dan end_date)
    //         $perizinanQuery->where(function ($query) use ($startDate, $endDate) {
    //             $query->whereBetween('start_date', [$startDate, $endDate])
    //                 ->orWhereBetween('end_date', [$startDate, $endDate]);
    //         });
    //     }

    //     // Filter jika ada
    //     if ($request->has('filter') && $request->filter != 'semua') {
    //         $filter = $request->filter;

    //         // Filter data kehadiran berdasarkan status
    //         if (in_array($filter, ['hadir', 'telat'])) {
    //             $kehadiranQuery->where('status', $filter);
    //         }

    //         // Filter data perizinan berdasarkan reason
    //         if (in_array($filter, ['sakit', 'izin'])) {
    //             $perizinanQuery->where('reason', $filter);
    //         }
    //     }

    //     // Ambil data kehadiran dan perizinan yang difilter
    //     $kehadiran = $kehadiranQuery->get();
    //     $perizinan = $perizinanQuery->get();

    //     // Gabungkan data kehadiran dan perizinan
    //     $riwayat = [];
        
    //     // Proses data kehadiran
    //     foreach ($kehadiran as $kehadirans) {
    //         $checkInTime = Carbon::parse($kehadirans->check_in_time);
    //         $checkOutTime = $kehadirans->check_out_time ? Carbon::parse($kehadirans->check_out_time) : null;
    //         $totalHours = $checkOutTime ? $checkOutTime->diffInHours($checkInTime) : '-';

    //         $riwayat[] = [
    //             'tanggal' => $checkInTime->format('d-m-Y'),
    //             'masuk' => $checkInTime->format('H:i'),
    //             'pulang' => $checkOutTime ? $checkOutTime->format('H:i') : '-',
    //             'jam_kerja' => $totalHours,
    //             'status' => ucfirst($kehadirans->status),
    //             'type' => 'kehadiran',
    //         ];
    //     }

    //     // Proses data perizinan
    //     foreach ($perizinan as $izin) {
    //         $startDate = Carbon::parse($izin->start_date);
    //         $endDate = Carbon::parse($izin->end_date);

    //         $tanggalPerizinan = $startDate->format('d-m-Y') . ' s/d ' . $endDate->format('d-m-Y');

    //         $riwayat[] = [
    //             'tanggal' => $tanggalPerizinan,
    //             'masuk' => '-',
    //             'pulang' => '-',
    //             'jam_kerja' => '-',
    //             'status' => ucfirst($izin->reason),
    //             'type' => 'perizinan',
    //         ];
    //     }

    //     // Sortir data berdasarkan tanggal terbaru
    //     usort($riwayat, function ($a, $b) {
    //         return strtotime(explode(' s/d ', $b['tanggal'])[0]) - strtotime(explode(' s/d ', $a['tanggal'])[0]);
    //     });

    //     // Ambil hanya 8 entri terbaru
    //     $riwayatTerbaru = array_slice($riwayat, 0, 8);

    //     return view('user.riwayat', compact('riwayatTerbaru'));
    // }

    public function show(Request $request)
    {
        // Inisialisasi query kehadiran dan perizinan
        $kehadiranQuery = Kehadiran::where('user_id', Auth::id());
        $perizinanQuery = Perizinan::where('user_id', Auth::id());

        // Cek dan filter berdasarkan tanggal
        $this->filterTanggal($request, $kehadiranQuery, $perizinanQuery);

        // Filter jika ada
        if ($request->has('filter') && $request->filter != 'semua') {
            $filter = $request->filter;

            // Filter data kehadiran berdasarkan status (hadir atau telat)
            if ($filter === 'hadir' || $filter === 'telat') {
                $kehadiranQuery->where('status', $filter);
                $perizinanQuery = null; // Kosongkan perizinan jika yang difilter adalah kehadiran
            }
            // Filter data perizinan berdasarkan reason (sakit atau izin)
            elseif ($filter === 'sakit' || $filter === 'izin') {
                $perizinanQuery->where('reason', $filter);
                $kehadiranQuery = null; // Kosongkan kehadiran jika yang difilter adalah perizinan
            }
        }

        // Ambil data kehadiran dan perizinan yang difilter
        $kehadiran = $kehadiranQuery ? $kehadiranQuery->get() : collect(); // Jika kehadiranQuery kosong, buat collection kosong
        $perizinan = $perizinanQuery ? $perizinanQuery->get() : collect(); // Jika perizinanQuery kosong, buat collection kosong

        // Gabungkan data kehadiran dan perizinan ke dalam satu array
        $riwayat = [];

        // Tambahkan data kehadiran ke array riwayat
        foreach ($kehadiran as $kehadirans) {
            $checkInTime = Carbon::parse($kehadirans->check_in_time);
            $checkOutTime = $kehadirans->check_out_time ? Carbon::parse($kehadirans->check_out_time) : null;
            $totalHours = $checkOutTime ? $checkOutTime->diffInHours($checkInTime) : '-';

            $riwayat[] = [
                'tanggal' => $checkInTime->format('d-m-Y'),
                'masuk' => $checkInTime->format('H:i'),
                'pulang' => $checkOutTime ? $checkOutTime->format('H:i') : '-',
                'jam_kerja' => $totalHours,
                'status' => ucfirst($kehadirans->status),
                'type' => 'kehadiran',
            ];
        }

        // Tambahkan data perizinan ke array riwayat
        foreach ($perizinan as $izin) {
            $startDate = Carbon::parse($izin->start_date);
            $endDate = Carbon::parse($izin->end_date);

            $tanggalPerizinan = $startDate->format('d-m-Y') . ' s/d ' . $endDate->format('d-m-Y');

            $riwayat[] = [
                'tanggal' => $tanggalPerizinan,
                'masuk' => '-',
                'pulang' => '-',
                'jam_kerja' => '-',
                'status' => ucfirst($izin->reason),
                'type' => 'perizinan',
            ];
        }

        // Sortir data berdasarkan tanggal terbaru
        usort($riwayat, function ($a, $b) {
            return strtotime(explode(' s/d ', $b['tanggal'])[0]) - strtotime(explode(' s/d ', $a['tanggal'])[0]);
        });

        // Pagination
        $perPage = 8; // Jumlah item per halaman
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($riwayat, ($currentPage - 1) * $perPage, $perPage);
        $riwayatPaginated = new LengthAwarePaginator($currentItems, count($riwayat), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        return view('user.riwayat', compact('riwayatPaginated'));
    }

    protected function filterTanggal(Request $request, $kehadiranQuery, $perizinanQuery)
    {
        // Cek apakah ada start_date dan end_date yang diinputkan
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            // Filter kehadiran berdasarkan check_in_time
            $kehadiranQuery->whereBetween('check_in_time', [$startDate, $endDate]);

            // Filter perizinan berdasarkan rentang tanggal (start_date dan end_date)
            $perizinanQuery->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                    });
            });
        }
    }


}