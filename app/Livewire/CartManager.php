<?php

namespace App\Livewire;

use App\ViewModels\CartViewModel;
use Livewire\Component;

class CartManager extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $cartCount = 0;
    public $loading = false;
    public $isEmpty = true;

    protected $listeners = ['cartUpdated' => 'loadCart', 'addToCart' => 'addToCart'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $cartViewModel = new CartViewModel();
        $this->cartItems = $cartViewModel->getCartItems();
        $this->total = $cartViewModel->getTotal();
        $this->cartCount = $cartViewModel->getCartCount();
        $this->loading = $cartViewModel->isLoading();
        $this->isEmpty = $cartViewModel->isEmpty();
    }

    public function addToCart($bookId)
    {
        $cartViewModel = new CartViewModel();
        $success = $cartViewModel->addToCart($bookId);

        if ($success) {
            $this->dispatch('show-toast', message: 'Đã thêm vào giỏ hàng!', type: 'success');
            $this->loadCart();
            $this->dispatch('cartCountUpdated', ['count' => $this->cartCount]);
        } else {
            $errors = $cartViewModel->getErrors();
            $this->dispatch('show-toast', message: $errors['auth'] ?? $errors['book'] ?? $errors['quantity'] ?? 'Có lỗi xảy ra', type: 'error');
        }
    }

    public function updateQuantity($bookId, $quantity)
    {
        $cartViewModel = new CartViewModel();
        $success = $cartViewModel->updateQuantity($bookId, $quantity);

        if ($success) {
            $this->loadCart();
            // $this->dispatch('show-toast', message: 'Đã thêm vào giỏ hàng!', type: 'success');

            $this->dispatch('cartCountUpdated', ['count' => $this->cartCount]);
        } else {
            $errors = $cartViewModel->getErrors();
            // $this->dispatch('show-toast', message: $errors['quantity'] ?? 'Có lỗi xảy ra', type: 'warning');
        }
    }

    public function removeFromCart($bookId)
    {
        $cartViewModel = new CartViewModel();
        $success = $cartViewModel->removeFromCart($bookId);

        if ($success) {
            $this->loadCart();
            $this->dispatch('cartCountUpdated', ['count' => $this->cartCount]);
            $this->dispatch('show-toast', message: 'Đã xóa sản phẩm khỏi giỏ hàng', type: 'success');
        }
    }

    public function getCartCount()
    {
        $this->dispatch('cartCountUpdated', count: $this->cartCount);
    }

    public function render()
    {
        return view('livewire.cart-manager', [
            'cartItems' => $this->cartItems,
            'total' => $this->total,
            'cartCount' => $this->cartCount,
            'loading' => $this->loading,
            'isEmpty' => $this->isEmpty,
        ]);
    }
}
