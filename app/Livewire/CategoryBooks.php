<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryBooks extends Component
{
    use WithPagination;

    protected $queryString = [
        'sortBy' => ['as' => 'sort', 'except' => 'default'],
    ];

    public $selectedPublishers = [];
    public $selectedPriceRanges = [];
    public $customPriceMin = '';
    public $customPriceMax = '';
    public $sortBy = 'default';
    public $perPage = 12;
    public $categoryType = 'all'; // all, best-sellers, new-releases, top-selling, recommendations
    public $categorySlug = null;
    public $categoryId = null;

    protected $listeners = ['filtersUpdated' => 'updateFilters'];

    public function updateFilters($filters)
    {
        $this->selectedPublishers = $filters['publishers'] ?? [];
        $this->selectedPriceRanges = $filters['priceRanges'] ?? [];
        $this->customPriceMin = $filters['customPriceMin'] ?? '';
        $this->customPriceMax = $filters['customPriceMax'] ?? '';
        $this->resetPage();
    }

    public function mount()
    {
        // Initialize from URL parameters
        $this->selectedPublishers = request()->get('publishers', []);
        $this->selectedPriceRanges = request()->get('price_ranges', []);
        $this->customPriceMin = request()->get('custom_price_min', '');
        $this->customPriceMax = request()->get('custom_price_max', '');
        $this->sortBy = request()->get('sort', 'default');

        // Determine category type from route
        $routeName = request()->route()->getName();
        $this->categorySlug = request()->route('slug');
        if ($this->categorySlug) {
            $category = Category::where('slug', $this->categorySlug)->first();
            $this->categoryId = $category?->id;
        }
        if (str_contains($routeName, 'best-sellers')) {
            $this->categoryType = 'best-sellers';
        } elseif (str_contains($routeName, 'new-releases')) {
            $this->categoryType = 'new-releases';
        } elseif (str_contains($routeName, 'top-selling')) {
            $this->categoryType = 'top-selling';
        } elseif (str_contains($routeName, 'recommendations')) {
            $this->categoryType = 'recommendations';
        } else {
            $this->categoryType = 'all';
        }
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
        $this->selectedPublishers = [];
        $this->selectedPriceRanges = [];
        $this->customPriceMin = '';
        $this->customPriceMax = '';
        $this->sortBy = 'default';
        $this->resetPage();
    }

    public function applyCustomPriceRange()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Book::with(['author', 'category', 'publisher']);

        // Filter by selected category (when browsing a specific category slug)
        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        // Apply category type specific filters
        switch ($this->categoryType) {
            case 'best-sellers':
                $query->leftJoin('discounts', 'books.id', '=', 'discounts.book_id')
                    ->where(function($q) {
                        $q->whereNotNull('discounts.percent')
                          ->orWhereNotNull('discounts.amount');
                    })
                    ->orderByRaw('COALESCE(discounts.percent, 0) DESC')
                    ->orderByRaw('COALESCE(discounts.amount, 0) DESC')
                    ->select('books.*');
                // Tạo discount_percent column
                $query->addSelect('discounts.percent as discount_percent');
                $query->addSelect('discounts.amount as discount_amount');
                break;
            case 'new-releases':
                // Books created in the last 30 days
                $query->orderBy('created_at', 'desc');
                break;

            case 'recommendations':
                // Lấy các sách có cùng danh mục với sách user yêu thích, nếu không có thì hiển thị sách mới nhất
                $user = Auth::check() ? Auth::user() : null;
                $favoriteCategoryIds = [];

                if ($user) {
                    // Lấy id các sách user yêu thích
                    $wishlistBookIds = DB::table('wishlists')
                        ->where('user_id', $user->id)
                        ->pluck('book_id')
                        ->toArray();

                    if (!empty($wishlistBookIds)) {
                        // Lấy các category_id từ các sách yêu thích
                        $favoriteCategoryIds = Book::whereIn('id', $wishlistBookIds)
                            ->pluck('category_id')
                            ->unique()
                            ->filter()
                            ->toArray();
                    }
                }

                if (!empty($favoriteCategoryIds)) {
                    $query->whereIn('category_id', $favoriteCategoryIds)
                        ->whereNotIn('id', function($subQuery) use ($user) {
                            $subQuery->select('book_id')
                                  ->from('wishlists')
                                  ->where('user_id', $user->id);
                        })
                        ->orderBy('created_at', 'desc');
                } else {
                    // Nếu không có danh mục yêu thích hoặc user chưa đăng nhập, hiển thị sách mới nhất
                    $query->orderBy('created_at', 'desc');
                }
                break;
            default:
                // All books
                break;
        }

        // Apply publisher filters - only if publishers are selected
        if (!empty($this->selectedPublishers) && is_array($this->selectedPublishers)) {
            $query->whereIn('publisher_id', array_filter($this->selectedPublishers));
        }

        // Apply price range filters - only if price ranges are selected
        if (!empty($this->selectedPriceRanges) && is_array($this->selectedPriceRanges)) {
            $query->where(function ($q) {
                foreach (array_filter($this->selectedPriceRanges) as $range) {
                    switch ($range) {
                        case '0-50000':
                            $q->orWhereBetween('price', [0, 50000]);
                            break;
                        case '50000-100000':
                            $q->orWhereBetween('price', [50000, 100000]);
                            break;
                        case '100000-200000':
                            $q->orWhereBetween('price', [100000, 200000]);
                            break;
                        case '200000-500000':
                            $q->orWhereBetween('price', [200000, 500000]);
                            break;
                        case '500000+':
                            $q->orWhere('price', '>', 500000);
                            break;
                    }
                }
            });
        }

        // Apply custom price range - only if both values are provided
        if ($this->customPriceMin && $this->customPriceMax) {
            $min = (float) $this->customPriceMin;
            $max = (float) $this->customPriceMax;
            if ($min <= $max) {
                $query->whereBetween('price', [$min, $max]);
            }
        } elseif ($this->customPriceMin) {
            $min = (float) $this->customPriceMin;
            $query->where('price', '>=', $min);
        } elseif ($this->customPriceMax) {
            $max = (float) $this->customPriceMax;
            $query->where('price', '<=', $max);
        }

        // If a specific sort is selected, clear any earlier orderings so it takes precedence
        if ($this->sortBy !== 'default') {
            $query->reorder();
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'price_asc':
                // Ensure discounts table is available for computing effective price
                if ($this->categoryType !== 'best-sellers') {
                    $query->leftJoin('discounts', 'books.id', '=', 'discounts.book_id')
                          ->select('books.*');
                }
                $query->orderByRaw(
                    '(books.price - CASE '
                    . 'WHEN discounts.percent IS NOT NULL AND discounts.percent > 0 '
                    . 'AND (discounts.start_date IS NULL OR discounts.start_date <= NOW()) '
                    . 'AND (discounts.end_date IS NULL OR discounts.end_date >= NOW()) '
                    . 'THEN books.price * (discounts.percent / 100.0) '
                    . 'WHEN discounts.amount IS NOT NULL AND discounts.amount > 0 '
                    . 'AND (discounts.start_date IS NULL OR discounts.start_date <= NOW()) '
                    . 'AND (discounts.end_date IS NULL OR discounts.end_date >= NOW()) '
                    . 'THEN LEAST(discounts.amount, books.price) '
                    . 'ELSE 0 END) ASC'
                );
                break;
            case 'price_desc':
                // Ensure discounts table is available for computing effective price
                if ($this->categoryType !== 'best-sellers') {
                    $query->leftJoin('discounts', 'books.id', '=', 'discounts.book_id')
                          ->select('books.*');
                }
                $query->orderByRaw(
                    '(books.price - CASE '
                    . 'WHEN discounts.percent IS NOT NULL AND discounts.percent > 0 '
                    . 'AND (discounts.start_date IS NULL OR discounts.start_date <= NOW()) '
                    . 'AND (discounts.end_date IS NULL OR discounts.end_date >= NOW()) '
                    . 'THEN books.price * (discounts.percent / 100.0) '
                    . 'WHEN discounts.amount IS NOT NULL AND discounts.amount > 0 '
                    . 'AND (discounts.start_date IS NULL OR discounts.start_date <= NOW()) '
                    . 'AND (discounts.end_date IS NULL OR discounts.end_date >= NOW()) '
                    . 'THEN LEAST(discounts.amount, books.price) '
                    . 'ELSE 0 END) DESC'
                );
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
                // For top-selling, use sales_count as primary sort
                if ($this->categoryType === 'top-selling') {
                    $query->orderBy('sales_count', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
                break;
        }

        // Add secondary sorting for consistency
        if ($this->categoryType === 'top-selling') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('title', 'asc');
        }

        $books = $query->paginate($this->perPage);
        $publishers = Publisher::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('livewire.category-books', [
            'books' => $books,
            'publishers' => $publishers,
            'categories' => $categories,
            'categoryType' => $this->categoryType,
        ]);
    }
}
