<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function show(){

        $user = Auth::user();

        $currentTime = Carbon::now()->format('H'); // Ambil jam dalam format 24 jam
        // dd(Carbon::now());
        // Tentukan pesan berdasarkan waktu
        if ($currentTime >= 5 && $currentTime <= 11) {
            
            $greeting = 'Pagi';
        } elseif ($currentTime > 11 && $currentTime <= 15) {
            
            $greeting = 'Siang';
        } elseif ($currentTime > 15 && $currentTime < 18) {
            
            $greeting = 'Sore';
        } else {
            $greeting = 'Malam';
        }

        return view('user.home', compact('user', 'greeting'));
    }
}