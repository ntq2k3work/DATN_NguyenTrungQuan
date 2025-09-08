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
        $books = Book::all();
        $auth = Auth::user();
        if ($auth) {
            $wishlists = Wishlist::where('user_id', $auth->id)->get('book_id');

            $bookIdsInWishlist = $wishlists->pluck('book_id');

            $categoriesAuthors = Book::whereIn('id', $bookIdsInWishlist)
                ->select('category_id', 'author_id')
                ->distinct()
                ->get();

            $categoryIds = $categoriesAuthors->pluck('category_id')->filter()->unique();
            $authorIds = $categoriesAuthors->pluck('author_id')->filter()->unique();

            // Lấy sách liên quan (cùng tác giả hoặc danh mục)
            $books = Book::whereNotIn('id', $bookIdsInWishlist)
                ->where(function($query) use ($categoryIds, $authorIds) {
                    $query->whereIn('category_id', $categoryIds)
                        ->orWhereIn('author_id', $authorIds);
                })
                ->get();

            // Xử lý sắp xếp theo mức độ liên quan bằng code PHP
            $books = $books->map(function($book) use ($categoryIds, $authorIds) {
                $score = 0;
                if ($categoryIds->contains($book->category_id)) $score++;
                if ($authorIds->contains($book->author_id)) $score++;
                $book->score = $score; // thêm thuộc tính điểm
                return $book;
            });

            // Sắp xếp giảm dần theo score, rồi random trong cùng nhóm
            $book_recommendations = $books
                ->sortByDesc('score') // ưu tiên sách có điểm cao
                ->take(8)
                ->values();
        } else {
            $wishlists = collect();
            $book_recommendations = Book::inRandomOrder()->take(8)->get();
        }

        $best_sellers = Book::select('books.*', \DB::raw('(SELECT discounts.percent FROM discounts WHERE discounts.book_id = books.id LIMIT 1) as percent'), \DB::raw('(SELECT discounts.amount FROM discounts WHERE discounts.book_id = books.id LIMIT 1) as amount'))
        ->whereExists(function($query) {
            $query->select(\DB::raw(1))
                  ->from('discounts')
                  ->whereColumn('discounts.book_id', 'books.id');
        })
        ->with(['author'])
        ->take(6)
        ->get();

        foreach ($best_sellers as $book) {
            $price = $book->price;
            $percent = $book->percent ?? 0;
            $amount = $book->amount ?? 0;

            $book->final_price = $price - ($price * $percent / 100) - $amount;
            if ($book->final_price <= 0){
                $book->final_price = 0;
                $book->percent = 100;
            }
        }

        $best_sellers = $best_sellers->sortBy('final_price')->values();

        $new_publishers = Book::select()->with(['author','discount'])->orderBy('created_at','desc')->take(6)->get();
        foreach($new_publishers as $book)
        {
            $price = $book->price;
            $percent = $book->percent ?? 0;
            $amount = $book->amount ?? 0;
            $book->final_price = $this->numberFormat($price - ($price * $percent / 100) - $amount);

            if ($book->final_price <= 0){
                $book->final_price = 0;
                $book->percent = 100;
            }

            $book->price = $this->numberFormat($book->price);
        }
        
        // Calculate cart count
        $cartCount = $this->getCartCount();
        
        return view('app',compact(['book_recommendations','best_sellers','new_publishers','cartCount']));
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
