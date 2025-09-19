<?php

namespace App\Livewire;

use App\Models\Book;
use App\ViewModels\BookViewModel;
use Livewire\Component;

class BookCard extends Component
{
    public $bookId;
    public $bookData;
    public $showWishlistButton = true;
    public $showAddToCartButton = true;
    public $cardStyle = 'default'; // default, horizontal, compact, best-seller, new-release, top-selling
    public $rank = null; // For top-selling cards
    public $addingToCart = false;
    public $componentId;
    public $inWishlist = false;

    protected $listeners = ['cartUpdated', 'wishlistUpdated', 'wishlistToggled' => 'handleWishlistToggled', 'resetAddingToCart'];

    public function mount($book, $showWishlistButton = true, $showAddToCartButton = true, $cardStyle = 'default', $rank = null)
    {
        $this->bookId = $book->id;
        $this->bookData = $book->toArray();
        $this->showWishlistButton = $showWishlistButton;
        $this->showAddToCartButton = $showAddToCartButton;
        $this->cardStyle = $cardStyle;
        $this->rank = $rank;
        $this->componentId = uniqid('bookcard_');
        $this->inWishlist = (new BookViewModel(Book::find($this->bookId)))->isInWishlist();
    }

    public function addToCart()
    {
        // Prevent multiple rapid clicks
        if ($this->addingToCart) {
            return;
        }

        $this->addingToCart = true;

        // Dispatch directly to cart manager
        $this->dispatch('addToCart', bookId: $this->bookId);

        // Reset after 2 seconds using JavaScript
        $this->js("setTimeout(() => { \$wire.resetAddingToCart('{$this->componentId}'); }, 2000);");
    }

    public function toggleWishlist()
    {
        // Optimistically update UI
        $this->inWishlist = !$this->inWishlist;
        $this->dispatch('toggleWishlist', bookId: $this->bookId);
    }

    public function resetAddingToCart($componentId)
    {
        if ($componentId === $this->componentId) {
            $this->addingToCart = false;
        }
    }

    public function isInWishlist()
    {
        return $this->inWishlist;
    }

    public function handleWishlistToggled($bookId, $inWishlist)
    {
        if ($bookId === $this->bookId) {
            $this->inWishlist = (bool) $inWishlist;
        }
    }

    public function render()
    {
        $bookViewModel = new BookViewModel(Book::find($this->bookId));

        return view('livewire.book-card', [
            'bookViewModel' => $bookViewModel,
            'isInWishlist' => $bookViewModel->isInWishlist(),
            'finalPrice' => $bookViewModel->getFinalPrice(),
            'formattedPrice' => $bookViewModel->getFormattedPrice(),
            'formattedFinalPrice' => $bookViewModel->getFormattedFinalPrice(),
            'hasDiscount' => $bookViewModel->hasDiscount(),
            'discountPercent' => $bookViewModel->getDiscountPercent(),
            'isAvailable' => $bookViewModel->isAvailable(),
        ]);
    }
}
