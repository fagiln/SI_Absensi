<?php


// Admin Controller
use App\Http\Controllers\Admin\DashboardController;
<<<<<<< HEAD

// User Controller
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\NotifController;
use App\Http\Controllers\User\RiwayatController;
use App\Http\Controllers\User\CutiController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\LayoutController;

=======
use App\Http\Controllers\Admin\DepartemenController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PerizinanController;
>>>>>>> 804975fb0ab1aa569b76d46efe175b35a403ebdc
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

// Route::get('/home', function () {
//     if (Auth::user()->role == 'admin') {

//         return redirect(route('admin.dashboard'));
//     }
// });


// Route::middleware('guest')->group(function () {
//     Route::get('/', [LoginController::class, 'show'])->name('login');

//     Route::post('/', [LoginController::class, 'authenticate'])->name('login.authenticate');
// });

<<<<<<< HEAD
// Route::middleware(['auth', 'verified', 'user.role:admin'])->group(function () {
//     Route::get('admin/dashboard', [DashboardController::class, 'show'])->name('admin.dashboard');
//     Route::get('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');
// });

// {{---------------------- User ------------------------------}}

// -------------------- Home ---------------------------

Route::get('/', [HomeController::class, 'show'])->name('home');

// -------------------- Profile -------------------------

Route::get('/user/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/user/profile/update_foto/{id}', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
Route::post('/user/profile/update_data/{id}', [ProfileController::class, 'update_dataprofile'])->name('profile.update_dataprofile');
Route::post('/user/profile/update_pass/{id}', [ProfileController::class, 'update_pass'])->name('profile.update_pass');

// -------------------- Notif ---------------------------

Route::get('/user/notif', [NotifController::class, 'show'])->name('notif');

// -------------------- Riwayat -------------------------

Route::get('/user/riwayat', [RiwayatController::class, 'show'])->name('riwayat');

// -------------------- Cuti ----------------------------

Route::get('/user/cuti', [CutiController::class, 'show'])->name('cuti');
Route::get('/user/cuti-detail', [CutiController::class, 'showdetail'])->name('cuti-detail');

// -------------------- Layouts ----------------------------

// Route::get('/user/profile', [LayoutController::class, 'index'])->name('profile');
=======
Route::middleware(['auth', 'verified', 'user.role:admin'])->group(function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

        Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
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

        Route::get('perizinan', [PerizinanController::class, 'index'])->name('perizinan.index');
        Route::put('perizinan/status/{id}', [PerizinanController::class, 'update'])->name('perizinan.status');
    });
});
>>>>>>> 804975fb0ab1aa569b76d46efe175b35a403ebdc
