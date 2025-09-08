<div>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">
            @if($pageType === 'show' && $category)
                {{ $category->name }}
            @elseif($pageType === 'best-sellers')
                Sách bán chạy
            @elseif($pageType === 'new-releases')
                Sách mới phát hành
            @elseif($pageType === 'recommendations')
                Sách đề xuất
            @elseif($pageType === 'top-selling')
                Sách bán chạy nhất
            @else
                Tất cả sách
            @endif
        </h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-lg font-semibold mb-4">Bộ lọc</h2>
                    
                    <!-- Categories Filter -->
                    @if($pageType !== 'show')
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Danh mục</h3>
                            <div class="space-y-2">
                                @foreach($categories as $cat)
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               wire:model="selectedCategories" 
                                               value="{{ $cat->id }}"
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">{{ $cat->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Publishers Filter -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Nhà xuất bản</h3>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            @foreach($publishers as $publisher)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           wire:model="selectedPublishers" 
                                           value="{{ $publisher->id }}"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">{{ $publisher->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Price Range Filter -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Khoảng giá</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       wire:model="selectedPriceRanges" 
                                       value="0-100000"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Dưới 100.000đ</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       wire:model="selectedPriceRanges" 
                                       value="100000-200000"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">100.000đ - 200.000đ</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       wire:model="selectedPriceRanges" 
                                       value="200000-300000"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">200.000đ - 300.000đ</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       wire:model="selectedPriceRanges" 
                                       value="300000-500000"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">300.000đ - 500.000đ</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       wire:model="selectedPriceRanges" 
                                       value="500000-999999999"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Trên 500.000đ</span>
                            </label>
                        </div>
                        
                        <!-- Custom Price Range -->
                        <div class="mt-4">
                            <h4 class="text-xs font-medium text-gray-600 mb-2">Khoảng giá tùy chỉnh</h4>
                            <div class="flex space-x-2">
                                <input type="number" 
                                       wire:model="customPriceMin" 
                                       placeholder="Từ"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <input type="number" 
                                       wire:model="customPriceMax" 
                                       placeholder="Đến"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Clear Filters -->
                    <button wire:click="clearFilters"
                            class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition duration-200 text-sm">
                        Xóa bộ lọc
                    </button>
                </div>
            </div>
            
            <!-- Books Grid -->
            <div class="lg:col-span-3">
                <!-- Sort and Results Info -->
                <div class="flex justify-between items-center mb-6">
                    <div class="text-sm text-gray-600">
                        Hiển thị {{ $books->count() }} sản phẩm
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-gray-700">Sắp xếp:</label>
                        <select wire:model="sortBy" 
                                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="price_asc">Giá tăng dần</option>
                            <option value="price_desc">Giá giảm dần</option>
                            <option value="name_asc">Tên A-Z</option>
                            <option value="name_desc">Tên Z-A</option>
                        </select>
                    </div>
                </div>
                
                <!-- Books Grid -->
                @if($books->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($books as $book)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                                <a href="{{ route('product.show', $book->slug) }}">
                                    <img src="{{ $book->image_url }}" 
                                         alt="{{ $book->title }}" 
                                         class="w-full h-48 object-cover">
                                </a>
                                
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                        <a href="{{ route('product.show', $book->slug) }}" 
                                           class="hover:text-blue-600">
                                            {{ $book->title }}
                                        </a>
                                    </h3>
                                    
                                    <p class="text-sm text-gray-600 mb-2">{{ $book->author->name }}</p>
                                    <p class="text-xs text-gray-500 mb-3">{{ $book->publisher->name }}</p>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-gray-900">
                                            {{ number_format($book->price, 0, ',', '.') }}đ
                                        </span>
                                        
                                        <div class="flex space-x-2">
                                            <button wire:click="addToCart({{ $book->id }})"
                                                    class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition duration-200 text-sm">
                                                Thêm vào giỏ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $books->links() }}
                    </div>
                @else
                    <!-- No Results -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h2 class="mt-4 text-2xl font-semibold text-gray-900">Không tìm thấy sản phẩm</h2>
                        <p class="mt-2 text-gray-600">Không có sản phẩm nào phù hợp với bộ lọc của bạn.</p>
                        <div class="mt-6">
                            <button wire:click="clearFilters"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Xóa bộ lọc
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    @if($showSuccessToast)
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-transform duration-300" 
             x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             x-init="setTimeout(() => show = false, 3000)">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ $toastMessage }}</span>
            </div>
        </div>
    @endif

    @if($showErrorToast)
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-transform duration-300" 
             x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             x-init="setTimeout(() => show = false, 3000)">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ $toastMessage }}</span>
            </div>
        </div>
    @endif
</div>

<!-- Toast Notifications -->
@if($showSuccessToast)
<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 3000)"
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed top-4 right-4 z-50 max-w-sm w-full bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium">{{ $toastMessage }}</p>
        </div>
        <div class="ml-4 flex-shrink-0">
            <button wire:click="hideToast" class="inline-flex text-white hover:text-gray-200">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>
</div>
@endif

@if($showErrorToast)
<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 3000)"
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed top-4 right-4 z-50 max-w-sm w-full bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium">{{ $toastMessage }}</p>
        </div>
        <div class="ml-4 flex-shrink-0">
            <button wire:click="hideToast" class="inline-flex text-white hover:text-gray-200">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>
</div>
@endif
