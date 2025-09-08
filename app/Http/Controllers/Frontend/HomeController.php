<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Book;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function numberFormat($price)
    {
        return number_format($price,0,',','.');
    }
    
    public function index()
    {
        // Calculate cart count for header
        $cartCount = $this->getCartCount();
        
        // Return view with only cart count - Livewire components will handle their own data
        return view('app', compact('cartCount'));
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
