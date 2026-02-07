<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::post('/login', function (Request $request) {
    // Validate the input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Check if the user exists and password is correct
    if (!Auth::attempt($request->only('email', 'password'))) {
        throw ValidationException::withMessages([
            'email' => ['The credentials you provided are incorrect.'],
        ]);
    }

    // Success! Laravel starts a session automatically
    return response()->json(['message' => 'Logged in successfully']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
