<?php

use App\Jobs\SendEmailJob;
use App\Mail\AbsensiReportMail;
use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('send-email', function () {
    $data['email'] = 'fagilnuril50@gmail.com';
    dispatch(new SendEmailJob($data));
    return 'success';
});
