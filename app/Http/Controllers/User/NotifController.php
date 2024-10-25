<?php

namespace App\Http\Controllers\User;
use App\Models\Kehadiran;
use App\Models\Perizinan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;

class NotifController extends Controller
{
    public function show(){

        $user = Auth::user();
        $userId = auth()->id();

         // Mengambil 5 data terbaru dari model Kehadiran
         $kehadiranTerbaru = Kehadiran::where('user_id', $userId)
         ->select('status', 'created_at') // Ambil kolom yang relevan
         ->orderBy('created_at', 'desc')
         ->limit(3) // Sesuaikan dengan jumlah data yang ingin ditampilkan
         ->get();

        // Mengambil 5 data terbaru dari model Perizinan
        $perizinanTerbaru = Perizinan::where('user_id', $userId)
        ->select('reason', 'created_at') // Ambil kolom yang relevan
        ->orderBy('created_at', 'desc')
        ->limit(3) // Sesuaikan dengan jumlah data yang ingin ditampilkan
        ->get();

        // Menggabungkan kedua koleksi
        $dataGabungan = $kehadiranTerbaru->map(function ($item) {
            return [
                'type' => 'Kehadiran', // Menandai tipe
                'message' => $item->status,
                'created_at' => $item->created_at,
            ];
        })->merge($perizinanTerbaru->map(function ($item) {
            return [
                'type' => 'Perizinan', // Menandai tipe
                'message' => $item->reason,
                'created_at' => $item->created_at,
            ];
        }));

        // Urutkan berdasarkan waktu pembuatan
        $dataGabungan = $dataGabungan->sortByDesc('created_at');

        return view('user.notif', compact('dataGabungan'));
    }
}
