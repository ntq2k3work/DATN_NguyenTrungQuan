<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CartManager extends Component
{
    public $cartItems = [];
    public $cartCount = 0;
    public $subtotal = 0;
    public $total = 0;
    public $shippingFee = 0;

    protected $listeners = ['cartUpdated' => 'loadCartItems'];

    public function mount()
    {
        $this->loadCartItems();
    }

    public function loadCartItems()
    {
        $this->cartItems = collect();

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $this->cartItems = CartItem::with('book')->where('cart_id', $cart->id)->get();
            }
        } else {
            $sessionCart = Session::get('cart', []);
            foreach ($sessionCart as $item) {
                $book = Book::find($item['book_id']);
                if ($book) {
                    $this->cartItems->push((object) [
                        'book' => $book,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'] ?? $book->price,
                        'book_id' => $book->id
                    ]);
                }
            }
        }

        $this->calculateTotals();
        $this->updateCartCount();
    }

    public function addToCart($bookId, $quantity = 1)
    {
        $book = Book::find($bookId);

        if (!$book) {
            $this->dispatch('showError', 'Sách không tồn tại');
            return;
        }

        // Check stock availability
        if ($book->quantity < $quantity) {
            $this->dispatch('showError', 'Không đủ hàng trong kho');
            return;
        }

        if (Auth::check()) {
            $this->addToDatabaseCart($bookId, $quantity);
        } else {
            $this->addToSessionCart($bookId, $quantity);
        }

        $this->loadCartItems();
        $this->dispatch('cartUpdated');
        $this->dispatch('showSuccess', 'Đã thêm sản phẩm vào giỏ hàng');
    }

    public function removeFromCart($bookId)
    {
        if (Auth::check()) {
            $this->removeFromDatabaseCart($bookId);
        } else {
            $this->removeFromSessionCart($bookId);
        }

        $this->loadCartItems();
        $this->dispatch('cartUpdated');
        $this->dispatch('showSuccess', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }

    public function updateQuantity($bookId, $quantity)
    {
        if ($quantity < 1) {
            $this->removeFromCart($bookId);
            return;
        }

        $book = Book::find($bookId);

        if (!$book) {
            $this->dispatch('showError', 'Sách không tồn tại');
            return;
        }

        if ($book->quantity < $quantity) {
            $this->dispatch('showError', 'Không đủ hàng trong kho');
            return;
        }

        if (Auth::check()) {
            $this->updateDatabaseCart($bookId, $quantity);
        } else {
            $this->updateSessionCart($bookId, $quantity);
        }

        $this->loadCartItems();
        $this->dispatch('cartUpdated');
        $this->dispatch('showSuccess', 'Đã cập nhật số lượng sản phẩm');
    }

    public function clearCart()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                CartItem::where('cart_id', $cart->id)->delete();
            }
        } else {
            Session::forget('cart');
        }

        $this->loadCartItems();
        $this->dispatch('cartUpdated');
        $this->dispatch('showSuccess', 'Đã xóa tất cả sản phẩm khỏi giỏ hàng');
    }

    private function addToDatabaseCart($bookId, $quantity)
    {
        $user = Auth::user();
        $book = Book::with('discount')->find($bookId);
        
        if (!$book) {
            return;
        }
        
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
            // Calculate price with discount
            $price = $book->price;
            if ($this->hasDiscount($book)) {
                if ($book->discount->amount != null && $book->discount->amount > 0) {
                    // Discount theo amount
                    $discountPrice = $price - $book->discount->amount;
                } elseif ($book->discount->percent != null && $book->discount->percent > 0) {
                    // Discount theo percent
                    $discountPrice = $price - ($price * $book->discount->percent / 100);
                } else {
                    $discountPrice = $price;
                }
                
                if ($discountPrice > 0) {
                    $price = $discountPrice;
                }
            }
            
            // Create new cart item with discounted price
            CartItem::create([
                'cart_id' => $cart->id,
                'book_id' => $bookId,
                'quantity' => $quantity,
                'price' => $price
            ]);
        }
    }

    private function addToSessionCart($bookId, $quantity)
    {
        $cart = Session::get('cart', []);
        $book = Book::with('discount')->find($bookId);
        
        if (!$book) {
            return;
        }
        
        // Calculate price with discount
        $price = $book->price;
        if ($this->hasDiscount($book)) {
            if ($book->discount->amount != null && $book->discount->amount > 0) {
                // Discount theo amount
                $discountPrice = $price - $book->discount->amount;
            } elseif ($book->discount->percent != null && $book->discount->percent > 0) {
                // Discount theo percent
                $discountPrice = $price - ($price * $book->discount->percent / 100);
            } else {
                $discountPrice = $price;
            }
            
            if ($discountPrice > 0) {
                $price = $discountPrice;
            }
        }
        
        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity'] += $quantity;
        } else {
            $cart[$bookId] = [
                'book_id' => $bookId,
                'quantity' => $quantity,
                'price' => $price
            ];
        }
        
        Session::put('cart', $cart);
    }

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

    private function removeFromSessionCart($bookId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$bookId]);
        Session::put('cart', $cart);
    }

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

    private function updateSessionCart($bookId, $quantity)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }
    }

    private function calculateTotals()
    {
        $this->subtotal = $this->cartItems->sum(function ($item) {
            // Use stored price if available (for discounted items), otherwise use book price
            $price = isset($item->price) ? $item->price : $item->book->price;
            return $price * $item->quantity;
        });

        $this->shippingFee = $this->subtotal >= 500000 ? 0 : 30000;
        $this->total = $this->subtotal + $this->shippingFee;
    }

    private function updateCartCount()
    {
        $this->cartCount = $this->cartItems->sum('quantity');
    }

    public function getCartCount()
    {
        return $this->cartCount;
    }

    private function hasDiscount($book)
    {
        if (!$book->discount) {
            return false;
        }
        
        // Kiểm tra discount theo amount hoặc percent
        $hasValidDiscount = false;
        if ($book->discount->amount != null && $book->discount->amount > 0) {
            $hasValidDiscount = true;
        } elseif ($book->discount->percent != null && $book->discount->percent > 0) {
            $hasValidDiscount = true;
        }
        
        if (!$hasValidDiscount) {
            return false;
        }
        
        // Kiểm tra thời gian hiệu lực
        $now = now();
        $startDate = $book->discount->start_date;
        $endDate = $book->discount->end_date;
        
        // Nếu có thời gian bắt đầu và kết thúc
        if ($startDate && $endDate) {
            return $now->between($startDate, $endDate);
        }
        
        // Nếu không có thời gian, coi như luôn có hiệu lực
        return true;
    }

    public function render()
    {
        return view('livewire.cart-manager');
    }
}
