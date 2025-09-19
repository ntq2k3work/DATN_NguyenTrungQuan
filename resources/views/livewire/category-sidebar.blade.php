<div>
    <div class="space-y-6">
        <!-- Categories -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Danh mục</h3>
            <div class="space-y-2">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}"
                       class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Publishers -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Nhà xuất bản</h3>
            <div wire:loading.class="opacity-50" wire:target="selectedPublishers" class="space-y-2 max-h-48 overflow-y-auto transition-opacity duration-200">
                @foreach($publishers as $publisher)
                    <label class="flex items-center">
                        <input type="checkbox"
                               wire:model.live="selectedPublishers"
                               value="{{ $publisher->id }}"
                               wire:loading.attr="disabled"
                               class="publisher-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500 disabled:opacity-50">
                        <span class="ml-2 text-sm text-gray-700">{{ $publisher->name }}</span>
                    </label>
                @endforeach
            </div>
            <div wire:loading wire:target="selectedPublishers" class="text-sm text-gray-500 mt-2">
                <svg class="animate-spin h-4 w-4 inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Đang lọc...
            </div>
        </div>

        <!-- Price Range -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Khoảng giá</h3>
            <div wire:loading.class="opacity-50" wire:target="selectedPriceRanges" class="space-y-2 transition-opacity duration-200">
                @php
                    $priceRanges = [
                        '0-50000' => 'Dưới 50.000đ',
                        '50000-100000' => '50.000đ - 100.000đ',
                        '100000-200000' => '100.000đ - 200.000đ',
                        '200000-500000' => '200.000đ - 500.000đ',
                        '500000+' => 'Trên 500.000đ'
                    ];
                @endphp

                @foreach($priceRanges as $value => $label)
                    <label class="flex items-center">
                        <input type="checkbox"
                               wire:model.live="selectedPriceRanges"
                               value="{{ $value }}"
                               wire:loading.attr="disabled"
                               class="price-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500 disabled:opacity-50">
                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <div wire:loading wire:target="selectedPriceRanges" class="text-sm text-gray-500 mt-2">
                <svg class="animate-spin h-4 w-4 inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Đang lọc...
            </div>
        </div>

        <!-- Custom Price Range -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Giá tùy chỉnh</h3>
            <div class="space-y-3">
                <div class="flex space-x-2">
                    <input type="number"
                           wire:model.live.debounce.500ms="customPriceMin"
                           placeholder="Từ"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                    <input type="number"
                           wire:model.live.debounce.500ms="customPriceMax"
                           placeholder="Đến"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
                <button wire:click="applyCustomPriceRange"
                        class="w-full px-4 py-2 bg-purple-600 text-white text-sm rounded-md hover:bg-purple-700 transition-colors">
                    Áp dụng
                </button>
            </div>
        </div>

        <!-- Clear Filters -->
        <div>
            <button wire:click="clearFilters"
                    class="w-full px-4 py-2 bg-gray-500 text-white text-sm rounded-md hover:bg-gray-600 transition-colors">
                Xóa bộ lọc
            </button>
        </div>
    </div>
</div>
