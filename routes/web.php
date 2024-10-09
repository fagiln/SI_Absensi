<?php


// Admin Controller
use App\Http\Controllers\Admin\DashboardController;

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