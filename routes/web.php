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
use App\Http\Controllers\Api\ApifyWebhookController;


Route::prefix('public')->controller(PublicContentController::class)->group(function () {
    Route::get('/articles', 'getArticles')->name('public.articles');
    Route::get('/articles/{slug}', 'getArticleDetail')->name('public.articles.detail');
    Route::get('/faqs', 'getFaqs')->name('public.faqs');
    Route::get('/services', 'getServices')->name('public.services');
    Route::get('/gallery', 'getGallery')->name('public.gallery');
    Route::get('/partners', 'getPartners')->name('public.partners');
    Route::get('/categories', 'getCategories')->name('public.categories');
});

Route::post('/webhook/apify', [ApifyWebhookController::class, 'handle']);


// Redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Kalau user belum login
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
    Route::middleware(['auth'])->controller(HistoryController::class)->group(function () {

        Route::get('/history', 'index')->name('admin.history');

        Route::get('/history-recap', 'index')->name('admin.history.recap');
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
        Route::put('/post-categories/{category}', 'update')->name('categories.update');
        Route::delete('/post-categories/{category}', 'destroy')->name('categories.destroy');
    });

    // FAQ
    Route::controller(FaqController::class)->group(function () {
        Route::get('/faq', 'index')->name('faq');
        Route::post('/faq', 'store')->name('faq.store');
        Route::put('/faq/{id}', 'update')->name('faq.update');
        Route::delete('/faq/{id}', 'destroy')->name('faq.destroy');
        Route::post('/faq/{id}/restore', 'restore')->name('faq.restore');
        Route::delete('/faq/{id}/force-delete', 'forceDelete')->name('faq.forceDelete');
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

        Route::get('/product/{id}', 'show')->name('product.show');
    });

    // Product Category
    Route::controller(ProductCategoryController::class)->group(function () {
        Route::post('/product-categories', 'store')->name('product.categories.store');
        Route::put('/product-categories/{id}', 'update')->name('product.categories.update');
        Route::delete('/product-categories/{id}', 'destroy')->name('product.categories.destroy');
    });

    // Media Category
    Route::controller(MediaCategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('media.categories');
        Route::post('/categories', 'store')->name('media.categories.store');
        Route::put('/categories/{id}', 'update')->name('media.categories.update');
        Route::delete('/categories/{id}', 'destroy')->name('media.categories.destroy');
    });

    // Media Item
    Route::prefix('media')->controller(MediaItemController::class)->group(function () {
        Route::get('/', 'index')->name('media');
        Route::post('/', 'store')->name('media.store');
        Route::put('/{id}', 'update')->name('media.update');
        Route::delete('/{id}', 'destroy')->name('media.destroy');
        Route::post('/{id}/restore', 'restore')->name('media.restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('media.forceDelete');
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
    Route::prefix('crm')->controller(InboundController::class)->group(function () {
        Route::get('/inbound', 'index')->name('inbound.index');
        Route::get('/inbound/{id}', 'show')->name('inbound.show');
        Route::patch('/inbound/{id}/status', 'updateStatus')->name('inbound.status');
        Route::post('/inbound/bulk-approve', 'bulkApprove')->name('inbound.bulkApprove');
        Route::post('/inbound', 'store')->name('inbound.store');
    });

    Route::get('/cek-session', function () {
        return config('session.lifetime');
    });

    Route::get('/csrf-token-refresh', function () {
        return response()->json(['token' => csrf_token()]);
    })->middleware('web');

    // Members
    Route::prefix('crm')->controller(MemberController::class)->group(function () {
        Route::get('/members', 'index')->name('members.index');
        Route::get('/members/{id}', 'show')->name('members.show');
        Route::put('/members/{id}', 'update')->name('members.update');
        Route::delete('/members/{id}', 'destroy')->name('members.destroy');
        Route::post('/members/{id}/restore', 'restore')->name('members.restore');
        Route::delete('/members/{id}/force-delete', 'forceDelete')->name('members.forceDelete');
    });

    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::patch('/subscription/{id}/update-status', [SubscriptionController::class, 'updateStatus'])->name('subscription.updateStatus');
    Route::get('/subscription/{id}/restore', [SubscriptionController::class, 'restore'])->name('subscription.restore');
    Route::get('/subscription/{id}/force-delete', [SubscriptionController::class, 'forceDelete'])->name('subscription.forceDelete');
    Route::get('/subscription/{id}/destroy', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');
    Route::delete('/subscription/{id}/destroy', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');
    Route::delete('/subscription/{id}/force-delete', [SubscriptionController::class, 'forceDelete'])->name('subscription.forceDelete');
    Route::patch('/subscription/{id}/update-detail', [SubscriptionController::class, 'updateDetail'])->name('subscription.updateDetail');


    // Settings Config
    Route::get('/settings-config', [SettingsController::class, 'index'])->name('settings.index');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
