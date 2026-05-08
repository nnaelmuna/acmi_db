<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\MediaCategoryController;
use App\Http\Controllers\Admin\MediaItemController;
use App\Http\Controllers\Admin\MediaPartnerController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CRM\InboundController;

// Redirect ke login
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

    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- POST ---
    Route::controller(PostController::class)->group(function () {
        Route::get('/post', 'index')->name('post');
        Route::get('/post/create', 'create')->name('post.create');
        Route::post('/post', 'store')->name('post.store');
        Route::get('/post/{post}/edit', 'edit')->name('post.edit');
        Route::put('/post/{post}', 'update')->name('post.update');
        Route::delete('/post/{post}', 'destroy')->name('post.destroy');
        // Tambahkan route baru untuk Trash actions
        Route::post('/posts/{id}/restore', [PostController::class, 'restore'])
            ->name('posts.restore');

        Route::delete('/posts/{id}/force-delete', [PostController::class, 'forceDelete'])
            ->name('posts.forceDelete');
    });

    // POST CATEGORY
    Route::controller(CategoryController::class)->group(function () {
        Route::post('/post-categories', 'store')->name('categories.store');
        Route::delete('/post-categories/{category}', 'destroy')->name('categories.destroy');
    });

    // Category Post
    Route::post('/post-categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/post-categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // --- FAQ ---
    Route::controller(FaqController::class)->group(function () {
        Route::get('/faq', 'index')->name('faq');
        Route::post('/faq', 'store')->name('faq.store');
        Route::put('/faq/{id}', 'update')->name('faq.update');
        Route::delete('/faq/{id}', 'destroy')->name('faq.destroy');
    });

    // --- PRODUCT ROUTES ---
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product', 'index')->name('product.index');
        Route::get('/product/create', 'create')->name('product.create');
        Route::post('/product', 'store')->name('product.store');
        Route::get('/product/{id}/edit', 'edit')->name('product.edit');
        Route::put('/product/{id}', 'update')->name('product.update');
        Route::delete('/product/{id}', 'destroy')->name('product.destroy');
        Route::get('/product/{id}', 'show')->name('product.show');
        Route::post('/product/{id}/restore', 'restore')->name('product.restore');
        Route::delete('/product/{id}/force-delete', 'force-delete')->name('product.forceDelete');
    });

    Route::post('/product-categories', [ProductCategoryController::class, 'store'])->name('product.categories.store');
    Route::delete('/product-categories/{id}', [ProductCategoryController::class, 'destroy'])->name('product.categories.destroy');

    // Media Category
    Route::get('/categories', [MediaCategoryController::class, 'index'])->name('media.categories');
    Route::post('/categories', [MediaCategoryController::class, 'store'])->name('media.categories.store');
    Route::put('/categories/{id}', [MediaCategoryController::class, 'update'])->name('media.categories.update');
    Route::delete('/categories/{id}', [MediaCategoryController::class, 'destroy'])->name('media.categories.destroy');

    // Media Item
    Route::prefix('media')->group(function () {
        Route::get('/', [MediaItemController::class, 'index'])->name('media');
        Route::post('/', [MediaItemController::class, 'store'])->name('media.store');
        Route::put('/{id}', [MediaItemController::class, 'update'])->name('media.update');
        Route::delete('/{id}', [MediaItemController::class, 'destroy'])->name('media.destroy');
    });

    // Media Partner
    Route::controller(MediaPartnerController::class)->group(function () {
        Route::get('/media-partner', 'index')->name('media-partner');
        Route::post('/media-partner', 'store')->name('media-partner.store');
        Route::put('/media-partner/{id}', 'update')->name('media-partner.update');
        Route::delete('/media-partner/{id}', 'destroy')->name('media-partner.destroy');
    });

    // Inbound
    Route::prefix('crm')->group(function () {
        Route::get('/inbound', [InboundController::class, 'index'])->name('inbound.index');
        Route::get('/inbound/{id}', [InboundController::class, 'show'])->name('inbound.show');
        Route::patch('/inbound/{id}/status', [InboundController::class, 'updateStatus'])->name('inbound.status');
        Route::post('/inbound/bulk-approve', [InboundController::class, 'bulkApprove'])->name('inbound.bulkApprove');
    });

    Route::get('/cek-session', function () {
        return config('session.lifetime'); // harusnya return 480
    });

    // --- LOGOUT ---
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
