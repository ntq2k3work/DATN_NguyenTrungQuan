<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryBooks extends Component
{
    use WithPagination;

    public $selectedPublishers = [];
    public $selectedPriceRanges = [];
    public $customPriceMin = '';
    public $customPriceMax = '';
    public $sortBy = 'default';
    public $perPage = 12;
    public $categoryType = 'all'; // all, best-sellers, new-releases, top-selling, recommendations

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

        // Apply category type specific filters
        switch ($this->categoryType) {
            case 'best-sellers':
                // For best sellers, you might want to add a best_seller flag or sales count logic
                $query->where('is_best_seller', true)->orWhere('sales_count', '>', 100);
                break;
            case 'new-releases':
                // Books created in the last 30 days
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case 'top-selling':
                // Books with highest sales count
                $query->orderBy('sales_count', 'desc');
                break;
            case 'recommendations':
                // For recommendations, you might want to add recommendation logic
                // For now, just show popular books
                $query->where('is_featured', true)->orWhere('sales_count', '>', 50);
                break;
            default:
                // All books
                break;
        }

        // Apply publisher filters
        if (!empty($this->selectedPublishers)) {
            $query->whereIn('publisher_id', $this->selectedPublishers);
        }

        // Apply price range filters
        if (!empty($this->selectedPriceRanges)) {
            $query->where(function ($q) {
                foreach ($this->selectedPriceRanges as $range) {
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

        // Apply custom price range
        if ($this->customPriceMin || $this->customPriceMax) {
            $min = $this->customPriceMin ?: 0;
            $max = $this->customPriceMax ?: PHP_INT_MAX;
            $query->whereBetween('price', [$min, $max]);
        }

        // Apply sorting (but not for top-selling as it already has its own order)
        if ($this->categoryType !== 'top-selling') {
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
