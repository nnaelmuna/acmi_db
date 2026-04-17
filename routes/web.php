<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController; // Pastikan ini ditambahkan

// Rute Publik (Halaman depan jika ada)
Route::get('/', function () {
    // Sementara kita arahkan langsung ke halaman login saja
    return redirect()->route('login'); 
});

/*
|--------------------------------------------------------------------------
| Rute Guest (Hanya bisa diakses jika BELUM login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/signup', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/signup', [RegisterController::class, 'register']);

    Route::get('/signin', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/signin', [LoginController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Rute Admin CMS (HANYA bisa diakses jika SUDAH login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Rute Keamanan
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Rute Dashboard (Sudah memanggil Controller agar angkanya dinamis)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Rute Post & FAQ (Sementara pakai view langsung tidak apa-apa, tapi harus di dalam sini agar aman)
    Route::get('/post', function () {
        return view('post');
    })->name('post');

    Route::get('/faq', function () {
        return view('faq');
    })->name('faq');

});