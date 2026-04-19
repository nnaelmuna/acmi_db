<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Faq;

// 1. Rute Publik (Langsung lempar ke dashboard)
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Rute Guest (Belum login)
Route::middleware('guest')->group(function () {
    
    // // Auth - Register
    // Route::controller(RegisterController::class)->group(function() {
    //     Route::get('/signup', 'showRegistrationForm')->name('register');
    //     Route::post('/signup', 'register');
    // });


    Route::controller(LoginController::class)->group(function () {
        Route::get('/signin', 'showLoginForm')->name('login');
        Route::post('/signin', 'login');
    });
});

/*
|--------------------------------------------------------------------------
| Rute Admin CMS (HANYA bisa diakses jika SUDAH login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Rute Keamanan (Logout)
    Route::controller(LoginController::class)->group(function() {
        Route::post('/logout', 'logout')->name('logout');
    });
    
    // Dashboard
    Route::controller(DashboardController::class)->group(function() {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    // CRUD Post
    Route::controller(PostController::class)->group(function() {
        Route::get('/post', 'index')->name('post');
        Route::get('/post/create', 'create')->name('post.create');
        Route::post('/post', 'store')->name('post.store');
    });

    // Halaman FAQ
    Route::controller(FaqController::class)->group(function() {
        Route::get('/faq', 'index')->name('faq');
        Route::post('/faq', 'store')->name('faq.store');
        Route::put('/faq/{id}', 'update')->name('faq.update');
        Route::delete('/faq/{id}', 'destroy')->name('faq.destroy');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});