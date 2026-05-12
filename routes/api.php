<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicContentController;

// Endpoint Publik untuk Landing Page ACMI (Tanpa Token Sanctum)
Route::prefix('public')->group(function () {
    Route::get('/articles', [PublicContentController::class, 'getArticles']);
    Route::get('/articles/{slug}', [PublicContentController::class, 'getArticleDetail']);
    Route::get('/faqs', [PublicContentController::class, 'getFaqs']);
    Route::get('/services', [PublicContentController::class, 'getServices']);
    Route::get('/gallery', [PublicContentController::class, 'getGallery']);
    Route::get('/partners', [PublicContentController::class, 'getPartners']);
});