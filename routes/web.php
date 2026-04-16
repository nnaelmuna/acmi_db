<?php

use Illuminate\Support\Facades\Route;

// Halaman utama (Landing Page)
Route::get('/', function () {
    return view('post');
});


Route::get('/signin', function () {
    return view('auth.login'); 
})->name('login');


Route::get('/signup', function () {
    return view('auth.register'); 
})->name('register');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/post', function () {
    return view('post');
})->name('post');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');