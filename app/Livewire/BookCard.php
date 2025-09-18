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
    public $cardStyle = 'default'; // default, horizontal, compact

    protected $listeners = ['cartUpdated', 'wishlistUpdated'];

    public function mount($book, $showWishlistButton = true, $showAddToCartButton = true, $cardStyle = 'default')
    {
        $this->bookId = $book->id;
        $this->bookData = $book->toArray();
        $this->showWishlistButton = $showWishlistButton;
        $this->showAddToCartButton = $showAddToCartButton;
        $this->cardStyle = $cardStyle;
    }

    public function addToCart()
    {
        $this->dispatch('addToCart', bookId: $this->bookId);
    }

    public function toggleWishlist()
    {
        $this->dispatch('toggleWishlist', bookId: $this->bookId);
    }

    public function isInWishlist()
    {
        $bookViewModel = new BookViewModel(Book::find($this->bookId));
        return $bookViewModel->isInWishlist();
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
