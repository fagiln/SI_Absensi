<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function show(){
        return view('user.riwayat');
    }
}