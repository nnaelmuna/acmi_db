<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProductController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Kalau user BELUM login
Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/signin', 'showLoginForm')->name('login');
        Route::post('/signin', 'login');
    });
});

// Kalau user SUDAH login
Route::middleware('auth')->group(function () {

    // --- PRODUCT ROUTES ---
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product', 'index')->name('product.index');
        Route::get('/product/create', 'create')->name('product.create');
        Route::post('/product', 'store')->name('product.store');
        Route::get('/product/{id}/edit', 'edit')->name('product.edit'); // Tampilan edit
        Route::put('/product/{id}', 'update')->name('product.update'); // Proses simpan edit
        Route::delete('/product/{id}', 'destroy')->name('product.destroy'); // Proses hapus
    });

    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- POST ---
    Route::controller(PostController::class)->group(function () {
        Route::get('/post', 'index')->name('post');
        Route::get('/post/create', 'create')->name('post.create');
        Route::post('/post', 'store')->name('post.store');
    });

    // --- FAQ ---
    Route::controller(FaqController::class)->group(function () {
        Route::get('/faq', 'index')->name('faq');
        Route::post('/faq', 'store')->name('faq.store');
        Route::put('/faq/{id}', 'update')->name('faq.update');
        Route::delete('/faq/{id}', 'destroy')->name('faq.destroy');
    });

    // --- LOGOUT ---
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});