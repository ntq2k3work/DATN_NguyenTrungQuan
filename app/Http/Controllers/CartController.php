<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
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

        $bookId = $request->book_id;
        $quantity = $request->quantity;
        $book = Book::find($bookId);

        if (!$book) {
            return response()->json(['error' => 'Sách không tồn tại'], 404);
        }

        // Check stock availability
        if ($book->quantity < $quantity) {
            return response()->json(['error' => 'Không đủ hàng trong kho'], 400);
        }

        if (Auth::check()) {
            // User is logged in - use database cart
            $this->addToDatabaseCart($bookId, $quantity);
        } else {
            // User is not logged in - use session cart
            $this->addToSessionCart($bookId, $quantity);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng',
            'cart_count' => $this->getCartCount()
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        $bookId = $request->book_id;

        if (Auth::check()) {
            $this->removeFromDatabaseCart($bookId);
        } else {
            $this->removeFromSessionCart($bookId);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
            'cart_count' => $this->getCartCount()
        ]);
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

        $bookId = $request->book_id;
        $quantity = $request->quantity;
        $book = Book::find($bookId);

        if (!$book) {
            return response()->json(['error' => 'Sách không tồn tại'], 404);
        }

        if ($book->quantity < $quantity) {
            return response()->json(['error' => 'Không đủ hàng trong kho'], 400);
        }

        if (Auth::check()) {
            $this->updateDatabaseCart($bookId, $quantity);
        } else {
            $this->updateSessionCart($bookId, $quantity);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật số lượng sản phẩm',
            'cart_count' => $this->getCartCount()
        ]);
    }

    /**
     * Show cart page
     */
    public function show()
    {
        $cartCount = $this->getCartCount();
        return view('pages.cart.index', compact('cartCount'));
    }

    /**
     * Get cart items
     */
    public function index()
    {
        $cartItems = collect();

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cartItems = CartItem::with('book')->where('cart_id', $cart->id)->get();
            }
        } else {
            $sessionCart = Session::get('cart', []);
            foreach ($sessionCart as $item) {
                $book = Book::find($item['book_id']);
                if ($book) {
                    $cartItems->push((object) [
                        'book' => $book,
                        'quantity' => $item['quantity'],
                        'book_id' => $book->id
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'cart_items' => $cartItems,
            'cart_count' => $this->getCartCount()
        ]);
    }

    /**
     * Get cart count
     */
    public function count()
    {
        return response()->json([
            'success' => true,
            'cart_count' => $this->getCartCount()
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
            $book = Book::find($bookId);
            CartItem::create([
                'cart_id' => $cart->id,
                'book_id' => $bookId,
                'quantity' => $quantity,
                'price' => $book->price
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
