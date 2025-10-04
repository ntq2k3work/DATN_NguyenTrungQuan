<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Wishlist;
use App\ViewModels\WishlistViewModel;
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

        $wishlistViewModel = new WishlistViewModel();
        $success = $wishlistViewModel->addToWishlist($request->book_id);

        if ($success) {
            $wishlistCount = $wishlistViewModel->getWishlistCount();

            // Dispatch event to update header
            event('wishlist.count.updated', ['count' => $wishlistCount]);
            event('wishlist.updated');

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích',
                'wishlist_count' => $wishlistCount
            ]);
        } else {
            $errors = $wishlistViewModel->getErrors();
            return response()->json([
                'success' => false,
                'error' => $errors['auth'] ?? $errors['book'] ?? $errors['exists'] ?? 'Có lỗi xảy ra'
            ], 400);
        }
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

        $wishlistViewModel = new WishlistViewModel();
        $success = $wishlistViewModel->removeFromWishlist($request->book_id);

        if ($success) {
            $wishlistCount = $wishlistViewModel->getWishlistCount();

            // Dispatch event to update header
            event('wishlist.count.updated', ['count' => $wishlistCount]);
            event('wishlist.updated');

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa khỏi danh sách yêu thích',
                'wishlist_count' => $wishlistCount
            ]);
        } else {
            $errors = $wishlistViewModel->getErrors();
            return response()->json([
                'success' => false,
                'error' => $errors['auth'] ?? $errors['not_found'] ?? 'Có lỗi xảy ra'
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

        $wishlistViewModel = new WishlistViewModel();
        $success = $wishlistViewModel->toggleWishlist($request->book_id);

        if ($success) {
            $wishlistCount = $wishlistViewModel->getWishlistCount();
            $isInWishlist = $wishlistViewModel->isInWishlist($request->book_id);

            // Dispatch event to update header
            event('wishlist.count.updated', ['count' => $wishlistCount]);
            event('wishlist.updated');

            return response()->json([
                'success' => true,
                'message' => $isInWishlist ? 'Đã thêm vào danh sách yêu thích' : 'Đã xóa khỏi danh sách yêu thích',
                'in_wishlist' => $isInWishlist,
                'wishlist_count' => $wishlistCount
            ]);
        } else {
            $errors = $wishlistViewModel->getErrors();
            return response()->json([
                'success' => false,
                'error' => $errors['auth'] ?? $errors['book'] ?? 'Có lỗi xảy ra'
            ], 400);
        }
    }

    /**
     * Check if book is in wishlist
     */
    public function check(Request $request)
    {
        // Check if checking multiple books
        if ($request->has('book_ids')) {
            $bookIds = $request->input('book_ids');

            if (!is_array($bookIds) || empty($bookIds)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Dữ liệu không hợp lệ'
                ], 400);
            }

            $wishlistViewModel = new WishlistViewModel();
            $wishlistStatus = $wishlistViewModel->getWishlistStatus($bookIds);

            return response()->json([
                'success' => true,
                'wishlist_status' => $wishlistStatus
            ]);
        }

        // Single book check (backward compatibility)
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|integer|exists:books,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Dữ liệu không hợp lệ'
            ], 400);
        }

        $wishlistViewModel = new WishlistViewModel();
        $inWishlist = $wishlistViewModel->isInWishlist($request->book_id);

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
        $wishlistViewModel = new WishlistViewModel();

        return response()->json([
            'success' => true,
            'wishlist_count' => $wishlistViewModel->getWishlistCount()
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

        $wishlistViewModel = new WishlistViewModel();
        $wishlistItems = $wishlistViewModel->getWishlistItems();
        $cartCount = (new CartController())->getCartCount();

        return view('pages.wishlist.index', compact('wishlistItems', 'cartCount'));
    }
}
