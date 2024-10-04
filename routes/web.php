<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\WebController;
Route::get('/', function () {
    return view('welcome');
});



Route::get('home', function () {
    return view('home');
});

Route::get('dosen',[DosenController::class,'index']);

// Route::get('notifications', [NotificationController::class, 'index']);

Route::get('/masuk', function () {
    return view('masuk');
});

Route::get('/bottomnav', function () {
    return view('bottomnav');
});


route::get('/maps', [WebController::class, 'maps'])->name('maps');
