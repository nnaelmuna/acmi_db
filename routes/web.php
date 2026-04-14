<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.signup');
});

// sign in
Route::get('/signin', function () {
    return view('auth.signup'); 
});

// route untuk halaman sign in
Route::get('/signin', function () {
    return view('auth.signin'); 
})->name('login');

// route untuk halaman sign up
Route::get('/signup', function () {
    return view('auth.signup'); 
})->name('register');