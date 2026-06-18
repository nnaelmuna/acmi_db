<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicContentController;
use App\Http\Controllers\Api\InstagramController;
use App\Http\Controllers\Api\InboundApiController;
use App\Http\Controllers\Api\InboundController;
use App\Http\Controllers\Api\MemberRequestController;

Route::prefix('public')->group(function () {
    Route::get('/articles', [PublicContentController::class, 'getArticles']);
    Route::get('/articles/{locale}/{slug}', [PublicContentController::class, 'getArticleDetail']);
    Route::get('/faqs', [PublicContentController::class, 'getFaqs']);
    Route::get('/services', [PublicContentController::class, 'getServices']);
    Route::get('/testimonials', [PublicContentController::class, 'getTestimonials']);
    Route::get('/gallery', [PublicContentController::class, 'getGallery']);
    Route::get('/partners', [PublicContentController::class, 'getPartners']);
    Route::get('/categories', [PublicContentController::class, 'getCategories']);
    Route::get('/public/instagram', [PublicContentController::class, 'getInstagramPosts']);
    Route::get('/instagram', [InstagramController::class, 'index']);
    Route::post('/inbound', [InboundApiController::class, 'store']);
    Route::post('/public/member-request', [MemberRequestController::class, 'store']);
});
