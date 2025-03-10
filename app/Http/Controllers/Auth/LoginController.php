<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{

    public function show(){
        return view('auth.login');
    }
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role == 'admin') {
                $request->session()->regenerate();
                return redirect()->intended('admin/dashboard');
            } elseif (Auth::user()->role == 'user') {
                $request->session()->regenerate();
                return redirect()->intended('/user/home');
            }
        }
        return back()->withErrors([
            'username' => 'Login gagal, Mohon periksa kembali Username dan Password anda !',
        ]);
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
