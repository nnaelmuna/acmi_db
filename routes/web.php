<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PostController;
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
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Post
    Route::get('/post', [PostController::class, 'index'])->name('post');
    // Rute Create New Post
    Route::get('/post', [PostController::class, 'create'])->name('post.create');
    // Rute proses form saat submit
    Route::post('/post', [PostController::class, 'store'])->name('post.store');

    Route::get('/faq', function () {
        return view('faq');
    })->name('faq');

});