<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SearchComponent extends Component
{
    public $query = '';
    public $suggestions = [];
    public $results = [];
    public $showSuggestions = false;
    public $showResults = false;
    public $loading = false;

    protected $listeners = ['searchUpdated' => 'performSearch'];

    public function updatedQuery()
    {
        if (strlen($this->query) >= 2) {
            $this->loadSuggestions();
        } else {
            $this->suggestions = [];
            $this->showSuggestions = false;
        }

        if (strlen($this->query) >= 3) {
            $this->performSearch();
        } else {
            $this->results = [];
            $this->showResults = false;
        }
    }

    public function loadSuggestions()
    {
        $this->loading = true;

        $suggestions = collect();

        // Search books
        $books = Book::where('title', 'like', '%' . $this->query . '%')
            ->limit(3)
            ->get()
            ->map(function ($book) {
                return [
                    'type' => 'book',
                    'text' => $book->title,
                    'url' => route('product.show', $book->slug)
                ];
            });

        // Search authors
        $authors = Author::where('name', 'like', '%' . $this->query . '%')
            ->limit(2)
            ->get()
            ->map(function ($author) {
                return [
                    'type' => 'author',
                    'text' => $author->name,
                    'url' => route('categories.index', ['author' => $author->id])
                ];
            });

        // Search categories
        $categories = Category::where('name', 'like', '%' . $this->query . '%')
            ->limit(2)
            ->get()
            ->map(function ($category) {
                return [
                    'type' => 'category',
                    'text' => $category->name,
                    'url' => route('categories.index', ['category' => $category->id])
                ];
            });

        $suggestions = $suggestions->merge($books)->merge($authors)->merge($categories);

        $this->suggestions = $suggestions->take(5)->toArray();
        $this->showSuggestions = count($this->suggestions) > 0;
        $this->loading = false;
    }

    public function performSearch()
    {
        if (strlen($this->query) < 3) {
            return;
        }

        $this->loading = true;

        $books = Book::where('title', 'like', '%' . $this->query . '%')
            ->orWhereHas('author', function ($query) {
                $query->where('name', 'like', '%' . $this->query . '%');
            })
            ->orWhereHas('category', function ($query) {
                $query->where('name', 'like', '%' . $this->query . '%');
            })
            ->with(['author', 'category','discount'])
            ->limit(5)
            ->get()
            ->map(function ($book) {

                $discount     = $book->discount?->percent ?: $book->discount?->amount;
                $discountType = $book->discount?->percent ? 'percent' : 'amount';
                $price        = $book->price;

                if ($discountType === 'percent') {
                    $discountPrice = $price * (100 - $discount) / 100;
                } else {
                    $discountPrice = $price - $discount;
                }

                if ($discountPrice < 0) {
                    $discountPrice = 0;
                }

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author?->name ?? 'Unknown',
                    'publisher' => $book->publisher?->name ?? 'Unknown',
                    'category' => $book->category?->name ?? 'Unknown',
                    'price' => number_format($price, 0, ',', '.'),
                    'discount_price' => number_format($discountPrice, 0, ',', '.'),
                    'discount_percent' => $discount,
                    'has_discount' => $discount > 0,
                    'image_url' => $book->image_url ? (str_starts_with($book->image_url, 'http') ? $book->image_url : '/storage/' . ltrim($book->image_url, '/')) : '/images/placeholder.jpg',
                    'url' => route('product.show', $book->slug),
                    'discount' => $discount,
                ];
            });
        $this->results = $books->toArray();
        $this->showResults = count($this->results) > 0;
        Log::debug(print_r($this->results, true));
        $this->loading = false;
    }

    public function selectSuggestion($text)
    {
        $this->query = $text;
        $this->showSuggestions = false;
        $this->performSearch();
    }

    public function clearSearch()
    {
        $this->query = '';
        $this->suggestions = [];
        $this->results = [];
        $this->showSuggestions = false;
        $this->showResults = false;
    }

    public function getSuggestionIcon($type)
    {
        $icons = [
            'book' => '📖',
            'author' => '✍️',
            'publisher' => '🏢',
            'category' => '📚'
        ];
        return $icons[$type] ?? '🔍';
    }

    public function render()
    {
        return view('livewire.search-component');
    }
}
