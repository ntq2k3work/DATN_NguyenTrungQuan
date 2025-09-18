<?php

namespace App\Livewire;

use App\ViewModels\HeaderViewModel;
use Livewire\Component;

class HeaderManager extends Component
{
    public $cartCount = 0;
    public $wishlistCount = 0;

    protected $listeners = [
        'cartCountUpdated' => 'updateCartCount',
        'wishlistCountUpdated' => 'updateWishlistCount',
        'cartUpdated' => 'loadCartCount',
        'wishlistUpdated' => 'loadWishlistCount',
        'userLoggedIn' => 'loadCounts',
        'userLoggedOut' => 'resetCounts'
    ];

    public function __construct()
    {
        // Prevent dependency injection issues by not calling parent constructor
    }

    public function mount()
    {
        $this->loadCounts();
    }

    public function loadCounts()
    {
        $headerViewModel = new HeaderViewModel();
        $this->cartCount = $headerViewModel->getCartCount();
        $this->wishlistCount = $headerViewModel->getWishlistCount();
    }

    public function loadCartCount()
    {
        $headerViewModel = new HeaderViewModel();
        $this->cartCount = $headerViewModel->getCartCount();
    }

    public function loadWishlistCount()
    {
        $headerViewModel = new HeaderViewModel();
        $this->wishlistCount = $headerViewModel->getWishlistCount();
    }

    public function updateCartCount($data)
    {
        $this->cartCount = $data['count'] ?? 0;
    }

    public function updateWishlistCount($data)
    {
        $this->wishlistCount = $data['count'] ?? 0;
    }

    public function resetCounts()
    {
        $this->cartCount = 0;
        $this->wishlistCount = 0;
    }

    public function render()
    {
        return view('livewire.header-manager', [
            'cartCount' => $this->cartCount,
            'wishlistCount' => $this->wishlistCount,
            'hasCartItems' => $this->cartCount > 0,
            'hasWishlistItems' => $this->wishlistCount > 0,
        ]);
    }
}
