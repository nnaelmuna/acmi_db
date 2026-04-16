<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('post');
});

Route::get('/post', function () {
    return view('post');
})->name('post');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Rute khusus tamu
Route::middleware('guest')->group(function () {
    Route::get('/signup', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/signup', [RegisterController::class, 'register']);

    Route::get('/signin', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/signin', [LoginController::class, 'login']);
});

// Rute khusus admin
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});