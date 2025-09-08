<div>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Chi tiết sản phẩm</h1>
        
        @if($book)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Image -->
                <div class="space-y-4">
                    <div class="aspect-w-3 aspect-h-4">
                        <img src="{{ $book->image_url }}" 
                             alt="{{ $book->title }}" 
                             class="w-full h-full object-cover rounded-lg shadow-lg">
                    </div>
                </div>
                
                <!-- Product Details -->
                <div class="space-y-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $book->title }}</h1>
                        <p class="text-lg text-gray-600 mt-2">Tác giả: {{ $book->author->name }}</p>
                        <p class="text-sm text-gray-500">Nhà xuất bản: {{ $book->publisher->name }}</p>
                    </div>
                    
                    <!-- Price -->
                    <div class="space-y-2">
                        @if($this->hasDiscount())
                            <div class="flex items-center space-x-3">
                                <span class="text-3xl font-bold text-red-600">
                                    {{ number_format($this->discountedPrice, 0, ',', '.') }}đ
                                </span>
                                <span class="text-lg text-gray-500 line-through">
                                    {{ number_format($book->price, 0, ',', '.') }}đ
                                </span>
                                <span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                    -{{ $this->discountPercent }}%
                                </span>
                            </div>
                        @else
                            <span class="text-3xl font-bold text-gray-900">
                                {{ number_format($book->price, 0, ',', '.') }}đ
                            </span>
                        @endif
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Mô tả sản phẩm</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $book->description }}</p>
                    </div>
                    
                    <!-- Book Details -->
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-600">Danh mục:</span>
                            <span class="text-gray-900">{{ $book->category->name }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Số trang:</span>
                            <span class="text-gray-900">{{ $book->pages }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Ngôn ngữ:</span>
                            <span class="text-gray-900">{{ $book->language }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Tồn kho:</span>
                            <span class="text-gray-900">{{ $book->quantity }} cuốn</span>
                        </div>
                    </div>
                    
                    <!-- Quantity and Actions -->
                    <div class="space-y-4">
                        <!-- Quantity Selector -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng:</label>
                            <div class="flex items-center space-x-3">
                                <button wire:click="decrementQuantity"
                                        class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                
                                <input type="number" 
                                       wire:model="quantity" 
                                       min="1" 
                                       max="{{ $book->quantity }}"
                                       class="w-20 text-center border border-gray-300 rounded-lg px-3 py-2">
                                
                                <button wire:click="incrementQuantity"
                                        class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex space-x-4">
                            <button wire:click="addToCart"
                                    wire:loading.attr="disabled"
                                    wire:target="addToCart"
                                    class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <div wire:loading wire:target="addToCart" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                                <svg wire:loading.remove wire:target="addToCart" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                </svg>
                                <span wire:loading.remove wire:target="addToCart">Thêm vào giỏ hàng</span>
                                <span wire:loading wire:target="addToCart">Đang thêm...</span>
                            </button>
                            
                            <button wire:click="buyNow"
                                    wire:loading.attr="disabled"
                                    wire:target="buyNow"
                                    class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <div wire:loading wire:target="buyNow" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                                <svg wire:loading.remove wire:target="buyNow" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="buyNow">Mua ngay</span>
                                <span wire:loading wire:target="buyNow">Đang xử lý...</span>
                            </button>
                        </div>
                        
                        <!-- Wishlist Button -->
                        <div class="flex justify-center">
                            <button wire:click="toggleWishlist"
                                    class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 {{ $inWishlist ? 'text-red-600 border-red-300 bg-red-50' : 'text-gray-600' }}">
                                <svg class="w-5 h-5 {{ $inWishlist ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>{{ $inWishlist ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Books -->
            @if($relatedBooks->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl font-bold mb-8">Sách liên quan</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedBooks as $relatedBook)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                                <a href="{{ route('product.show', $relatedBook->slug) }}">
                                    <img src="{{ $relatedBook->image_url }}" 
                                         alt="{{ $relatedBook->title }}" 
                                         class="w-full h-48 object-cover">
                                </a>
                                
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                        <a href="{{ route('product.show', $relatedBook->slug) }}" 
                                           class="hover:text-blue-600">
                                            {{ $relatedBook->title }}
                                        </a>
                                    </h3>
                                    
                                    <p class="text-sm text-gray-600 mb-2">{{ $relatedBook->author->name }}</p>
                                    
                                    <div class="flex items-center justify-between">
                                        @if($relatedBook->discount_percent > 0)
                                            <div class="flex items-center space-x-2">
                                                <span class="text-lg font-bold text-red-600">
                                                    {{ number_format($relatedBook->final_price, 0, ',', '.') }}đ
                                                </span>
                                                <span class="text-sm text-gray-500 line-through">
                                                    {{ number_format($relatedBook->price, 0, ',', '.') }}đ
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">
                                                {{ number_format($relatedBook->price, 0, ',', '.') }}đ
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <h2 class="text-2xl font-semibold text-gray-900">Không tìm thấy sản phẩm</h2>
                <p class="mt-2 text-gray-600">Sản phẩm bạn đang tìm kiếm không tồn tại.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Về trang chủ
                    </a>
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
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
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
</div>
