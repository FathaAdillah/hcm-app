<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\JabatansController;
use App\Http\Controllers\PositionsController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\GeofencingsController;
use App\Http\Controllers\CompaniesController;
use App\Models\Absensi;

Route::get('/', function () {
    return view('pages.auth.auth-login');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/home', function () {
        return view('pages.welcome');
    })->name('home');
    Route::get('/403', function () {
        return view('pages.error-403');
    })->name('403');
    Route::get('/reset-password', function () {
        return view('pages.auth.auth-reset-password');
    })->name('reset-password');
    Route::post('/reset-password', [ResetPasswordController::class, 'update'])->name('password.update');
    Route::resource('absen', AbsenController::class);
    Route::resource('profile', ProfileController::class);




    Route::middleware(['role:admin'])->group(function () {
        Route::get('/mainpage', function () {
            return view('admin.dashboard');
        })->name('mainpage');
        Route::resource('users', UsersController::class);
        Route::resource('employees', EmployeesController::class);
        Route::resource('units', UnitsController::class);
        Route::resource('jabatans', JabatansController::class);
        Route::resource('positions', PositionsController::class);
        Route::resource('schedules', SchedulesController::class);
        Route::resource('geofencings', GeofencingsController::class);
        Route::resource('companies', CompaniesController::class);
    });
});
