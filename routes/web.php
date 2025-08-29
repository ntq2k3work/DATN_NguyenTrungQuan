<?php

use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register',[AuthController::class, 'register'])->name('register');
    Route::get('/login',[AuthController::class, 'login'])->name('login');
    Route::post('/register',[AuthController::class, 'handleRegister'])->name('handleRegister');
    Route::post('/login',[AuthController::class, 'handleLogin'])->name('handleLogin');

    // Password Reset Routes
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])
        ->name('password.reset')
        ->middleware('check.password.reset.token');
    Route::post('/reset-password', [AuthController::class, 'resetPasswordUpdate'])->name('password.update');
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
    Route::get('/profile',[AuthController::class, 'profile'])->name('profile');
    Route::get('/profile/edit',[AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update',[AuthController::class, 'updateProfile'])->name('profile.update');
});

// Email verification route
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify')
    ->middleware('signed');

