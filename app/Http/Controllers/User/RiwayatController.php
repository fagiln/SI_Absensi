<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function show(){
        
        $kehadiran = Kehadiran::where('user_id', Auth::id())->get();

        // Data tanggal dan jam 
        foreach($kehadiran as $kehadirans){
            $checkInTime = Carbon::parse($kehadirans->check_in_time);
            $checkOutTime = Carbon::parse($kehadirans->check_out_time);

            // Menghitung jam kerja dengan mengurangi 1 jam dari selisih check in dan check out
            $totalHours = $checkOutTime->diffInHours($checkInTime);
            // $totalHours = $checkOutTime->diffInHours($checkInTime) - 1;

            // Menambahkan atribut jam kerja ke setiap attendance
            $kehadirans->jam_kerja = $totalHours;
        }
        
        return view('user.riwayat', compact('kehadiran'));

    }

    public function table_show(){

    }

}