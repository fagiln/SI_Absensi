<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kehadiran;
use App\Models\Perizinan;

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
        $userId = auth()->id(); // Mendapatkan ID pengguna yang sedang login
        $perizinan = Perizinan::where('user_id', $userId)->orderBy('created_at', 'desc')->first();
       
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
        
        // Jam kerja hari ini
        $kehadiran = Kehadiran::where('user_id', $userId)
                                ->whereDate('check_in_time', Carbon::now()->format('Y-m-d'))
                                ->first(); // Mengambil record kehadiran hari ini

        // Pastikan ada data kehadiran
        if ($kehadiran && $kehadiran->check_out_time) {
            // Mengambil waktu checkin dan check-out
            $checkInTime = Carbon::parse($kehadiran->check_in_time);
            $checkOutTime = Carbon::parse($kehadiran->check_out_time);

            // Menghitung selisih jam kerja dengan mengurangi 1 jam untuk istirahat
            $jamKerja = $checkOutTime->diffInHours($checkInTime); 

        // Format untuk menampilkan hanya jam
            $jamKerjaFormatted = $jamKerja . ' Jam';
        } else {
            $jamKerjaFormatted = 'Belum Absen Pulang';
        }

        // Menghitung total jam kerja selama 1 bulan
        $totalJamKerja = Kehadiran::where('user_id', $userId)
                                    ->whereMonth('check_in_time', Carbon::now()->month) // Filter berdasarkan bulan
                                    ->whereYear('check_in_time', Carbon::now()->year) // Filter berdasarkan tahun
                                    ->get(); // Ambil semua record

        // Menjumlahkan jam kerja bulan ini
        $totalJam = 0;
        foreach ($totalJamKerja as $kehadiranBulanan) {
            if ($kehadiranBulanan->check_out_time) {
            $checkIn = Carbon::parse($kehadiranBulanan->check_in_time);
            $checkOut = Carbon::parse($kehadiranBulanan->check_out_time);
            $jamTotal = $checkOut->diffInHours($checkIn); // Mengurangi 1 jam istirahat
            $totalJam += $jamTotal; // Menjumlahkan jam total
            }
        }

        // Format total jam kerja
        $totalJamFormatted = $totalJam . ' Jam';

        // Menghitung jumlah kehadiran selama satu bulan
        $hadirCount = Kehadiran::where('user_id', $userId)
                                ->where('status', 'hadir')
                                ->whereMonth('work_date', Carbon::now()->month)
                                ->whereYear('work_date', Carbon::now()->year)
                                ->count();

        $izinCount = Perizinan::where('user_id', $userId)
                                ->where('reason', 'izin')
                                ->where('status', 'diterima')
                                ->whereMonth('start_date', Carbon::now()->month)
                                ->whereYear('start_date', Carbon::now()->year)
                                ->count();

        $sakitCount = Perizinan::where('user_id', $userId)
        ->where('reason', 'sakit')
        ->where('status', 'diterima')
        ->whereMonth('start_date', Carbon::now()->month)
        ->whereYear('start_date', Carbon::now()->year)
        ->count();

        $terlambatCount = Kehadiran::where('user_id', $userId)
        ->where('status', 'telat')
        ->whereMonth('work_date', Carbon::now()->month)
        ->whereYear('work_date', Carbon::now()->year)
        ->count();

        // menampilkan data terbaru
        $kehadiranTerbaru = Kehadiran::where('user_id', $userId)
        ->orderBy('work_date', 'desc')
        ->limit(5) // Menampilkan 5 data terbaru, bisa disesuaikan
        ->get();

        return view('user.home.home', 
        compact('user', 'greeting', 'absenToday', 'absenpulangToday', 'jamKerjaFormatted', 'totalJamFormatted',
        'hadirCount', 'izinCount', 'sakitCount', 'terlambatCount', 'kehadiranTerbaru', 'perizinan'));
    }

    public function absen_masuk() {

        $today = Carbon::today();
        $userId = auth()->id(); // Ambil ID pengguna yang sedang login

        // Cek apakah data absen untuk hari ini sudah ada
        $existingAbsen = Kehadiran::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->first();

        if ($existingAbsen) {
            // Jika sudah ada, arahkan ke halaman lain atau tampilkan pesan
            return redirect()->route('home')->with('message', 'Anda sudah absen hari ini.');
        }

        // Kembalikan view dengan data yang diperlukan
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
        \Storage::disk('public')->put('kehadiran/' . $imageName, base64_decode($image));

        // Waktu check-in yang ditentukan (contoh: 08:10)
        $scheduledCheckInTime = Carbon::createFromTime(8, 10);

        // Mendapatkan waktu sekarang
        $currentCheckInTime = Carbon::now();

        // Menentukan status kehadiran
        $status = $currentCheckInTime->greaterThan($scheduledCheckInTime) ? 'telat' : 'hadir';

        // Menyimpan data kehadiran ke database
        Kehadiran::create([
            'user_id' => $userId,
            'check_in_photo' => $imageName,
            'check_in_latitude' => $latitude,
            'check_in_longitude' => $longitude,
            'work_date' => Carbon::now(),
            'check_in_time' => $currentCheckInTime, 
            'status' => $status, // Menyimpan status kehadiran
        ]);

        \Log::info("Data diterima: ", $request->all());

        // Ambil data admin pertama yang ditemukan
        $admin = User::where('role', 'admin')->first();
        $username = Auth::user()->name;

        // Redirect atau return response
        // return redirect('/home')->with('success', 'Data absen berhasil dikirim!');
        return response()->json([
            'success' => true,
            'userName' => $username,
            'checkInTime' => Carbon::parse($currentCheckInTime)->format('d F Y \J\a\m H:i'),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'adminPhone' => $admin->no_hp,
        ]);

    }

    // Fungsi untuk menyimpan foto
    private function savePhoto($photoData){
        // Menghapus prefix data URL
        $photoData = str_replace('data:image/png;base64,', '', $photoData);
        $photoData = str_replace(' ', '+', $photoData);
        $image = base64_decode($photoData);

        // Membuat nama file unik
        $fileName = 'absen_' . time() . '.png';

        // Menyimpan file ke storage
        Storage::disk('public')->put('kehadiran/' . $fileName, $image);

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

        // Cek apakah data absen untuk hari ini sudah ada
        $existingAbsen = Kehadiran::where('user_id', $userId)
            ->whereDate('check_out_time', Carbon::now()->format('Y-m-d'))
            ->exists();

        if ($existingAbsen) {
            // Jika sudah ada, arahkan ke halaman lain atau tampilkan pesan
            return redirect()->route('home')->with('message', 'Anda sudah absen hari ini.');
        }

        return view('user.home.absen_pulang', compact('absenToday'));
        
    }

    public function absen_pulang_store(Request $request) {
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
        \Storage::disk('public')->put('kehadiran/' . $imageName, base64_decode($image));

        // Cari absen masuk yang ada pada hari yang sama
        $today = Carbon::now()->format('Y-m-d');
        $kehadiran = Kehadiran::where('user_id', $userId)
                    ->whereDate('check_in_time', $today) // Mengecek data berdasarkan absen masuk hari ini
                    ->first();

        $currentCheckOutTime = Carbon::now();

        if ($kehadiran) {
            // Update absen pulang pada baris yang sama
            $kehadiran->update([
                'check_out_photo' => $imageName,
                'check_out_latitude' => $latitude,
                'check_out_longitude' => $longitude,
                'check_out_time' => $currentCheckOutTime,
            ]);

            $admin = User::where('role', 'admin')->first();
            $username = Auth::user()->name;

            // return redirect('/home')->with('success', 'Data absen pulang berhasil dikirim!');
            return response()->json([
                'success' => true,
                'userName' => $username,
                'checkOutTime' => Carbon::parse($currentCheckOutTime)->format('d F Y \J\a\m H:i'),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'adminPhone' => $admin->no_hp,
            ]);
        } else {
            // Jika tidak ada absen masuk, kembalikan pesan error
            return redirect('/home')->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }
    }

}