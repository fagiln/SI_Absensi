<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('cuti', function () {
    return view('cuti'); // Sesuaikan dengan nama view yang ingin ditampilkan
});

Route::get('/absenpulang', function () {
    return view('absenpulang');
});


route::get('/maps', [WebController::class, 'maps'])->name('maps');

