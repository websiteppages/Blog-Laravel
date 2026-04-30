<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Auth;

// Login / Logout
Route::get('/login', function () {
    return view('auth.login');
})->middleware(['guest'])->name('login');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


// Register
if (Features::enabled(Features::registration())) {
    Route::get('/register', function () {
        return view('auth.register');
    })->middleware(['guest'])->name('register');
}


// Password Reset
if (Features::enabled(Features::resetPasswords())) {
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->middleware(['guest'])->name('password.request');

    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->middleware(['guest'])->name('password.reset');
}


// Email Verification
if (Features::enabled(Features::emailVerification())) {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware(['auth'])->name('verification.notice');
}
