<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    public $publishers = [];
    public $categories = [];
    public $category = null;
    public $pageType = 'index'; // index, best-sellers, new-releases, recommendations, top-selling, show

    // Filter properties
    public $selectedCategories = [];
    public $selectedPublishers = [];
    public $selectedPriceRanges = [];
    public $customPriceMin = '';
    public $customPriceMax = '';
    public $sortBy = 'newest';
    
    // Toast notifications
    public $showSuccessToast = false;
    public $showErrorToast = false;
    public $toastMessage = '';

    protected $queryString = [
        'selectedCategories' => ['except' => []],
        'selectedPublishers' => ['except' => []],
        'selectedPriceRanges' => ['except' => []],
        'customPriceMin' => ['except' => ''],
        'customPriceMax' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
    ];

    public function mount($slug = null, $pageType = 'index')
    {
        $this->loadPublishers();
        $this->loadCategories();
        
        if ($slug) {
            $this->category = Category::where('slug', $slug)->firstOrFail();
            $this->pageType = 'show';
        } else {
            $this->pageType = $pageType;
        }
    }

    public function loadPublishers()
    {
        $this->publishers = Publisher::orderBy('name')->get();
    }

    public function loadCategories()
    {
        $this->categories = Category::orderBy('name')->get();
    }

    public function loadBooks()
    {
        $query = Book::with(['author', 'category', 'publisher']);

        // Apply category filter for specific category page
        if ($this->category) {
            $query->where('category_id', $this->category->id);
        }

        // Filter by categories
        if (!empty($this->selectedCategories)) {
            $query->whereIn('category_id', $this->selectedCategories);
        }

        // Filter by publishers
        if (!empty($this->selectedPublishers)) {
            $query->whereIn('publisher_id', $this->selectedPublishers);
        }

        // Filter by price range
        if (!empty($this->selectedPriceRanges)) {
            $query->where(function($q) {
                foreach ($this->selectedPriceRanges as $range) {
                    $prices = explode('-', $range);
                    if (count($prices) === 2) {
                        $minPrice = (int) $prices[0];
                        $maxPrice = (int) $prices[1];

                        if ($maxPrice === 999999999) {
                            $q->orWhere('price', '>=', $minPrice);
                        } else {
                            $q->orWhereBetween('price', [$minPrice, $maxPrice]);
                        }
                    }
                }
            });
        }

        // Filter by custom price range
        if (!empty($this->customPriceMin) || !empty($this->customPriceMax)) {
            $minPrice = (int) $this->customPriceMin;
            $maxPrice = (int) $this->customPriceMax;

            if ($minPrice > 0 || $maxPrice > 0) {
                if ($minPrice > 0 && $maxPrice > 0) {
                    $query->whereBetween('price', [$minPrice, $maxPrice]);
                } elseif ($minPrice > 0) {
                    $query->where('price', '>=', $minPrice);
                } elseif ($maxPrice > 0) {
                    $query->where('price', '<=', $maxPrice);
                }
            }
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate(12);
    }

    public function updatedSelectedCategories()
    {
        $this->resetPage();
    }

    public function updatedSelectedPublishers()
    {
        $this->resetPage();
    }

    public function updatedSelectedPriceRanges()
    {
        $this->resetPage();
    }

    public function updatedCustomPriceMin()
    {
        $this->resetPage();
    }

    public function updatedCustomPriceMax()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->selectedCategories = [];
        $this->selectedPublishers = [];
        $this->selectedPriceRanges = [];
        $this->customPriceMin = '';
        $this->customPriceMax = '';
        $this->sortBy = 'newest';
        $this->resetPage();
    }

    public function setPageType($type)
    {
        $this->pageType = $type;
        $this->resetPage();
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
        $cart = \App\Models\Cart::firstOrCreate(['user_id' => $user->id]);
        
        // Check if item already exists
        $cartItem = \App\Models\CartItem::where('cart_id', $cart->id)
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
            
            \App\Models\CartItem::create([
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
            $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                return $cart->items()->sum('quantity');
            }
        } else {
            $cart = Session::get('cart', []);
            return array_sum(array_column($cart, 'quantity'));
        }
        
        return 0;
    }

    public function render()
    {
        $books = $this->loadBooks();
        return view('livewire.category-manager', compact('books'));
    }
}
