<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BestSellers extends Component
{
    public $bestSellers;
    public $showSuccessToast = false;
    public $showErrorToast = false;
    public $toastMessage = '';

    public function mount()
    {
        $this->loadBestSellers();
    }

    public function loadBestSellers()
    {
        $this->bestSellers = Book::with(['author', 'discounts'])
            ->whereHas('discounts', function($query) {
                $query->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            })
            ->take(6)
            ->get();

        foreach ($this->bestSellers as $book) {
            $discount = $book->discounts()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();
                
            $price = $book->price;
            $percent = $discount?->percent ?? 0;
            $amount = $discount?->amount ?? 0;

            $book->final_price = $price - ($price * $percent / 100) - $amount;
            $book->percent = $percent;
            $book->amount = $amount;
            
            if ($book->final_price <= 0){
                $book->final_price = 0;
                $book->percent = 100;
            }
        }

        $this->bestSellers = $this->bestSellers->sortBy('final_price')->values();
    }

    public function addToCart($bookId)
    {
        $book = Book::find($bookId);
        
        if (!$book) {
            $this->dispatch('showError', 'Sách không tồn tại');
            return;
        }

        // Check stock availability
        if ($book->quantity < 1) {
            $this->dispatch('showError', 'Sách đã hết hàng');
            return;
        }

        if (Auth::check()) {
            $this->addToDatabaseCart($bookId, 1);
        } else {
            $this->addToSessionCart($bookId, 1);
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('cartCountUpdated', $this->getCartCount());
        $this->showSuccessToast = true;
        $this->toastMessage = 'Đã thêm sản phẩm vào giỏ hàng';
        
        // Auto hide toast after 3 seconds
        $this->dispatch('hideToast');
    }

    public function showError($message)
    {
        $this->showErrorToast = true;
        $this->toastMessage = $message;
        
        // Auto hide toast after 3 seconds
        $this->dispatch('hideToast');
    }

    public function hideToast()
    {
        $this->showSuccessToast = false;
        $this->showErrorToast = false;
        $this->toastMessage = '';
    }

    private function hasDiscount($book)
    {
        if (!$book->discount) {
            return false;
        }
        
        // Kiểm tra discount theo amount hoặc percent
        $hasValidDiscount = false;
        if ($book->discount->amount != null) {
            $hasValidDiscount = $book->discount->amount > 0;
        } else {
            $hasValidDiscount = $book->discount->percent > 0;
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

    private function addToDatabaseCart($bookId, $quantity)
    {
        $user = Auth::user();
        $book = Book::find($bookId);
        
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
            // Create new cart item - use discounted price if available
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
        $book = Book::find($bookId);
        
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

    public function render()
    {
        return view('livewire.best-sellers');
    }
}
