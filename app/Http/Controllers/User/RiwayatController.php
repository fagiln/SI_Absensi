<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Perizinan;

use App\DataTables\User\RiwayatDataTable;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RiwayatController extends Controller
{
    // public function show(Request $request)
    // {
    //     // Inisialisasi query kehadiran dan perizinan
    //     $kehadiranQuery = Kehadiran::where('user_id', Auth::id());
    //     $perizinanQuery = Perizinan::where('user_id', Auth::id());

    //     // Cek dan filter berdasarkan tanggal
    //     $this->filterTanggal($request, $kehadiranQuery, $perizinanQuery);

    //     // Filter jika ada
    //     if ($request->has('filter') && $request->filter != 'semua') {
    //         $filter = $request->filter;

    //         // Filter data kehadiran berdasarkan status (hadir atau telat)
    //         if ($filter === 'hadir' || $filter === 'telat') {
    //             $kehadiranQuery->where('status', $filter);
    //             $perizinanQuery = null; // Kosongkan perizinan jika yang difilter adalah kehadiran
    //         }
    //         // Filter data perizinan berdasarkan reason (sakit atau izin)
    //         elseif ($filter === 'sakit' || $filter === 'izin') {
    //             $perizinanQuery->where('reason', $filter);
    //             $kehadiranQuery = null; // Kosongkan kehadiran jika yang difilter adalah perizinan
    //         }
    //     }

    //     // Ambil data kehadiran dan perizinan yang difilter
    //     $kehadiran = $kehadiranQuery ? $kehadiranQuery->get() : collect(); // Jika kehadiranQuery kosong, buat collection kosong
    //     $perizinan = $perizinanQuery ? $perizinanQuery->get() : collect(); // Jika perizinanQuery kosong, buat collection kosong

    //     // Gabungkan data kehadiran dan perizinan ke dalam satu array
    //     $riwayat = [];

    //     // Tambahkan data kehadiran ke array riwayat
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

    //     // Tambahkan data perizinan ke array riwayat
    //     foreach ($perizinan as $izin) {
    //         $startDate = Carbon::parse($izin->start_date);
    //         $endDate = Carbon::parse($izin->end_date);

    //         $tanggalPerizinan = $startDate->format('d-m-Y') . ' s/d ' . $endDate->format('d-m-Y');

    //         if ($izin->status == 'diterima') {
    //             $riwayat[] = [
    //                 'tanggal' => $tanggalPerizinan,
    //                 'masuk' => '-',
    //                 'pulang' => '-',
    //                 'jam_kerja' => '-',
    //                 'status' => ucfirst($izin->reason),
    //                 'type' => 'perizinan',
    //             ];
    //         }
    //     }

    //     $startDate = Carbon::now()->startOfMonth(); // Awal bulan ini
    //     $endDate = Carbon::now()->endOfMonth();     // Akhir bulan ini

    //     // Sortir data berdasarkan tanggal terbaru
    //     usort($riwayat, function ($a, $b) {
    //         return strtotime(explode(' s/d ', $b['tanggal'])[0]) - strtotime(explode(' s/d ', $a['tanggal'])[0]);
    //     });

    //     // Pagination
    //     $perPage = 8; // Jumlah item per halaman
    //     $currentPage = LengthAwarePaginator::resolveCurrentPage();
    //     $currentItems = array_slice($riwayat, ($currentPage - 1) * $perPage, $perPage);
    //     $riwayatPaginated = new LengthAwarePaginator($currentItems, count($riwayat), $perPage, $currentPage, [
    //         'path' => LengthAwarePaginator::resolveCurrentPath(),
    //         'query' => $request->query(),
    //     ]);

    //     return view('user.riwayat', compact('riwayatPaginated', 'startDate', 'endDate'));
    // }

    // protected function filterTanggal(Request $request, $kehadiranQuery, $perizinanQuery)
    // {
    //     // Cek apakah ada start_date dan end_date yang diinputkan
    //     if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
    //         $startDate = Carbon::parse($request->start_date)->startOfDay();
    //         $endDate = Carbon::parse($request->end_date)->endOfDay();

    //         // Filter kehadiran berdasarkan check_in_time
    //         $kehadiranQuery->whereBetween('check_in_time', [$startDate, $endDate]);

    //         // Filter perizinan berdasarkan rentang tanggal (start_date dan end_date)
    //         $perizinanQuery->where(function ($query) use ($startDate, $endDate) {
    //             $query->whereBetween('start_date', [$startDate, $endDate])
    //                 ->orWhereBetween('end_date', [$startDate, $endDate])
    //                 ->orWhere(function ($query) use ($startDate, $endDate) {
    //                     $query->where('start_date', '<=', $startDate)
    //                             ->where('end_date', '>=', $endDate);
    //                 });
    //         });
    //     }
    // }
    
    public function show(Request $request)
    {
        // Query untuk data kehadiran dan perizinan
        $kehadiranQuery = Kehadiran::where('user_id', Auth::id());
        $perizinanQuery = Perizinan::where('user_id', Auth::id());

        // Tetapkan default untuk start_date dan end_date
        $startDate = $request->start_date 
            ? Carbon::parse($request->start_date)->startOfDay() 
            : Carbon::now()->startOfMonth();
        $endDate = $request->end_date 
            ? Carbon::parse($request->end_date)->endOfDay() 
            : Carbon::now()->endOfMonth();

        // Filter query kehadiran dan perizinan berdasarkan tanggal
        $kehadiranQuery = Kehadiran::where('user_id', Auth::id())
            ->whereBetween('check_in_time', [$startDate, $endDate]);

        $perizinanQuery = Perizinan::where('user_id', Auth::id())
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });

        // Filter tanggal
        $this->filterTanggal($request, $kehadiranQuery, $perizinanQuery);

        // Filter berdasarkan status
        $this->filterStatus($request, $kehadiranQuery, $perizinanQuery);

        // Ambil data dari query
        $kehadiran = $kehadiranQuery ? $kehadiranQuery->get() : collect();
        $perizinan = $perizinanQuery ? $perizinanQuery->get() : collect();

        // Gabungkan dan susun data riwayat
        $riwayat = $this->gabungkanRiwayat($kehadiran, $perizinan);

        // Sortir berdasarkan tanggal terbaru
        $riwayat = collect($riwayat)->sortByDesc(function ($item) {
            return strtotime(explode(' s/d ', $item['tanggal'])[0]);
        })->values();

        // Pagination
        $perPage = 8;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $riwayat->slice(($currentPage - 1) * $perPage, $perPage);
        $riwayatPaginated = new LengthAwarePaginator(
            $currentItems,
            $riwayat->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        $startDate = Carbon::now()->startOfMonth(); // Awal bulan ini
        $endDate = Carbon::now()->endOfMonth();     // Akhir bulan ini

        return view('user.riwayat', compact('riwayatPaginated', 'startDate', 'endDate'));
    }

    protected function filterTanggal(Request $request, $kehadiranQuery, $perizinanQuery)
    {
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $kehadiranQuery->whereBetween('check_in_time', [$startDate, $endDate]);

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

    protected function filterStatus(Request $request, &$kehadiranQuery, &$perizinanQuery)
    {
        if ($request->has('filter') && $request->filter != 'semua') {
            $filter = $request->filter;

            if (in_array($filter, ['hadir', 'telat'])) {
                $kehadiranQuery->where('status', $filter);
                $perizinanQuery = null; // Kosongkan query perizinan
            } elseif (in_array($filter, ['sakit', 'izin'])) {
                $perizinanQuery->where('reason', $filter);
                $kehadiranQuery = null; // Kosongkan query kehadiran
            }
        }
    }

    protected function gabungkanRiwayat($kehadiran, $perizinan)
    {
        $riwayat = [];

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

        foreach ($perizinan as $izin) {
            $startDate = Carbon::parse($izin->start_date);
            $endDate = Carbon::parse($izin->end_date);
            $tanggalPerizinan = $startDate->format('d-m-Y') . ' s/d ' . $endDate->format('d-m-Y');

            if ($izin->status == 'diterima') {
                $riwayat[] = [
                    'tanggal' => $tanggalPerizinan,
                    'masuk' => '-',
                    'pulang' => '-',
                    'jam_kerja' => '-',
                    'status' => ucfirst($izin->reason),
                    'type' => 'perizinan',
                ];
            }
        }

        return $riwayat;
    }

}