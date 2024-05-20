<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;


Route::get('/', function () {
    return view('pages.auth.auth-login');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/home', function () {
        return view('pages.welcome');})->name('home');
    Route::get('/403', function () {
        return view('pages.error-403');})->name('403');
    Route::resource('absen', AbsenController::class);
    Route::get('/reset-password', function () {
        return view('pages.auth.auth-reset-password');})->name('reset-password');


    Route::middleware(['role:admin'])->group(function () {
        Route::get('/mainpage', function () {
            return view('admin.dashboard');})->name('mainpage');
    });
});

