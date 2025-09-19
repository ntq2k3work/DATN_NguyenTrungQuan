<?php

namespace App\ViewModels;

use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class HeaderViewModel extends BaseViewModel
{
    protected $cartCount = 0;
    protected $wishlistCount = 0;

    public function __construct()
    {
        parent::__construct();
        $this->loadCounts();
    }

    public function loadCounts(): void
    {
        $this->loadCartCount();
        $this->loadWishlistCount();
    }

    public function loadCartCount(): void
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $this->cartCount = $cart->items()->sum('quantity');
            } else {
                $this->cartCount = 0;
            }
        } else {
            $this->cartCount = 0;
        }
    }

    public function loadWishlistCount(): void
    {
        if (Auth::check()) {
            $this->wishlistCount = Wishlist::where('user_id', Auth::id())->count();
        } else {
            $this->wishlistCount = 0;
        }
    }

    public function updateCartCount(int $count): void
    {
        $this->cartCount = $count;
    }

    public function updateWishlistCount(int $count): void
    {
        $this->wishlistCount = $count;
    }

    public function resetCounts(): void
    {
        $this->cartCount = 0;
        $this->wishlistCount = 0;
    }

    public function getCartCount(): int
    {
        return $this->cartCount;
    }

    public function getWishlistCount(): int
    {
        return $this->wishlistCount;
    }

    public function hasCartItems(): bool
    {
        return $this->cartCount > 0;
    }

    public function hasWishlistItems(): bool
    {
        return $this->wishlistCount > 0;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'cartCount' => $this->cartCount,
            'wishlistCount' => $this->wishlistCount,
            'hasCartItems' => $this->hasCartItems(),
            'hasWishlistItems' => $this->hasWishlistItems(),
        ]);
    }
}
