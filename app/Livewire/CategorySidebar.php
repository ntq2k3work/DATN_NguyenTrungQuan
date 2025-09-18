<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Publisher;
use Livewire\Component;

class CategorySidebar extends Component
{
    public $selectedPublishers = [];
    public $selectedPriceRanges = [];
    public $customPriceMin = '';
    public $customPriceMax = '';

    protected $listeners = ['clearFilters' => 'clearFilters'];

    public function mount()
    {
        // Initialize from URL parameters
        $this->selectedPublishers = request()->get('publishers', []);
        $this->selectedPriceRanges = request()->get('price_ranges', []);
        $this->customPriceMin = request()->get('custom_price_min', '');
        $this->customPriceMax = request()->get('custom_price_max', '');
    }

    public function updatedSelectedPublishers()
    {
        $this->dispatch('filtersUpdated', [
            'publishers' => $this->selectedPublishers,
            'priceRanges' => $this->selectedPriceRanges,
            'customPriceMin' => $this->customPriceMin,
            'customPriceMax' => $this->customPriceMax,
        ]);
    }

    public function updatedSelectedPriceRanges()
    {
        $this->dispatch('filtersUpdated', [
            'publishers' => $this->selectedPublishers,
            'priceRanges' => $this->selectedPriceRanges,
            'customPriceMin' => $this->customPriceMin,
            'customPriceMax' => $this->customPriceMax,
        ]);
    }

    public function updatedCustomPriceMin()
    {
        $this->dispatch('filtersUpdated', [
            'publishers' => $this->selectedPublishers,
            'priceRanges' => $this->selectedPriceRanges,
            'customPriceMin' => $this->customPriceMin,
            'customPriceMax' => $this->customPriceMax,
        ]);
    }

    public function updatedCustomPriceMax()
    {
        $this->dispatch('filtersUpdated', [
            'publishers' => $this->selectedPublishers,
            'priceRanges' => $this->selectedPriceRanges,
            'customPriceMin' => $this->customPriceMin,
            'customPriceMax' => $this->customPriceMax,
        ]);
    }

    public function applyCustomPriceRange()
    {
        $this->dispatch('filtersUpdated', [
            'publishers' => $this->selectedPublishers,
            'priceRanges' => $this->selectedPriceRanges,
            'customPriceMin' => $this->customPriceMin,
            'customPriceMax' => $this->customPriceMax,
        ]);
    }

    public function clearFilters()
    {
        $this->selectedPublishers = [];
        $this->selectedPriceRanges = [];
        $this->customPriceMin = '';
        $this->customPriceMax = '';

        $this->dispatch('filtersUpdated', [
            'publishers' => [],
            'priceRanges' => [],
            'customPriceMin' => '',
            'customPriceMax' => '',
        ]);
    }

    public function render()
    {
        $publishers = Publisher::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('livewire.category-sidebar', [
            'publishers' => $publishers,
            'categories' => $categories,
        ]);
    }
}
