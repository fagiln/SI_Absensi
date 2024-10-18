<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kehadiran;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function show(){

        $user = Auth::user();
        $userId = auth()->id(); // Mendapatkan ID pengguna yang sedang login
       
            // Memeriksa apakah sudah absen hari ini
            $absenToday = Kehadiran::where('user_id', $userId)
                ->whereDate('check_in_time', Carbon::now()->format('Y-m-d'))
                ->exists();

            // Memeriksa apakah sudah absen pulang hari ini
            $absenpulangToday = Kehadiran::where('user_id', $userId)
                ->whereDate('check_out_time', Carbon::now()->format('Y-m-d'))
                ->exists();

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
        
        // jam kerja
        $kehadiran = Kehadiran::where('user_id', $userId)
                                ->whereDate('check_in_time', Carbon::now()->format('Y-m-d'))
                                ->first(); // Mengambil record kehadiran hari ini

        // Pastikan ada data kehadiran
        if ($kehadiran && $kehadiran->check_out_time) {
            // Mengambil waktu checkin dan check-out
            $checkInTime = Carbon::parse($kehadiran->check_in_time);
            $checkOutTime = Carbon::parse($kehadiran->check_out_time);

            // Menghitung selisih jam kerja dengan mengurangi 1 jam untuk istirahat
            $jamKerja = $checkOutTime->diffInHours($checkInTime) - 1; 

            // Format untuk menampilkan hanya jam
            $jamKerjaFormatted = $jamKerja . ' Jam';
        } else {
            $jamKerjaFormatted = 'Belum Absen Pulang';
        }

        return view('user.home.home', compact('user', 'greeting', 'absenToday', 'absenpulangToday', 'jamKerjaFormatted'));
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
        $userid = auth()->user()->id;

        // Mendapatkan data dari request
        $photoData = $request->input('photo-data');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Mengubah foto dari base64 menjadi file
        $image = str_replace('data:image/png;base64,', '', $photoData);
        $image = str_replace(' ', '+', $image);
        $imageName = 'absen_in_' . $userid . '_' . now()->format('Ymd') . '.png'; // Menggunakan waktu untuk penamaan unik

        // Simpan file ke storage
        Storage::disk('public')->put($imageName, base64_decode($image));

        // Simpan data absen ke database
        Kehadiran::create([
            'user_id' => $userId,
            'check_in_photo' => $imageName,
            'check_in_latitude' => $latitude,
            'check_in_longitude' => $longitude,
            'work_date' => Carbon::now(),
            'check_in_time' => Carbon::now(), 
        ]);

        // Redirect atau return response
        return redirect('/home')->with('success', 'Data absen berhasil dikirim!');
    

    }

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
        return 'storage/kehadiran' . $fileName;
    }

    public function absen_pulang() {
        $userId = Auth::id();
        
        // Cek apakah sudah absen masuk hari ini
        $absenToday = Kehadiran::where('user_id', $userId)
        ->whereDate('check_in_time', Carbon::now()->format('Y-m-d'))
        ->exists();
        
        // dd(session()->all()); 
        if (!$absenToday) {
            // Jika belum absen masuk, alihkan kembali dengan pesan error
            return redirect()->back()->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }
        // dd($absenToday); 

        return view('user.home.absen_pulang', compact('absenToday'));

    }

    public function absen_pulang_store(Request $request)
{
    // Validasi data yang diterima
    $request->validate([
        'photo-data' => 'required|string',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    $userId = Auth::id(); // Mendapatkan ID pengguna yang sedang login

    // Mendapatkan data dari request
    $photoData = $request->input('photo-data');
    $latitude = $request->input('latitude');
    $longitude = $request->input('longitude');

    // Mengubah foto dari base64 menjadi file
    $image = str_replace('data:image/png;base64,', '', $photoData);
    $image = str_replace(' ', '+', $image);
    $imageName = 'absen_out_' . $userId . '_' . now()->format('Ymd') . '.png'; // Penamaan unik untuk foto absen pulang

    // Simpan file ke storage
    \Storage::disk('public')->put($imageName, base64_decode($image));

    // Cari absen masuk yang ada pada hari yang sama
    $today = Carbon::now()->format('Y-m-d');
    $kehadiran = Kehadiran::where('user_id', $userId)
                ->whereDate('check_in_time', $today) // Mengecek data berdasarkan absen masuk hari ini
                ->first();

    if ($kehadiran) {
        // Update absen pulang pada baris yang sama
        $kehadiran->update([
            'check_out_photo' => $imageName,
            'check_out_latitude' => $latitude,
            'check_out_longitude' => $longitude,
            'check_out_time' => Carbon::now(),
        ]);

        return redirect('/home')->with('success', 'Data absen pulang berhasil dikirim!');
    } else {
        // Jika tidak ada absen masuk, kembalikan pesan error
        return redirect('/home')->with('error', 'Anda belum melakukan absen masuk hari ini.');
    }
}


}