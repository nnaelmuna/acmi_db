<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\MediaCategoryController;
use App\Http\Controllers\Admin\MediaItemController;
use App\Http\Controllers\CategoryController;

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
        Route::get('/post/{post}/edit', 'edit')->name('post.edit');
        Route::put('/post/{post}', 'update')->name('post.update');
    });

    // Category Post
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Category Post
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // --- FAQ ---
    Route::controller(FaqController::class)->group(function () {
        Route::get('/faq', 'index')->name('faq');
        Route::post('/faq', 'store')->name('faq.store');
        Route::put('/faq/{id}', 'update')->name('faq.update');
        Route::delete('/faq/{id}', 'destroy')->name('faq.destroy');
    });

    // --- Media ---
    // Media Category
    Route::get('/categories', [MediaCategoryController::class, 'index'])->name('media.categories');
    Route::post('/categories', [MediaCategoryController::class, 'store'])->name('media.categories.store');
    Route::put('/categories/{id}', [MediaCategoryController::class, 'update'])->name('media.categories.update');
    Route::delete('/categories/{id}', [MediaCategoryController::class, 'destroy'])->name('media.categories.destroy');
    Route::post('/categories/{id}/delete', [MediaCategoryController::class, 'destroy'])->name('media.categories.delete');

    // Media Item
    Route::prefix('media')->group(function () {
        Route::get('/', [MediaItemController::class, 'index'])->name('media');
        Route::post('/', [MediaItemController::class, 'store'])->name('media.store');
        Route::put('/{id}', [MediaItemController::class, 'update'])->name('media.update');
        Route::delete('/{id}', [MediaItemController::class, 'destroy'])->name('media.destroy');

});


    // --- LOGOUT ---
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});