<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kehadiran;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function show(){

        $user = Auth::user();

        // dd(Carbon::now());
        // Fitur ganti sapaan 
        $currentTime = Carbon::now()->format('H'); // Ambil jam dalam format 24 jam
        // Tentukan pesan berdasarkan waktu
        if ($currentTime >= 5 && $currentTime < 11) {
            
            $greeting = 'Pagi';
        } elseif ($currentTime >= 11 && $currentTime < 15) {
            
            $greeting = 'Siang';
        } elseif ($currentTime >= 15 && $currentTime <= 17) {
            
            $greeting = 'Sore';
        } else {
            $greeting = 'Malam';
        }
        

        return view('user.home.home', compact('user', 'greeting'));
    }

    public function absen_masuk() {

        return view('user.home.absen_masuk');

    }

    public function absen_masuk_store(Request $request) {

        // Validasi data yang diterima
        $request->validate([
            'photo-data' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $userId = Auth::id();

        // Mendapatkan data dari request
        $photoData = $request->input('photo-data');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Mengubah foto dari base64 menjadi file
        $image = str_replace('data:image/png;base64,', '', $photoData);
        $image = str_replace(' ', '+', $image);
        $imageName = 'absen_' . time() . '.png'; // Menggunakan waktu untuk penamaan unik

        // Simpan file ke storage
        Storage::disk(name: 'public')->put($imageName, base64_decode($image));

        // Simpan data absen ke database
        Kehadiran::create([
            'user_id' => $userId,
            'check_in_photo' => $imageName,
            'check_in_latitude' => $latitude,
            'check_in_longitude' => $longitude,
            'work_date' => Carbon::now(),
            'check_in_time' => Carbon::now(),
            'check_out_time' => Carbon::now(), // Menggunakan Carbon untuk timestamp
            // Tambahkan kolom lain jika diperlukan
        ]);

        // Redirect atau return response
        return redirect('/home')->with('success', 'Data absen berhasil dikirim!');
    

    }

    // public function absen_masuk_store(Request $request) {

    //     // Log::info('Store method called.');

    //     dd('Store method called');
    // }

    // Fungsi untuk menyimpan foto
    private function savePhoto($photoData)
    {
        // Menghapus prefix data URL
        $photoData = str_replace('data:image/png;base64,', '', $photoData);
        $photoData = str_replace(' ', '+', $photoData);
        $image = base64_decode($photoData);

        // Membuat nama file unik
        $fileName = 'absen_' . time() . '.png';

        // Menyimpan file ke storage
        Storage::disk('public')->put($fileName, $image);

        // Mengembalikan path penyimpanan
        return 'storage/' . $fileName;
    }

}