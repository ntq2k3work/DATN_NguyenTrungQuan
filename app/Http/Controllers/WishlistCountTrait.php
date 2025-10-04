<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

trait WishlistCountTrait
{
    /**
     * Get wishlist count for current user
     */
    protected function getWishlistCount()
    {
        if (!Auth::check()) {
            return 0;
        }

        return Wishlist::where('user_id', Auth::id())->count();
    }

    protected function getWishlist()
    {
        if (!Auth::check()) {
            return 0;
        }

        return Wishlist::where('user_id', Auth::id())->get();
    }
}
