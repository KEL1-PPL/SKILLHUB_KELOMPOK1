<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Siswa\KursusController;
use App\Http\Controllers\Siswa\RiwayatController;
use App\Http\Controllers\Mentor\ProgressController;
use Illuminate\Support\Facades\Route;

// Landing Page Route
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


//Register
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// User yang sudah login, baik itu role admin, mentor dan siswa
Route::middleware('auth')->group(function () {
    Route::get('dashboard',DashboardController::class)->name('dashboard');

    //Route untuk siswa
    Route::prefix('siswa')->group(function(){
        Route::get('kursus', [KursusController::class, 'index'])->name('siswa.kursus.index');
        Route::get('riwayat', [RiwayatController::class, 'index'])->name('siswa.riwayat.index');
        Route::get('riwayat/{id}/review', [RiwayatController::class, 'review'])->name('courses.review');
    });

    //Route untuk Mentor
    Route::prefix('mentor')->group(function(){
        Route::get('progress',[ProgressController::class,'index'])->name('mentor.progress.index');
    });
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');