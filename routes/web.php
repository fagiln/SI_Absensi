<?php


// Admin Controller
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartemenController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PerizinanController;
use App\Http\Controllers\Admin\PresensiController;
use App\Http\Controllers\Admin\RekapPresensiController;
// User Controller
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\NotifController;
use App\Http\Controllers\User\RiwayatController;
use App\Http\Controllers\User\CutiController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\LayoutController;

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

// {{-------------------- Admin -----------------------------}}

Route::get('/home', function () {
    if (Auth::user()->role == 'admin') {
        return redirect(route('admin.dashboard'));
    }elseif(Auth::user()->role == 'user'){
        return redirect(route('home'));
    }
});


Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'show'])->name('login');

    Route::post('/', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware(['auth', 'verified', 'user.role:admin'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'show'])->name('admin.dashboard');
    Route::get('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');
});


Route::middleware(['auth', 'verified', 'user.role:admin'])->group(function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

        Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
        Route::get('karyawan-izin', [DashboardController::class, 'karyawanIzin'])->name('karyawan.izin');
        Route::get('karyawan-sakit', [DashboardController::class, 'karyawanSakit'])->name('karyawan.sakit');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/karyawan', [KaryawanController::class, 'index'])->name('index.karyawan');
        Route::post('karyawan/add', [KaryawanController::class, 'store'])->name('karyawan.add');
        Route::get('karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.delete');
        Route::get('/karyawan', [KaryawanController::class, 'index'])->name('index.karyawan');
        Route::get('/departemen', [DepartemenController::class, 'index'])->name('index.departemen');
        Route::post('departemen/add', [DepartemenController::class, 'store'])->name('departemen.add');
        Route::get('departemen/{id}/edit', [DepartemenController::class, 'edit'])->name('departemen.edit');
        Route::put('departemen/{id}', [DepartemenController::class, 'update'])->name('departemen.update');
        Route::delete('/departemen/{id}', [DepartemenController::class, 'destroy'])->name('departemen.delete');

        Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('karyawan-cuti', [MonitoringController::class, 'karyawanCuti'])->name('karyawan.cuti');


        Route::get('perizinan', [PerizinanController::class, 'index'])->name('perizinan.index');
        Route::put('perizinan/status/{id}', [PerizinanController::class, 'update'])->name('perizinan.status');
        Route::get('perizinan/edit/{id}', [PerizinanController::class, 'edit'])->name('perizinan.edit');

        Route::get('presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::get('presensi/export', [PresensiController::class, 'export'])->name('presensi.export');
        Route::get('presensi/print', [PresensiController::class, 'print'])->name('presensi.print');
        Route::get('presensi/search', [PresensiController::class, 'search'])->name('presensi.search');

        Route::get('rekap-presensi', [RekapPresensiController::class, 'index'])->name('rekap-presensi.index');
        Route::get('rekap-presensi/export', [RekapPresensiController::class, 'export'])->name('rekap-presensi.export');
        Route::get('rekap-presensi/print', [RekapPresensiController::class, 'print'])->name('rekap-presensi.print');
    });
});


// {{---------------------- User ------------------------------}}

Route::middleware(['auth', 'verified', 'user.role:user'])->group(function () {
    // Route::group(['prefix' => 'user', ], function () {

    // -------------------- Home ---------------------------

    Route::get('/user/home', [HomeController::class, 'show'])->name('home');
    Route::get('user/absen_masuk', [HomeController::class, 'absen_masuk'])->name('absen_masuk');
    Route::post('user/absen_masuk', [HomeController::class, 'absen_masuk_store'])->name('absen_masuk.store');
    Route::get('user/absen_pulang', [HomeController::class, 'absen_pulang'])->name('absen_pulang');
    Route::post('user/absen_pulang', [HomeController::class, 'absen_pulang_store'])->name('absen_pulang.store');

    // -------------------- Profile -------------------------

    Route::get('/user/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/user/profile/update_foto', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
    Route::post('/user/profile/update_data', [ProfileController::class, 'update_dataprofile'])->name('profile.update_dataprofile');
    Route::post('/user/profile/update_pass', [ProfileController::class, 'update_pass'])->name('profile.update_pass');

    // -------------------- Notif ---------------------------

    Route::get('/user/notif', [NotifController::class, 'show'])->name('notif');

    // -------------------- Riwayat -------------------------

    Route::get('/user/riwayat', [RiwayatController::class, 'show'])->name('riwayat');

    // -------------------- Cuti ----------------------------

    Route::get('/user/cuti', [CutiController::class, 'show'])->name('cuti');
    Route::post('/user/cuti/create_cuti', [CutiController::class, 'create_cuti'])->name('cuti.create');
    Route::get('/user/cuti-detail/{id}', [CutiController::class, 'showdetail'])->name('cuti-detail');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

});

