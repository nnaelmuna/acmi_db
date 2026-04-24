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

// kalau user belum login
Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/signin', 'showLoginForm')->name('login');
        Route::post('/signin', 'login');
    });
});

Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::controller(DashboardController::class)->group(function() {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    // Post
    Route::controller(PostController::class)->group(function () {
        Route::get('/post', 'index')->name('post');
        Route::get('/post/create', 'create')->name('post.create');
        Route::post('/post', 'store')->name('post.store');
    });

    // FAQ
    Route::controller(FaqController::class)->group(function () {
        Route::get('/faq', [FaqController::class, 'index'])->name('faq');
        Route::post('/faq', [FaqController::class, 'store'])->name('faq.store');
        Route::put('/faq/{id}', [FaqController::class, 'update'])->name('faq.update');
        Route::delete('/faq/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');
    });

    // Product
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product', 'index')->name('product');
        
    });

     
});