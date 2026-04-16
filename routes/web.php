<?php

use Illuminate\Support\Facades\Route;

// Halaman utama (Landing Page)
Route::get('/', function () {
    return view('post');
});

// sign in
Route::get('/signin', function () {
    return view('auth.signin'); 
})->name('login');

// Route untuk halaman sign up
Route::get('/signup', function () {
    return view('auth.signup'); 
})->name('register');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/post', function () {
    return view('post');
})->name('post');