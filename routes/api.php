<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/absen/checkin', [AbsenController::class, 'checkin'])->name('absensi.checkin')->middleware('auth:sanctum');
Route::post('/absen/checkout', [AbsenController::class, 'checkout'])->name('absensi.checkout')->middleware('auth:sanctum');
