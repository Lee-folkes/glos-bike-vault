<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BikeController;

// Show the landing page
Route::get('/', function () {
    return view('welcome');
});

// Login and Register views are handled by Fortify (see FortifyServiceProvider)

// Show the dashboard (protected route)
Route::get('/dashboard', function () {
    $bikes = auth()->user()->bikes()->latest()->get();
    return view('dashboard', compact('bikes'));
})->middleware('auth')->name('dashboard');

// Show the user profile (protected route)
Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

// Logout route
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

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
    return view('auth.two-factor-setup');
})->middleware('auth');

// Store new bike (protected route)
Route::post('/bikes', [BikeController::class, 'store'])
    ->middleware('auth')
    ->name('bikes.store');

// Update existing bike (protected route)
Route::put('/bikes/{bike}', [BikeController::class, 'update'])
    ->middleware('auth')
    ->name('bikes.update');


