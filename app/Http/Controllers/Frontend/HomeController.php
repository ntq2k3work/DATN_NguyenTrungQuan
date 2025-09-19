<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\ViewModels\HomeViewModel;
use App\Http\Controllers\WishlistCountTrait;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use WishlistCountTrait;

    public function numberFormat($price)
    {
        return number_format($price,0,',','.');
    }
    public function index()
    {
        $homeViewModel = new HomeViewModel();

        // Calculate cart count
        $cartCount = $this->getCartCount();

        // Calculate wishlist count
        $wishlistCount = $this->getWishlistCount();
        $wishlist = $this->getWishlist();

        return view('app', [
            'homeViewModel' => $homeViewModel,
            'book_recommendations' => $homeViewModel->getRecommendedBooks(),
            'best_sellers' => $homeViewModel->getBestSellers(),
            'new_publishers' => $homeViewModel->getNewReleases(),
            'cartCount' => $cartCount,
            'wishlistCount' => $wishlistCount,
            'wishlist' => $wishlist,
        ]);
    }

    /**
     * Get total cart count
     */
    private function getCartCount()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                return CartItem::where('cart_id', $cart->id)->sum('quantity');
            }
        } else {
            $sessionCart = Session::get('cart', []);
            return array_sum(array_column($sessionCart, 'quantity'));
        }

        return 0;
    }
}
