<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Faq;

// Rute Publik
Route::get('/', function () {
    return redirect()->route('dashboard'); 
});

/*
|--------------------------------------------------------------------------
| Rute Guest (Hanya bisa diakses jika BELUM login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    
    // // Auth - Register
    // Route::controller(RegisterController::class)->group(function() {
    //     Route::get('/signup', 'showRegistrationForm')->name('register');
    //     Route::post('/signup', 'register');
    // });

    // // routes/web.php
    // Route::middleware('auth')->group(function () {
    //     Route::get('/register', [RegisterController::class, 'showRegistrationForm']);
    //     Route::post('/register', [RegisterController::class, 'register']);
    // });


    // Auth - Login
    Route::controller(LoginController::class)->group(function() {
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
    Route::controller(AdminDashboardController::class)->group(function() {
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

});