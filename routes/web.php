<?php

use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class, 'index'])->name('home');

// Product Routes
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');

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
    Route::get('/password/change',[AuthController::class, 'changePassword'])->name('password.change');
    Route::put('/password/change',[AuthController::class, 'updatePassword'])->name('password.change');
});

// Email verification route
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify')
    ->middleware('signed');

// Category Routes
Route::get('/categories/best-sellers', [App\Http\Controllers\Frontend\CategoryController::class, 'bestSellers'])->name('categories.best-sellers');
Route::get('/categories/new-releases', [App\Http\Controllers\Frontend\CategoryController::class, 'newReleases'])->name('categories.new-releases');
Route::get('/categories/recommendations', [App\Http\Controllers\Frontend\CategoryController::class, 'recommendations'])->name('categories.recommendations');
Route::get('/categories/top-selling', [App\Http\Controllers\Frontend\CategoryController::class, 'topSelling'])->name('categories.top-selling');
Route::get('/categories/{slug}', [App\Http\Controllers\Frontend\CategoryController::class, 'showBySlug'])->name('categories.show');

// API Routes for AJAX
Route::get('/api/check-discount', [App\Http\Controllers\Frontend\CheckoutController::class, 'checkDiscount'])
    ->name('api.check-discount')
    ->middleware('web');

Route::get('/api/categories/best-sellers/filter', [App\Http\Controllers\Frontend\CategoryController::class, 'filterBestSellers'])
    ->name('api.categories.best-sellers.filter')
    ->middleware('web');

Route::get('/api/categories/new-releases/filter', [App\Http\Controllers\Frontend\CategoryController::class, 'filterNewReleases'])
    ->name('api.categories.new-releases.filter')
    ->middleware('web');

Route::get('/api/categories/recommendations/filter', [App\Http\Controllers\Frontend\CategoryController::class, 'filterRecommendations'])
    ->name('api.categories.recommendations.filter')
    ->middleware('web');

Route::get('/api/categories/{slug}/filter', [App\Http\Controllers\Frontend\CategoryController::class, 'filterBooks'])
    ->name('api.categories.filter')
    ->middleware('web');

// Admin Routes - Commented out to use Filament Admin Panel
// Route::prefix('admin')->name('admin.')->group(function () {
//     // Guest admin routes (login)
//     Route::middleware('guest')->group(function () {
//         Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
//         Route::post('/login', [AdminAuthController::class, 'login'])->name('login');
//     });
    
//     // Protected admin routes
//     Route::middleware(['auth', 'admin'])->group(function () {
//         Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
//         Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
//     });
// });

