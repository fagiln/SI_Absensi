<?php

namespace App\Http\Controllers\User;

use App\Models\Kehadiran;
use App\Models\Perizinan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotifController extends Controller
{
    public function show()
    {
        $userId = auth()->id();

        // Mengambil data terbaru dari model Kehadiran
        $kehadiranTerbaru = Kehadiran::where('user_id', $userId)
            ->select('status', 'created_at', 'updated_at') // Tambahkan 'updated_at' jika ada
            ->orderBy('updated_at', 'desc') // Urutkan berdasarkan updated_at
            ->limit(3)
            ->get();

        // Mengambil data terbaru dari model Perizinan
        $perizinanTerbaru = Perizinan::where('user_id', $userId)
            ->select('reason', 'created_at', 'status', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();

        $kehadiranTerbaru = collect($kehadiranTerbaru);
        $perizinanTerbaru = collect($perizinanTerbaru);

        // Menggabungkan kedua koleksi dengan penyesuaian format tampilan
        $dataGabungan = $kehadiranTerbaru->map(function ($item) {
            return [
                'type' => 'Kehadiran',
                'message' => $item->status,
                'status' => '', // Kehadiran mungkin tidak memerlukan status tambahan
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        })->merge($perizinanTerbaru->map(function ($item) {
            return [
                'type' => 'Perizinan',
                'message' => $item->reason,
                'status' => $item->status, // Menampilkan status terbaru
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        }));

        // Urutkan data gabungan berdasarkan kolom 'updated_at'
        $dataGabungan = $dataGabungan->sortByDesc('updated_at');

        return view('user.notif', compact('dataGabungan'));
    }
}
