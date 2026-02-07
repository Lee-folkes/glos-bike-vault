<?php

use Illuminate\Support\Facades\Route;

// Show the landing page
Route::get('/', function () {
    return view('welcome');
});

// Show the login page
Route::get('/login', function () {
    return view('login');
});

//show the registration page
Route::get('/register', function () {
    return view('register');
});

// Show the dashboard (protected route)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');



