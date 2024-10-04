<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// {{---------------------- User ------------------------------}}

// -------------------- Home ---------------------------

Route::get('/', function () {
    return view('/user/home');
})->name('home');

// -------------------- Profile -------------------------

Route::get('/user/profile', function () {
    return view('/user/profile');
})->name('profile');

// -------------------- Notif ---------------------------

Route::get('/user/notif', function () {
    return view('/user/notif');
})->name('notif');

// -------------------- Riwayat -------------------------

Route::get('/user/riwayat', function () {
    return view('/user/riwayat');
})->name('riwayat');

// -------------------- Cuti ----------------------------

Route::get('/user/cuti', function () {
    return view('/user/cuti');
})->name('cuti');
Route::get('/user/cuti-detail', function () {
    return view('/user/cuti-detail');
})->name('cuti-detail');


// {{-------------------- Admin -----------------------------}}

// Route::get('/home', function () {
//     if (Auth::user()->role == 'admin') {

//         return redirect(route('admin.dashboard'));
//     }
// });


// Route::middleware('guest')->group(function () {
//     Route::get('/', [LoginController::class, 'show'])->name('login');

//     Route::post('/', [LoginController::class, 'authenticate'])->name('login.authenticate');
// });

// Route::middleware(['auth', 'verified', 'user.role:admin'])->group(function () {
//     Route::get('admin/dashboard', [DashboardController::class, 'show'])->name('admin.dashboard');
//     Route::get('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');
// });
