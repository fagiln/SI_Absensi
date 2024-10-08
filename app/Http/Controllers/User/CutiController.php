<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    public function show(){
        return view('user.cuti');
    }
    
    public function showdetail(){
        return view('user.cuti-detail');
    }
}