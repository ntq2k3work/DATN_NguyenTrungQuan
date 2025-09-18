<?php

namespace App\ViewModels;

use App\Models\Book;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class HomeViewModel extends BaseViewModel
{
    protected Collection $books;
    protected Collection $categories;
    protected Collection $featuredBooks;
    protected Collection $newReleases;
    protected Collection $bestSellers;
    protected Collection $recommendedBooks;

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
            ->get();
    }

    public function loadCategories(): void
    {
        $this->categories = Category::withCount('books')
            ->orderBy('name')
            ->get();
    }

    public function loadFeaturedBooks(): void
    {
        $this->featuredBooks = Book::with(['author', 'category', 'publisher', 'discount'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
    }

    public function loadNewReleases(): void
    {
        $this->newReleases = Book::with(['author', 'category', 'publisher', 'discount'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
    }

    public function loadBestSellers(): void
    {
        $this->bestSellers = Book::with(['author', 'category', 'publisher', 'discount'])
            ->where('status', 'active')
            ->limit(8)
            ->get();
    }

    public function loadRecommendedBooks(): void
    {
        if (!Auth::check()) {
            $this->recommendedBooks = collect();
            return;
        }

        $wishlists = Wishlist::where('user_id', Auth::id())->get('book_id');
        $bookIdsInWishlist = $wishlists->pluck('book_id');

        if ($bookIdsInWishlist->isEmpty()) {
            $this->recommendedBooks = collect();
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

        $this->recommendedBooks = $books->values();
    }

    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function getFeaturedBooks(): Collection
    {
        return $this->featuredBooks;
    }

    public function getNewReleases(): Collection
    {
        return $this->newReleases;
    }

    public function getBestSellers(): Collection
    {
        return $this->bestSellers;
    }

    public function getRecommendedBooks(): Collection
    {
        return $this->recommendedBooks;
    }

    public function getBooksCount(): int
    {
        return $this->books->count();
    }

    public function getCategoriesCount(): int
    {
        return $this->categories->count();
    }

    public function hasFeaturedBooks(): bool
    {
        return $this->featuredBooks->isNotEmpty();
    }

    public function hasNewReleases(): bool
    {
        return $this->newReleases->isNotEmpty();
    }

    public function hasBestSellers(): bool
    {
        return $this->bestSellers->isNotEmpty();
    }

    public function hasRecommendedBooks(): bool
    {
        return $this->recommendedBooks->isNotEmpty();
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'books' => $this->books->toArray(),
            'categories' => $this->categories->toArray(),
            'featuredBooks' => $this->featuredBooks->toArray(),
            'newReleases' => $this->newReleases->toArray(),
            'bestSellers' => $this->bestSellers->toArray(),
            'recommendedBooks' => $this->recommendedBooks->toArray(),
            'booksCount' => $this->getBooksCount(),
            'categoriesCount' => $this->getCategoriesCount(),
            'hasFeaturedBooks' => $this->hasFeaturedBooks(),
            'hasNewReleases' => $this->hasNewReleases(),
            'hasBestSellers' => $this->hasBestSellers(),
            'hasRecommendedBooks' => $this->hasRecommendedBooks(),
        ]);
    }
}
