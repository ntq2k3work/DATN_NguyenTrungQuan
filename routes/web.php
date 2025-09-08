<?php

use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Helper function to get cart count
function getCartCount() {
    $cartCount = 0;
    
    if (Auth::check()) {
        $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $cartCount = $cart->items()->sum('quantity');
        }
    } else {
        $cart = session()->get('cart', []);
        foreach ($cart as $item) {
            $cartCount += $item['quantity'];
        }
    }
    
    return $cartCount;
}

// Home Route - Keep using HomeController for now as it handles Livewire components
Route::get('/',[HomeController::class, 'index'])->name('home');

// Livewire Routes - Product Detail
Route::get('/product/{slug}', function($slug) {
    return view('pages.product.detail', ['slug' => $slug, 'cartCount' => getCartCount()]);
})->name('product.show');

// Livewire Routes - Cart
Route::get('/cart', function() {
    return view('pages.cart.index', ['cartCount' => getCartCount()]);
})->name('cart.show');


// Livewire Routes - Checkout
Route::get('/checkout', function() {
    return view('pages.checkout', ['cartCount' => getCartCount()]);
})->name('checkout');

// Order Success Route - Keep using OrderController for now
Route::get('/orders/success/{orderNumber}', [OrderController::class, 'success'])->name('orders.success');

// Livewire Routes - User Orders (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/orders/track', function() {
        return view('pages.order-tracking', ['cartCount' => getCartCount()]);
    })->name('orders.track');
    
    Route::get('/my-orders', function() {
        return view('pages.my-orders', ['cartCount' => getCartCount()]);
    })->name('orders.index');
    
    Route::get('/orders/{orderNumber}', function($orderNumber) {
        return view('pages.order-details', ['orderNumber' => $orderNumber, 'cartCount' => getCartCount()]);
    })->name('orders.show');
});

// Authentication Routes - Keep using AuthController for now
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

// Protected Routes - Keep using AuthController for now
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

// Livewire Routes - Categories
Route::get('/categories', function() {
    return view('pages.categories.index', ['cartCount' => getCartCount()]);
})->name('categories.index');

Route::get('/categories/best-sellers', function() {
    return view('pages.categories.best_sellers', ['cartCount' => getCartCount()]);
})->name('categories.best-sellers');

Route::get('/categories/new-releases', function() {
    return view('pages.categories.new_releases', ['cartCount' => getCartCount()]);
})->name('categories.new-releases');

Route::get('/categories/recommendations', function() {
    return view('pages.categories.recommendations', ['cartCount' => getCartCount()]);
})->name('categories.recommendations');

Route::get('/categories/top-selling', function() {
    return view('pages.categories.top_selling', ['cartCount' => getCartCount()]);
})->name('categories.top-selling');

Route::get('/categories/{slug}', function($slug) {
    return view('pages.categories.show', ['slug' => $slug, 'cartCount' => getCartCount()]);
})->name('categories.show');

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

