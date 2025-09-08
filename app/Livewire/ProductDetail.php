<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ProductDetail extends Component
{
    public $book;
    public $relatedBooks = [];
    public $inWishlist = false;
    public $quantity = 1;
    public $cartCount = 0;
    public $isLoading = false;
    public $showSuccessToast = false;
    public $showErrorToast = false;
    public $toastMessage = '';

    public function hasDiscount()
    {
        if (!$this->book->discount) {
            return false;
        }
        
        // Kiểm tra discount theo amount hoặc percent
        $hasValidDiscount = false;
        if ($this->book->discount->amount != null && $this->book->discount->amount > 0) {
            $hasValidDiscount = true;
        } elseif ($this->book->discount->percent != null && $this->book->discount->percent > 0) {
            $hasValidDiscount = true;
        }
        
        if (!$hasValidDiscount) {
            return false;
        }
        
        // Kiểm tra thời gian hiệu lực
        $now = now();
        $startDate = $this->book->discount->start_date;
        $endDate = $this->book->discount->end_date;
        
        // Nếu có thời gian bắt đầu và kết thúc
        if ($startDate && $endDate) {
            return $now->between($startDate, $endDate);
        }
        
        // Nếu không có thời gian, coi như luôn có hiệu lực
        return true;
    }

    public function mount($slug)
    {
        // Load book with minimal relations first
        $this->book = Book::with(['author', 'category', 'publisher', 'discount'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Check wishlist status
        $this->checkWishlistStatus();

        // Load related books (defer this to avoid blocking)
        $this->loadRelatedBooks();

        $this->updateCartCount();
    }

    public function hydrate()
    {
        // Ensure book data is fresh but discount info is preserved
        if ($this->book && $this->book->slug) {
            $this->book = Book::with(['author', 'category', 'publisher', 'discount'])
                ->where('slug', $this->book->slug)
                ->firstOrFail();
        }
    }

    private function checkWishlistStatus()
    {
        if (Auth::check()) {
            $this->inWishlist = Wishlist::where('user_id', Auth::id())
                ->where('book_id', $this->book->id)
                ->exists();
        }
    }

    private function loadRelatedBooks()
    {
        $this->relatedBooks = Book::with(['author', 'discount'])
            ->where('id', '!=', $this->book->id)
            ->where(function($query) {
                $query->where('category_id', $this->book->category_id)
                    ->orWhere('author_id', $this->book->author_id);
            })
            ->take(4)
            ->get();

        // Calculate prices for related books
        foreach ($this->relatedBooks as $relatedBook) {
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
    }

    public function addToCart()
    {
        if ($this->quantity < 1) {
            $this->showError('Số lượng phải lớn hơn 0');
            return;
        }

        if ($this->book->quantity < $this->quantity) {
            $this->showError('Không đủ hàng trong kho');
            return;
        }

        $this->isLoading = true;

        try {
            if (Auth::check()) {
                $this->addToDatabaseCart($this->book->id, $this->quantity);
            } else {
                $this->addToSessionCart($this->book->id, $this->quantity);
            }

            $this->updateCartCount();
            $this->dispatch('cartUpdated');
            $this->dispatch('cartCountUpdated', $this->getCartCount());
            $this->showSuccess('Đã thêm sản phẩm vào giỏ hàng');
        } finally {
            $this->isLoading = false;
        }
    }

    public function buyNow()
    {
        if ($this->quantity < 1) {
            $this->showError('Số lượng phải lớn hơn 0');
            return;
        }

        if ($this->book->quantity < $this->quantity) {
            $this->showError('Không đủ hàng trong kho');
            return;
        }

        // Calculate price with discount
        $price = $this->book->price;
        if ($this->hasDiscount()) {
            if ($this->book->discount->amount != null && $this->book->discount->amount > 0) {
                // Discount theo amount
                $discountPrice = $price - $this->book->discount->amount;
            } elseif ($this->book->discount->percent != null && $this->book->discount->percent > 0) {
                // Discount theo percent
                $discountPrice = $price - ($price * $this->book->discount->percent / 100);
            } else {
                $discountPrice = $price;
            }
            
            if ($discountPrice > 0) {
                $price = $discountPrice;
            }
        }

        // Store buy now data in session with discounted price
        Session::put('buy_now', [
            'book_id' => $this->book->id,
            'quantity' => $this->quantity,
            'price' => $price
        ]);

        return redirect()->route('checkout');
    }

    public function toggleWishlist()
    {
        if (!Auth::check()) {
            $this->showError('Vui lòng đăng nhập để sử dụng tính năng này');
            return;
        }

        if ($this->inWishlist) {
            Wishlist::where('user_id', Auth::id())
                ->where('book_id', $this->book->id)
                ->delete();
            $this->inWishlist = false;
            $this->showSuccess('Đã xóa khỏi danh sách yêu thích');
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'book_id' => $this->book->id
            ]);
            $this->inWishlist = true;
            $this->showSuccess('Đã thêm vào danh sách yêu thích');
        }
    }

    public function incrementQuantity()
    {
        if ($this->quantity < $this->book->quantity) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

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
            // Create new cart item - use discounted price if available
            $price = $this->book->price;
            if ($this->hasDiscount()) {
                if ($this->book->discount->amount != null && $this->book->discount->amount > 0) {
                    // Discount theo amount
                    $discountPrice = $price - $this->book->discount->amount;
                } elseif ($this->book->discount->percent != null && $this->book->discount->percent > 0) {
                    // Discount theo percent
                    $discountPrice = $price - ($price * $this->book->discount->percent / 100);
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
        
        // Calculate price with discount
        $price = $this->book->price;
        if ($this->hasDiscount()) {
            if ($this->book->discount->amount != null && $this->book->discount->amount > 0) {
                // Discount theo amount
                $discountPrice = $price - $this->book->discount->amount;
            } elseif ($this->book->discount->percent != null && $this->book->discount->percent > 0) {
                // Discount theo percent
                $discountPrice = $price - ($price * $this->book->discount->percent / 100);
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

    public function updateCartCount()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $this->cartCount = CartItem::where('cart_id', $cart->id)->sum('quantity');
            }
        } else {
            $sessionCart = Session::get('cart', []);
            $this->cartCount = array_sum(array_column($sessionCart, 'quantity'));
        }
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

    public function showSuccess($message)
    {
        $this->showSuccessToast = true;
        $this->showErrorToast = false;
        $this->toastMessage = $message;
        
        // Auto hide toast after 3 seconds
        $this->dispatch('hideToast');
    }

    public function showError($message)
    {
        $this->showErrorToast = true;
        $this->showSuccessToast = false;
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

    public function numberFormat($price)
    {
        return number_format($price, 0, ',', '.');
    }

    public function getDiscountedPriceProperty()
    {
        static $cachedPrice = null;
        static $cachedBookId = null;
        
        // Cache the result for the same book
        if ($cachedBookId === $this->book->id && $cachedPrice !== null) {
            return $cachedPrice;
        }
        
        if (!$this->hasDiscount()) {
            $cachedPrice = $this->book->price;
            $cachedBookId = $this->book->id;
            return $cachedPrice;
        }

        $price = $this->book->price;
        if ($this->book->discount->amount != null && $this->book->discount->amount > 0) {
            // Discount theo amount
            $discountPrice = $price - $this->book->discount->amount;
        } elseif ($this->book->discount->percent != null && $this->book->discount->percent > 0) {
            // Discount theo percent
            $discountPrice = $price - ($price * $this->book->discount->percent / 100);
        } else {
            $discountPrice = $price;
        }
        
        $cachedPrice = $discountPrice > 0 ? $discountPrice : 0;
        $cachedBookId = $this->book->id;
        return $cachedPrice;
    }

    public function getDiscountPercentProperty()
    {
        static $cachedPercent = null;
        static $cachedBookId = null;
        
        // Cache the result for the same book
        if ($cachedBookId === $this->book->id && $cachedPercent !== null) {
            return $cachedPercent;
        }
        
        if (!$this->hasDiscount()) {
            $cachedPercent = 0;
            $cachedBookId = $this->book->id;
            return $cachedPercent;
        }

        $cachedPercent = $this->book->discount->percent ?? 0;
        $cachedBookId = $this->book->id;
        return $cachedPercent;
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}
