<?php

namespace App\Http\Controllers\User;

use App\Models\Kehadiran;
use App\Models\Perizinan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class NotifController extends Controller
{
    // public function show()
    // {
    //     $userId = auth()->id();

    //     // Mengambil data terbaru dari model Kehadiran
    //     $kehadiranTerbaru = Kehadiran::where('user_id', $userId)
    //         ->select('status', 'created_at', 'updated_at') // Tambahkan 'updated_at' jika ada
    //         ->orderBy('updated_at', 'desc') // Urutkan berdasarkan updated_at
    //         ->limit(3)
    //         ->get();

    //     // Mengambil data terbaru dari model Perizinan
    //     $perizinanTerbaru = Perizinan::where('user_id', $userId)
    //         ->select('reason', 'created_at', 'status', 'updated_at')
    //         ->orderBy('updated_at', 'desc')
    //         ->limit(3)
    //         ->get();

    //     $kehadiranTerbaru = collect($kehadiranTerbaru);
    //     $perizinanTerbaru = collect($perizinanTerbaru);

    //     // Menggabungkan kedua koleksi dengan penyesuaian format tampilan
    //     $dataGabungan = $kehadiranTerbaru->map(function ($item, $index) {
    //         return [
    //             'type' => 'Kehadiran',
    //             'message' => $item->status,
    //             'status' => $index === 0 ? 'Absen Masuk' : 'Absen Pulang', // Kehadiran mungkin tidak memerlukan status tambahan
    //             'created_at' => $item->created_at,
    //             'updated_at' => $item->updated_at,
    //         ];
    //     })->merge($perizinanTerbaru->map(function ($item) {
    //         return [
    //             'type' => 'Perizinan',
    //             'message' => $item->reason,
    //             'status' => $item->status, // Menampilkan status terbaru
    //             'created_at' => $item->created_at,
    //             'updated_at' => $item->updated_at,
    //         ];
    //     }));

    //     // Urutkan data gabungan berdasarkan kolom 'updated_at'
    //     $dataGabungan = $dataGabungan->sortByDesc('updated_at');

    //     return view('user.notif', compact('dataGabungan'));
    // }

    public function show()
    {
        $userId = auth()->id();
        
        // $absen = Kehadiran::where('user_id', $userId)
        //                     ->whereDate('created_at', Carbon::today()) // Memfilter berdasarkan tanggal hari ini
        //                     ->first();

        // Mengambil data terbaru dari model Kehadiran
        $kehadiranTerbaru = Kehadiran::where('user_id', $userId)
            ->select('status', 'created_at', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();
    
        // Mengambil data terbaru dari model Perizinan
        $perizinanTerbaru = Perizinan::where('user_id', $userId)
            ->select('reason', 'created_at', 'status', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();
    
        $perizinanTerbaru = collect($perizinanTerbaru);
    
        // Mengelompokkan kehadiran berdasarkan tanggal
        $kehadiranKelompok = $kehadiranTerbaru->groupBy(function ($item) {
            return Carbon::parse($item->updated_at)->toDateString(); // Mengambil hanya tanggal
        });
    
        // Menggabungkan data kehadiran dan perizinan
        $dataGabungan = collect();
    
        // Proses setiap grup kehadiran
        foreach ($kehadiranKelompok as $tanggal => $kehadiranGroup) {
            foreach ($kehadiranGroup as $index => $item) {
                // dd($absen->created_at,$absen->updated_at);
                $status = $item->created_at == $item->updated_at ? 'Absen Masuk' : 'Absen Pulang'; // Absen masuk untuk yang pertama dan pulang untuk yang kedua
                $dataGabungan->push([
                    'type' => 'Kehadiran',
                    'message' => $item->status,
                    'status' => $status,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ]);
            }
        }
    
        // Menambahkan data perizinan ke dalam data gabungan
        foreach ($perizinanTerbaru as $item) {
            $dataGabungan->push([
                'type' => 'Perizinan',
                'message' => $item->reason,
                'status' => $item->status, // Menampilkan status terbaru
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    
        // Urutkan data gabungan berdasarkan kolom 'updated_at'
        $dataGabungan = $dataGabungan->sortByDesc('updated_at');
    
        return view('user.notif', compact('dataGabungan'));
    }
    


}
