<?php

namespace App\Livewire;

use App\ViewModels\WishlistViewModel;
use Livewire\Component;

class WishlistManager extends Component
{
    public $bookId;
    public $wishlistItems = [];
    public $wishlistCount = 0;
    public $loading = false;
    public $isEmpty = true;

    protected $listeners = ['wishlistUpdated' => 'loadWishlist', 'toggleWishlist' => 'toggleWishlist'];

    public function mount($bookId = null)
    {
        $this->bookId = $bookId;
        $this->loadWishlist();
    }

    public function loadWishlist()
    {
        $wishlistViewModel = new WishlistViewModel();
        $this->wishlistItems = $wishlistViewModel->getWishlistItems();
        $this->wishlistCount = $wishlistViewModel->getWishlistCount();
        $this->loading = $wishlistViewModel->isLoading();
        $this->isEmpty = $wishlistViewModel->isEmpty();
    }

    public function toggleWishlist($bookId = null)
    {
        $bookId = $bookId ?? $this->bookId;

        if (!$bookId) {
            $this->dispatch('show-toast', message: 'Không thể xác định sách', type: 'error');
            return;
        }

        $wishlistViewModel = new WishlistViewModel();
        $success = $wishlistViewModel->toggleWishlist($bookId);

        if ($success) {
            $message = $wishlistViewModel->isInWishlist($bookId) ? 'Đã thêm vào yêu thích' : 'Đã xóa khỏi yêu thích';
            $this->dispatch('show-toast', message: $message, type: 'success');
            $this->loadWishlist();
            $this->dispatch('wishlistCountUpdated', count: $this->wishlistCount);
        } else {
            $errors = $wishlistViewModel->getErrors();
            $this->dispatch('show-toast', message: $errors['auth'] ?? $errors['book'] ?? 'Có lỗi xảy ra', type: 'error');
        }
    }

    public function isInWishlist($bookId = null)
    {
        $bookId = $bookId ?? $this->bookId;

        if (!$bookId) {
            return false;
        }

        $wishlistViewModel = new WishlistViewModel();
        return $wishlistViewModel->isInWishlist($bookId);
    }

    public function getWishlistCount()
    {
        $this->dispatch('wishlistCountUpdated', count: $this->wishlistCount);
    }

    public function render()
    {
        return view('livewire.wishlist-manager', [
            'wishlistItems' => $this->wishlistItems,
            'wishlistCount' => $this->wishlistCount,
            'loading' => $this->loading,
            'isEmpty' => $this->isEmpty,
        ]);
    }
}

