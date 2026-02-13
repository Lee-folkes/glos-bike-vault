<?php

use Illuminate\Support\Facades\Route;

// Show the landing page
Route::get('/', function () {
    return view('welcome');
});

// Show the login page
Route::get('/login', function () {
    return view('login');
})->name('login');

//show the registration page
Route::get('/register', function () {
    return view('register');
})->name('register');

// Show the dashboard (protected route)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// Logout route
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth');

// Two-factor setup completion
Route::post('/complete-two-factor-setup', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login')->with('status', 'Two-factor authentication has been enabled. Please login with your credentials.');
})->middleware('auth');

// Show 2FA setup page after registration
Route::get('/two-factor-setup', function () {
    if (!auth()->check() || !auth()->user()->two_factor_secret) {
        return redirect('/login');
    }
    return view('two-factor-setup');
})->middleware('auth');



