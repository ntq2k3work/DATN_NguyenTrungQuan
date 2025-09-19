<?php

namespace App\ViewModels;

use App\Models\Wishlist;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class WishlistViewModel extends BaseViewModel
{
    protected Collection $wishlistItems;
    protected $wishlistCount = 0;

    public function __construct()
    {
        parent::__construct();
        $this->wishlistItems = collect();
        $this->loadWishlist();
    }

    public function loadWishlist(): void
    {
        if (Auth::check()) {
            $this->wishlistItems = Wishlist::where('user_id', Auth::id())
                ->with('book')
                ->get();
            $this->wishlistCount = $this->wishlistItems->count();
        } else {
            $this->wishlistItems = collect();
            $this->wishlistCount = 0;
        }
    }

    public function toggleWishlist(int $bookId): bool
    {
        $this->setLoading(true);

        if (!Auth::check()) {
            $this->addError('auth', 'Vui lòng đăng nhập để thêm vào yêu thích');
            $this->setLoading(false);
            return false;
        }

        $book = Book::find($bookId);
        if (!$book) {
            $this->addError('book', 'Sách không tồn tại');
            $this->setLoading(false);
            return false;
        }

        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('book_id', $bookId)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            $this->loadWishlist();
            $this->setLoading(false);
            return true;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'book_id' => $bookId
            ]);
            $this->loadWishlist();
            $this->setLoading(false);
            return true;
        }
    }

    public function addToWishlist(int $bookId): bool
    {
        if (!Auth::check()) {
            $this->addError('auth', 'Vui lòng đăng nhập để thêm vào yêu thích');
            return false;
        }

        $book = Book::find($bookId);
        if (!$book) {
            $this->addError('book', 'Sách không tồn tại');
            return false;
        }

        $existingWishlist = Wishlist::where('user_id', Auth::id())
            ->where('book_id', $bookId)
            ->first();

        if ($existingWishlist) {
            $this->addError('exists', 'Sản phẩm đã có trong danh sách yêu thích');
            return false;
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'book_id' => $bookId
        ]);

        $this->loadWishlist();
        return true;
    }

    public function removeFromWishlist(int $bookId): bool
    {
        if (!Auth::check()) {
            $this->addError('auth', 'Vui lòng đăng nhập để thực hiện thao tác này');
            return false;
        }

        $deleted = Wishlist::where('user_id', Auth::id())
            ->where('book_id', $bookId)
            ->delete();

        if ($deleted) {
            $this->loadWishlist();
            return true;
        }

        $this->addError('not_found', 'Sản phẩm không có trong danh sách yêu thích');
        return false;
    }

    public function isInWishlist(int $bookId): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Wishlist::where('user_id', Auth::id())
            ->where('book_id', $bookId)
            ->exists();
    }

    public function getWishlistItems(): \Illuminate\Support\Collection
    {
        return $this->wishlistItems;
    }

    public function getWishlistCount(): int
    {
        return $this->wishlistCount;
    }

    public function isEmpty(): bool
    {
        return $this->wishlistItems->isEmpty();
    }

    public function getWishlistStatus(array $bookIds): array
    {
        if (!Auth::check()) {
            return array_fill_keys($bookIds, false);
        }

        $wishlistStatus = [];
        foreach ($bookIds as $bookId) {
            $wishlistStatus[$bookId] = $this->isInWishlist($bookId);
        }

        return $wishlistStatus;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'wishlistItems' => ($this->wishlistItems instanceof \Illuminate\Support\Collection) ? $this->wishlistItems->toArray() : $this->wishlistItems,
            'wishlistCount' => $this->wishlistCount,
            'isEmpty' => $this->isEmpty(),
        ]);
    }
}
