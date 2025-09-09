<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * Add book to wishlist
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|integer|exists:books,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Dữ liệu không hợp lệ'
            ], 400);
        }

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'error' => 'Vui lòng đăng nhập để thêm vào danh sách yêu thích'
            ], 401);
        }

        $bookId = $request->book_id;
        $userId = Auth::id();

        // Check if already in wishlist
        $existingWishlist = Wishlist::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();

        if ($existingWishlist) {
            return response()->json([
                'success' => false,
                'error' => 'Sản phẩm đã có trong danh sách yêu thích'
            ], 400);
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => $userId,
            'book_id' => $bookId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào danh sách yêu thích'
        ]);
    }

    /**
     * Remove book from wishlist
     */
    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|integer|exists:books,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Dữ liệu không hợp lệ'
            ], 400);
        }

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'error' => 'Vui lòng đăng nhập để thực hiện thao tác này'
            ], 401);
        }

        $bookId = $request->book_id;
        $userId = Auth::id();

        // Remove from wishlist
        $deleted = Wishlist::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();

        if ($deleted) {
            // Get updated wishlist count
            $wishlistCount = Wishlist::where('user_id', $userId)->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa khỏi danh sách yêu thích',
                'wishlist_count' => $wishlistCount
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Sản phẩm không có trong danh sách yêu thích'
            ], 400);
        }
    }

    /**
     * Toggle wishlist status (add if not exists, remove if exists)
     */
    public function toggle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|integer|exists:books,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Dữ liệu không hợp lệ'
            ], 400);
        }

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'error' => 'Vui lòng đăng nhập để thực hiện thao tác này'
            ], 401);
        }

        $bookId = $request->book_id;
        $userId = Auth::id();

        // Check if already in wishlist
        $existingWishlist = Wishlist::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();

        if ($existingWishlist) {
            // Remove from wishlist
            $existingWishlist->delete();
            
            // Get updated wishlist count
            $wishlistCount = Wishlist::where('user_id', $userId)->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa khỏi danh sách yêu thích',
                'in_wishlist' => false,
                'wishlist_count' => $wishlistCount
            ]);
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $userId,
                'book_id' => $bookId
            ]);
            
            // Get updated wishlist count
            $wishlistCount = Wishlist::where('user_id', $userId)->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích',
                'in_wishlist' => true,
                'wishlist_count' => $wishlistCount
            ]);
        }
    }

    /**
     * Check if book is in wishlist
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|integer|exists:books,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Dữ liệu không hợp lệ'
            ], 400);
        }

        if (!Auth::check()) {
            return response()->json([
                'success' => true,
                'in_wishlist' => false
            ]);
        }

        $bookId = $request->book_id;
        $userId = Auth::id();

        $inWishlist = Wishlist::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->exists();

        return response()->json([
            'success' => true,
            'in_wishlist' => $inWishlist
        ]);
    }

    /**
     * Get user's wishlist count
     */
    public function count()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => true,
                'wishlist_count' => 0
            ]);
        }

        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'wishlist_count' => $wishlistCount
        ]);
    }

    /**
     * Get user's wishlist
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem danh sách yêu thích');
        }

        $wishlistItems = Wishlist::with(['book.author', 'book.category', 'book.publisher', 'book.discount'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        

        // Calculate final prices for wishlist items
        foreach ($wishlistItems as $item) {
            $book = $item->book;
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
        }

        return view('pages.wishlist.index', compact('wishlistItems'));
    }
}
