<?php

namespace App\ViewModels;

use App\Models\Book;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class HomeViewModel extends BaseViewModel
{
    protected $books = [];
    protected $categories = [];
    protected $featuredBooks = [];
    protected $newReleases = [];
    protected $bestSellers = [];
    protected $recommendedBooks = [];

    public function __construct()
    {
        parent::__construct();
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->loadBooks();
        $this->loadCategories();
        $this->loadFeaturedBooks();
        $this->loadNewReleases();
        $this->loadBestSellers();
        $this->loadRecommendedBooks();
    }

    public function loadBooks(): void
    {
        $this->books = Book::with(['author', 'category', 'publisher', 'discount'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function loadCategories(): void
    {
        $this->categories = Category::withCount('books')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function loadFeaturedBooks(): void
    {
        $this->featuredBooks = Book::with(['author', 'category', 'publisher', 'discount'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get()
            ->toArray();
    }

    public function loadNewReleases(): void
    {
        $this->newReleases = Book::with(['author', 'category', 'publisher', 'discount'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get()
            ->toArray();
    }

    public function loadBestSellers(): void
    {
        $this->bestSellers = Book::with(['author', 'category', 'publisher', 'discount'])
            ->where('status', 'active')
            ->limit(8)
            ->get()
            ->toArray();
    }

    public function loadRecommendedBooks(): void
    {
        if (!Auth::check()) {
            $this->recommendedBooks = [];
            return;
        }

        $wishlists = Wishlist::where('user_id', Auth::id())->get('book_id');
        $bookIdsInWishlist = $wishlists->pluck('book_id');

        if ($bookIdsInWishlist->isEmpty()) {
            $this->recommendedBooks = [];
            return;
        }

        $categoriesAuthors = Book::whereIn('id', $bookIdsInWishlist)
            ->select('category_id', 'author_id')
            ->distinct()
            ->get();

        $categoryIds = $categoriesAuthors->pluck('category_id')->filter()->unique();
        $authorIds = $categoriesAuthors->pluck('author_id')->filter()->unique();

        $books = Book::whereNotIn('id', $bookIdsInWishlist)
            ->where(function($query) use ($categoryIds, $authorIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereIn('author_id', $authorIds);
            })
            ->with(['author', 'category', 'publisher', 'discount'])
            ->get();

        // Sort by relevance
        $books = $books->map(function($book) use ($categoryIds, $authorIds) {
            $score = 0;
            if (in_array($book->category_id, $categoryIds->toArray())) {
                $score += 2;
            }
            if (in_array($book->author_id, $authorIds->toArray())) {
                $score += 1;
            }
            $book->relevance_score = $score;
            return $book;
        })->sortByDesc('relevance_score')->take(8);

        $this->recommendedBooks = $books->toArray();
    }

    public function getBooks(): array
    {
        return $this->books;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getFeaturedBooks(): array
    {
        return $this->featuredBooks;
    }

    public function getNewReleases(): array
    {
        return $this->newReleases;
    }

    public function getBestSellers(): array
    {
        return $this->bestSellers;
    }

    public function getRecommendedBooks(): array
    {
        return $this->recommendedBooks;
    }

    public function getBooksCount(): int
    {
        return count($this->books);
    }

    public function getCategoriesCount(): int
    {
        return count($this->categories);
    }

    public function hasFeaturedBooks(): bool
    {
        return !empty($this->featuredBooks);
    }

    public function hasNewReleases(): bool
    {
        return !empty($this->newReleases);
    }

    public function hasBestSellers(): bool
    {
        return !empty($this->bestSellers);
    }

    public function hasRecommendedBooks(): bool
    {
        return !empty($this->recommendedBooks);
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'books' => $this->books,
            'categories' => $this->categories,
            'featuredBooks' => $this->featuredBooks,
            'newReleases' => $this->newReleases,
            'bestSellers' => $this->bestSellers,
            'recommendedBooks' => $this->recommendedBooks,
            'booksCount' => $this->getBooksCount(),
            'categoriesCount' => $this->getCategoriesCount(),
            'hasFeaturedBooks' => $this->hasFeaturedBooks(),
            'hasNewReleases' => $this->hasNewReleases(),
            'hasBestSellers' => $this->hasBestSellers(),
            'hasRecommendedBooks' => $this->hasRecommendedBooks(),
        ]);
    }
}
