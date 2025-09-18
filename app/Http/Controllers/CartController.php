<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\ViewModels\CartViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cartViewModel = new CartViewModel();
        $success = $cartViewModel->addToCart($request->book_id, $request->quantity);

        if ($success) {
            $cartCount = $cartViewModel->getCartCount();

            // Dispatch event to update header
            event('cart.count.updated', ['count' => $cartCount]);
            event('cart.updated');

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                'cart_count' => $cartCount
            ]);
        } else {
            $errors = $cartViewModel->getErrors();
            return response()->json([
                'success' => false,
                'error' => $errors['auth'] ?? $errors['book'] ?? $errors['quantity'] ?? 'Có lỗi xảy ra'
            ], 400);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        $cartViewModel = new CartViewModel();
        $success = $cartViewModel->removeFromCart($request->book_id);

        if ($success) {
            $cartCount = $cartViewModel->getCartCount();

            // Dispatch event to update header
            event('cart.count.updated', ['count' => $cartCount]);
            event('cart.updated');

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
                'cart_count' => $cartCount
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Không thể xóa sản phẩm khỏi giỏ hàng'
            ], 400);
        }
    }

    /**
     * Update item quantity in cart
     */
    public function update(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cartViewModel = new CartViewModel();
        $success = $cartViewModel->updateQuantity($request->book_id, $request->quantity);

        if ($success) {
            $cartCount = $cartViewModel->getCartCount();

            // Dispatch event to update header
            event('cart.count.updated', ['count' => $cartCount]);
            event('cart.updated');

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật số lượng sản phẩm',
                'cart_count' => $cartCount
            ]);
        } else {
            $errors = $cartViewModel->getErrors();
            return response()->json([
                'success' => false,
                'error' => $errors['quantity'] ?? 'Có lỗi xảy ra'
            ], 400);
        }
    }

    /**
     * Show cart page
     */
    public function show()
    {
        $cartViewModel = new CartViewModel();
        $cartCount = $cartViewModel->getCartCount();
        return view('pages.cart.index', compact('cartCount'));
    }

    /**
     * Get cart items
     */
    public function index()
    {
        $cartViewModel = new CartViewModel();

        return response()->json([
            'success' => true,
            'cart_items' => $cartViewModel->getCartItems(),
            'cart_count' => $cartViewModel->getCartCount()
        ]);
    }

    /**
     * Get cart count
     */
    public function count()
    {
        $cartViewModel = new CartViewModel();

        return response()->json([
            'success' => true,
            'cart_count' => $cartViewModel->getCartCount()
        ]);
    }

    /**
     * Add item to database cart
     */
    private function addToDatabaseCart($bookId, $quantity)
    {
        $user = Auth::user();

        // Get or create cart
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Check if item already exists
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('book_id', $bookId)
            ->first();

        if ($cartItem) {
            // Update quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Create new cart item
            $book = Book::select('books.*', 'discounts.percent', 'discounts.amount')
                ->leftJoin('discounts', 'books.id', '=', 'discounts.book_id')
                ->where('books.id', $bookId)
                ->first();

            // Calculate final price with discount
            $finalPrice = $this->calculateFinalPrice($book);

            CartItem::create([
                'cart_id' => $cart->id,
                'book_id' => $bookId,
                'quantity' => $quantity,
                'price' => $finalPrice
            ]);
        }
    }

    /**
     * Add item to session cart
     */
    private function addToSessionCart($bookId, $quantity)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity'] += $quantity;
        } else {
            $cart[$bookId] = [
                'book_id' => $bookId,
                'quantity' => $quantity
            ];
        }

        Session::put('cart', $cart);
    }

    /**
     * Remove item from database cart
     */
    private function removeFromDatabaseCart($bookId)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            CartItem::where('cart_id', $cart->id)
                ->where('book_id', $bookId)
                ->delete();
        }
    }

    /**
     * Remove item from session cart
     */
    private function removeFromSessionCart($bookId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$bookId]);
        Session::put('cart', $cart);
    }

    /**
     * Update item in database cart
     */
    private function updateDatabaseCart($bookId, $quantity)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('book_id', $bookId)
                ->first();

            if ($cartItem) {
                $cartItem->quantity = $quantity;
                $cartItem->save();
            }
        }
    }

    /**
     * Update item in session cart
     */
    private function updateSessionCart($bookId, $quantity)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }
    }

    /**
     * Get total cart count
     */
    public function getCartCount()
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

    /**
     * Calculate final price with discount
     */
    private function calculateFinalPrice($book)
    {
        $price = $book->price;
        $percent = $book->percent ?? 0;
        $amount = $book->amount ?? 0;

        $finalPrice = $price - ($price * $percent / 100) - $amount;

        // Ensure price doesn't go below 0
        if ($finalPrice <= 0) {
            $finalPrice = 0;
        }

        return $finalPrice;
    }
}
