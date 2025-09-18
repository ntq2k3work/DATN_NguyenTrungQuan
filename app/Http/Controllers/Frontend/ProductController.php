<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\CartItem;
use App\Http\Controllers\WishlistCountTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    use WishlistCountTrait;
    public function show($slug)
    {
        $book = Book::with(['author', 'category', 'publisher', 'discount'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Tính giá sau giảm giá
        $price = $book->price;
        $percent = $book->discount?->percent ?? 0;
        $amount = $book->discount?->amount ?? 0;

        $book->final_price = $price - ($price * $percent / 100) - $amount;
        if ($book->final_price <= 0) {
            $book->final_price = 0;
            $book->discount_percent = 100;
        } else {
            $book->discount_percent = $percent;
        }

        // Kiểm tra sách có trong wishlist không
        $inWishlist = false;
        if (Auth::check()) {
            $inWishlist = Wishlist::where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->exists();
        }

        // Lấy sách liên quan (cùng tác giả hoặc danh mục)
        $relatedBooks = Book::with(['author', 'discount'])
            ->where('id', '!=', $book->id)
            ->where(function($query) use ($book) {
                $query->where('category_id', $book->category_id)
                    ->orWhere('author_id', $book->author_id);
            })
            ->take(4)
            ->get();

        // Tính giá cho sách liên quan
        foreach ($relatedBooks as $relatedBook) {
            $relatedPrice = $relatedBook->price;
            $relatedPercent = $relatedBook->discount?->percent ?? 0;
            $relatedAmount = $relatedBook->discount?->amount ?? 0;

            $relatedBook->final_price = $relatedPrice - ($relatedPrice * $relatedPercent / 100) - $relatedAmount;
            if ($relatedBook->final_price <= 0) {
                $relatedBook->final_price = 0;
                $relatedBook->discount_percent = 100;
            } else {
                $relatedBook->discount_percent = $relatedPercent;
            }
        }

        // Calculate cart count
        $cartCount = $this->getCartCount();

        // Calculate wishlist count
        $wishlistCount = $this->getWishlistCount();

        return view('pages.product.detail', compact('book', 'inWishlist', 'relatedBooks', 'cartCount', 'wishlistCount'));
    }

    public function numberFormat($price)
    {
        return number_format($price, 0, ',', '.');
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
