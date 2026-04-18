<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// 1. Rute Publik (Langsung lempar ke dashboard)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// 2. Rute Guest (Belum login)
Route::middleware('guest')->group(function () {
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/signup', 'showRegistrationForm')->name('register');
        Route::post('/signup', 'register');
    });

    Route::controller(LoginController::class)->group(function () {
        Route::get('/signin', 'showLoginForm')->name('login');
        Route::post('/signin', 'login');
    });
});

// 3. Rute Dashboard & Admin (Biar bisa dibuka saat php artisan serve)
// Note: Middleware 'auth' dimatikan sementara biar kamu bisa cek output tanpa login
Route::middleware([])->group(function () { 

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/post', [PostController::class, 'index'])->name('post');
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    
    Route::get('/faq', function () {
        return view('faq');
    })->name('faq');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});