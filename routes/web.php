<?php

use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class, 'index'])->name('home');
Route::get('/register',[AuthController::class, 'register'])->name('register');
Route::get('/login',[AuthController::class, 'login'])->name('login');
Route::post('/register',[AuthController::class, 'handleRegister'])->name('handleRegister');

