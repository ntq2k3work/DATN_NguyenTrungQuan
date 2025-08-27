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
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
    Route::get('/profile',[AuthController::class, 'profile'])->name('profile');
});

// Email verification route
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify')
    ->middleware('signed');

