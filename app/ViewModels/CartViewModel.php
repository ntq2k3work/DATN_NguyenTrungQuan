<?php

namespace App\ViewModels;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartViewModel extends BaseViewModel
{
    protected $cartItems = [];
    protected $total = 0;
    protected $cartCount = 0;

    public function __construct()
    {
        parent::__construct();
        $this->loadCart();
    }

    public function loadCart(): void
    {
        try {
            if (Auth::check()) {
                $this->loadUserCart();
            } else {
                $this->loadGuestCart();
            }

            $this->calculateTotal();
        } catch (\Exception $e) {
            Log::error('Cart loading error: ' . $e->getMessage());
            $this->cartItems = [];
            $this->cartCount = 0;
            $this->total = 0;
        }
    }

    /**
     * Load cart for authenticated user
     */
    protected function loadUserCart(): void
    {
		$cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
			$items = $cart->items()
				->with(['book.author', 'book.category', 'book.discount'])
				->get();

			$this->cartItems = $items->map(function ($item) {
				$book = $item->book;
				$finalPrice = $book ? $this->computeFinalPrice($book) : ($item->price ?? 0);
				return [
					'id' => $item->id,
					'book_id' => $item->book_id,
					'quantity' => $item->quantity,
					'price' => $finalPrice,
					'book' => $book ? $book->toArray() : null,
				];
			})->toArray();
            $this->cartCount = $cart->items()->sum('quantity');
        } else {
            $this->cartItems = [];
            $this->cartCount = 0;
        }
    }

    /**
     * Load cart for guest user from session
     */
    protected function loadGuestCart(): void
    {
        $sessionCart = Session::get('cart', []);
        $this->cartItems = [];
        $this->cartCount = 0;

		foreach ($sessionCart as $item) {
			$book = Book::with(['author', 'category', 'discount'])->find($item['book_id']);
            if ($book) {
                $this->cartItems[] = [
                    'id' => $item['book_id'],
                    'book_id' => $item['book_id'],
                    'quantity' => $item['quantity'],
					'price' => $this->computeFinalPrice($book),
                    'book' => $book->toArray()
                ];
                $this->cartCount += $item['quantity'];
            }
        }
    }

    public function addToCart(int $bookId, int $quantity = 1): bool
    {
        $this->setLoading(true);

        try {
            if (!Auth::check()) {
                $this->addError('auth', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng');
                $this->setLoading(false);
                return false;
            }

			$book = Book::with('discount')->find($bookId);
            if (!$book) {
                $this->addError('book', 'Sách không tồn tại');
                $this->setLoading(false);
                return false;
            }

            if ($book->quantity < $quantity) {
                $this->addError('quantity', 'Không đủ hàng trong kho');
                $this->setLoading(false);
                return false;
            }

            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cartItem = $cart->items()->where('book_id', $bookId)->first();

            if ($cartItem) {
                // Check if adding quantity exceeds stock
                if (($cartItem->quantity + $quantity) > $book->quantity) {
                    $this->addError('quantity', "Chỉ còn {$book->quantity} sản phẩm trong kho");
                    $this->setLoading(false);
                    return false;
                }
                $cartItem->increment('quantity', $quantity);
			} else {
				$cart->items()->create([
					'book_id' => $bookId,
					'quantity' => $quantity,
					'price' => $this->computeFinalPrice($book)
				]);
            }

            $this->loadCart();
            $this->setLoading(false);
            return true;

        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            $this->addError('general', 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
            $this->setLoading(false);
            return false;
        }
    }

    public function updateQuantity(int $bookId, int $quantity): bool
    {
        try {
            if ($quantity < 1) {
                return $this->removeFromCart($bookId);
            }

            if (!Auth::check()) {
                $this->addError('auth', 'Vui lòng đăng nhập để cập nhật giỏ hàng');
                return false;
            }

            $cart = Cart::where('user_id', Auth::id())->first();
            if (!$cart) {
                return false;
            }

            $cartItem = $cart->items()->where('book_id', $bookId)->first();
            if (!$cartItem) {
                return false;
            }

            $book = $cartItem->book;
            if ($quantity > $book->quantity) {
                $this->addError('quantity', "Chỉ còn {$book->quantity} sản phẩm trong kho");
                $this->loadCart();
                return false;
            }

            $cartItem->update(['quantity' => $quantity]);
            $this->loadCart();
            return true;

        } catch (\Exception $e) {
            Log::error('Update quantity error: ' . $e->getMessage());
            $this->addError('general', 'Có lỗi xảy ra khi cập nhật số lượng');
            return false;
        }
    }

    public function removeFromCart(int $bookId): bool
    {
        try {
            if (!Auth::check()) {
                $this->addError('auth', 'Vui lòng đăng nhập để xóa sản phẩm khỏi giỏ hàng');
                return false;
            }

            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cart->items()->where('book_id', $bookId)->delete();
                $this->loadCart();
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Remove from cart error: ' . $e->getMessage());
            $this->addError('general', 'Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng');
            return false;
        }
    }

    public function clearCart(): void
    {
        try {
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $cart->items()->delete();
                }
            } else {
                Session::forget('cart');
            }

            $this->loadCart();

        } catch (\Exception $e) {
            Log::error('Clear cart error: ' . $e->getMessage());
        }
    }

    /**
     * Get cart items with enhanced data
     */
    public function getCartItems(): array
    {
        return $this->cartItems;
    }

    /**
     * Get cart count
     */
    public function getCartCount(): int
    {
        return $this->cartCount;
    }

    /**
     * Get total amount
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty(): bool
    {
        return empty($this->cartItems);
    }

    /**
     * Calculate total with enhanced logic
     */
    private function calculateTotal(): void
    {
        $this->total = 0;

		foreach ($this->cartItems as $item) {
			$bookArray = $item['book'] ?? null;
			$price = $item['price'] ?? 0;
			$quantity = $item['quantity'] ?? 1;

			$this->total += $price * $quantity;
		}
    }

	/**
	 * Compute final price like product detail page
	 */
	private function computeFinalPrice($book): float
	{
		$price = (float) ($book->price ?? 0);
		$percent = (float) ($book->discount->percent ?? 0);
		$amount = (float) ($book->discount->amount ?? 0);

		$final = $price - ($price * $percent / 100) - $amount;
		return $final > 0 ? $final : 0.0;
	}

    /**
     * Get cart summary
     */
    public function getCartSummary(): array
    {
        return [
            'items' => $this->cartItems,
            'count' => $this->cartCount,
            'total' => $this->total,
            'isEmpty' => $this->isEmpty(),
            'loading' => $this->isLoading(),
            'errors' => $this->getErrors()
        ];
    }

    /**
     * Validate cart items
     */
    public function validateCartItems(): array
    {
        $errors = [];

        foreach ($this->cartItems as $item) {
            $book = Book::find($item['book_id']);

            if (!$book) {
                $errors[] = "Sản phẩm không tồn tại: " . ($item['book']['title'] ?? 'Unknown');
                continue;
            }

            if ($book->quantity < $item['quantity']) {
                $errors[] = "Không đủ số lượng tồn kho cho sản phẩm: {$book->title}. Số lượng còn lại: {$book->quantity}";
            }
        }

        return $errors;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'cartItems' => $this->cartItems,
            'cartCount' => $this->cartCount,
            'total' => $this->total,
            'isEmpty' => $this->isEmpty(),
        ]);
    }
}
