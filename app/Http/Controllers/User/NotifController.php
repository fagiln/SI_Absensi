<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;

class NotifController extends Controller
{
    public function show(){
        return view('user.notif');
    }
}