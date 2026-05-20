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
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Api\PublicContentController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SubscriptionController;

Route::prefix('public')->group(function () {
    Route::get('/articles', [PublicContentController::class, 'getArticles']);
    Route::get('/articles/{slug}', [PublicContentController::class, 'getArticleDetail']);
    Route::get('/faqs', [PublicContentController::class, 'getFaqs']);
    Route::get('/services', [PublicContentController::class, 'getServices']);
    Route::get('/gallery', [PublicContentController::class, 'getGallery']);
    Route::get('/partners', [PublicContentController::class, 'getPartners']);
    Route::get('/categories', [PublicContentController::class, 'getCategories']);
});

// Redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Kalau user blm login
Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/signin', 'showLoginForm')->name('login');
        Route::post('/signin', 'login');
    });
});

// Kalau user udah login
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard History
    Route::middleware(['auth'])->group(function () {
        Route::get('/history', [HistoryController::class, 'index'])->name('admin.history');
        Route::get('/history-recap', [HistoryController::class, 'index'])->name('admin.history');
    });

    // Post
    Route::controller(PostController::class)->group(function () {
        Route::get('/post', 'index')->name('post');
        Route::get('/post/create', 'create')->name('post.create');
        Route::post('/post', 'store')->name('post.store');

        Route::delete('/post/bulk-destroy', 'bulkDestroy')->name('post.bulkDestroy');
        Route::delete('/posts/bulk-force-delete', 'bulkForceDelete')->name('posts.bulkForceDelete');

        Route::get('/post/{post}/edit', 'edit')->name('post.edit');
        Route::put('/post/{post}', 'update')->name('post.update');
        Route::delete('/post/{post}', 'destroy')->name('post.destroy');

        Route::post('/posts/{id}/restore', 'restore')->name('posts.restore');
        Route::delete('/posts/{id}/force-delete', 'forceDelete')->name('posts.forceDelete');
        
    });

    // Post Category
    Route::controller(CategoryController::class)->group(function () {
        Route::post('/post-categories', 'store')->name('categories.store');
        Route::delete('/post-categories/{category}', 'destroy')->name('categories.destroy');
    });

    // Category Post
    Route::post('/post-categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/post-categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // FAQ
    Route::controller(FaqController::class)->group(function () {
        Route::get('/faq', 'index')->name('faq');
        Route::post('/faq', 'store')->name('faq.store');
        Route::put('/faq/{id}', 'update')->name('faq.update');
        Route::delete('/faq/{id}', 'destroy')->name('faq.destroy');
        Route::post('/faq/{id}/restore', [FaqController::class, 'restore'])->name('faq.restore');
        Route::delete('/faq/{id}/force-delete', [FaqController::class, 'forceDelete'])->name('faq.forceDelete');
    });

    // Product
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product', 'index')->name('product.index');
        Route::get('/product/create', 'create')->name('product.create');
        Route::post('/product', 'store')->name('product.store');

        Route::get('/product/{id}/edit', 'edit')->name('product.edit');
        Route::put('/product/{id}', 'update')->name('product.update');
        Route::delete('/product/{id}', 'destroy')->name('product.destroy');

        Route::post('/product/{id}/restore', 'restore')->name('product.restore');
        Route::delete('/product/{id}/force-delete', 'forceDelete')->name('product.forceDelete');

        // ini taruh paling bawah
        Route::get('/product/{id}', 'show')->name('product.show');
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
        Route::post('/media/{id}/restore', [MediaItemController::class, 'restore'])->name('media.restore');
        Route::delete('/media/{id}/force-delete', [MediaItemController::class, 'forceDelete'])->name('media.forceDelete');
    });

    // Media Partner
    Route::controller(MediaPartnerController::class)->group(function () {
        Route::get('/media-partner', 'index')->name('media-partner');
        Route::post('/media-partner', 'store')->name('media-partner.store');
        Route::put('/media-partner/{id}', 'update')->name('media-partner.update');
        Route::delete('/media-partner/{id}', 'destroy')->name('media-partner.destroy');
        Route::post('/media-partner/{id}/restore', 'restore')->name('media-partner.restore');
        Route::delete('/media-partner/{id}/force-delete', 'forceDelete')->name('media-partner.forceDelete');
    });

    // Inbound
    Route::prefix('crm')->group(function () {
        Route::get('/inbound', [InboundController::class, 'index'])->name('inbound.index');
        Route::get('/inbound/{id}', [InboundController::class, 'show'])->name('inbound.show');
        Route::patch('/inbound/{id}/status', [InboundController::class, 'updateStatus'])->name('inbound.status');
        Route::post('/inbound/bulk-approve', [InboundController::class, 'bulkApprove'])->name('inbound.bulkApprove');
        Route::post('/inbound/{id}/approve', [InboundController::class, 'approve'])->name('inbound.approve');
    });

    Route::get('/cek-session', function () {
        return config('session.lifetime'); // harusnya return 480
    });

    // Members
    Route::prefix('crm')->group(function () {
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/{id}', [MemberController::class, 'show'])->name('members.show');
        Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('members.destroy');
        Route::post('/{id}/restore', [MemberController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [MemberController::class, 'forceDelete'])->name('forceDelete');
    });

    Route::get('/subscription', [SubscriptionController::class, 'index'])
        ->name('subscription');;

    // Settings Config
    Route::get('/settings-config', [SettingsController::class, 'index'])->name('settings.index');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
