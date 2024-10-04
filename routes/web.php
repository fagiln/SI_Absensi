<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KaryawanController;
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

Route::get('/home', function () {
    if (Auth::user()->role == 'admin') {

        return redirect(route('admin.dashboard'));
    }
});


Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'show'])->name('login');

    Route::post('/', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware(['auth', 'verified', 'user.role:admin'])->group(function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

        Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/karyawan', [KaryawanController::class, 'index'])->name('index.karyawan');
        Route::post('karyawan/add', [KaryawanController::class, 'store'])->name('karyawan.add');
        Route::get('karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.delete');
    });
});
