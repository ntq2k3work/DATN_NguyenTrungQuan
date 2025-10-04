<?php

namespace App\ViewModels;

use App\Models\Book;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class BookViewModel extends BaseViewModel
{
    protected $book;
    protected $isInWishlist = false;
    protected $finalPrice = 0;

    public function __construct(Book $book)
    {
        parent::__construct();
        $this->book = $book;
        $this->calculateFinalPrice();
        $this->checkWishlistStatus();
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getId(): int
    {
        return $this->book->id;
    }

    public function getTitle(): string
    {
        return $this->book->title;
    }

    public function getSlug(): string
    {
        return $this->book->slug;
    }

    public function getPrice(): float
    {
        return $this->book->price;
    }

    public function getFinalPrice(): float
    {
        return $this->finalPrice ?? $this->book->price;
    }

    public function getQuantity(): int
    {
        return $this->book->quantity;
    }

    public function getImageUrl(): string
    {
        $imageUrl = $this->book->image_url;
        
        // If the URL is absolute (starts with http:// or https://), return as-is
        if (str_starts_with($imageUrl, 'http://') || str_starts_with($imageUrl, 'https://')) {
            return $imageUrl;
        }
        
        // If it's a relative path, add /storage prefix
        return '/storage/' . ltrim($imageUrl, '/');
    }

    public function getDescription(): string
    {
        return $this->book->description;
    }

    public function getAuthor(): ?object
    {
        return $this->book->author;
    }

    public function getCategory(): ?object
    {
        return $this->book->category;
    }

    public function getPublisher(): ?object
    {
        return $this->book->publisher;
    }

    public function getDiscount(): ?object
    {
        return $this->book->discount;
    }

    public function isInWishlist(): bool
    {
        return $this->isInWishlist;
    }

    public function isAvailable(): bool
    {
        return $this->book->quantity > 0;
    }

    public function hasDiscount(): bool
    {
        return $this->book->discount !== null;
    }

    public function getDiscountPercent(): float
    {
        return $this->book->discount ? ($this->book->discount->discount_percent ?? 0) : 0;
    }

    public function getDiscountAmount(): float
    {
        return $this->book->discount ? ($this->book->discount->discount_amount ?? 0) : 0;
    }

    public function getSavings(): float
    {
        return $this->book->price - $this->finalPrice;
    }

    public function getSavingsPercent(): float
    {
        if ($this->book->price == 0) {
            return 0;
        }
        return ($this->getSavings() / $this->book->price) * 100;
    }

    public function formatPrice(float $price): string
    {
        return number_format($price, 0, ',', '.') . ' VNĐ';
    }

    public function getFormattedPrice(): string
    {
        return $this->formatPrice($this->book->price);
    }

    public function getFormattedFinalPrice(): string
    {
        return $this->formatPrice($this->finalPrice);
    }

    public function getFormattedSavings(): string
    {
        return $this->formatPrice($this->getSavings());
    }

    private function calculateFinalPrice(): void
    {
        $this->finalPrice = $this->book->final_price ?? $this->book->price;
    }

    private function checkWishlistStatus(): void
    {
        if (Auth::check()) {
            $this->isInWishlist = Wishlist::where('user_id', Auth::id())
                ->where('book_id', $this->book->id)
                ->exists();
        }
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'book' => $this->book->toArray(),
            'isInWishlist' => $this->isInWishlist,
            'finalPrice' => $this->finalPrice,
            'isAvailable' => $this->isAvailable(),
            'hasDiscount' => $this->hasDiscount(),
            'discountPercent' => $this->getDiscountPercent(),
            'discountAmount' => $this->getDiscountAmount(),
            'savings' => $this->getSavings(),
            'savingsPercent' => $this->getSavingsPercent(),
            'formattedPrice' => $this->getFormattedPrice(),
            'formattedFinalPrice' => $this->getFormattedFinalPrice(),
            'formattedSavings' => $this->getFormattedSavings(),
        ]);
    }
}
