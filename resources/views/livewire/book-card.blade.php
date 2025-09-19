<div class="group {{ $cardStyle === 'horizontal' ? 'flex items-center space-x-4' : '' }} {{ $cardStyle === 'compact' ? 'flex items-start space-x-3' : '' }} hover:shadow-lg transition-shadow duration-300 bg-amber-50 rounded-lg overflow-hidden cursor-pointer"
     onclick="window.location.href='{{ route('product.show', $bookData['slug']) }}'">

    @if($cardStyle === 'default')
        <div class="p-4">
            <div class="relative mb-4">
                <img src="{{ asset($bookData['image_url']) }}"
                     alt="{{ $bookData['title'] }}"
                     class="w-full h-[280px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300">

                @if($showWishlistButton)
                    <button
                        wire:click.stop="toggleWishlist"
                        class="absolute top-2 right-2 bg-white/80 hover:bg-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity {{ $this->isInWishlist() ? 'opacity-100 bg-red-50 hover:bg-red-100' : '' }}"
                        title="{{ $this->isInWishlist() ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                    >
                        <svg class="w-4 h-4 {{ $this->isInWishlist() ? 'fill-current text-red-600' : 'stroke-current text-gray-600 hover:text-red-600' }}"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                @endif
            </div>

            <div class="space-y-3">
                <div>
                    <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors">
                        {{ $bookData['title'] }}
                    </h3>
                    <p class="text-sm text-gray-500">{{ $bookData['author']['name'] ?? 'Unknown' ?? 'Unknown' }}</p>
                </div>

                <div class="flex items-center gap-2">
                    <div class="flex items-center text-blue-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-sm text-gray-500">Số lượng: {{ $bookData['quantity'] ?? 0 }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if ($bookData['discount_percent'] ?? 0)
                            <span class="text-lg font-bold text-primary">{{ number_format($bookData['discount_price'] ?? $bookData['price'], 0, ',', '.') }}đ</span>
                            <span class="text-sm text-gray-500 line-through">{{ number_format($bookData['price'], 0, ',', '.') }}đ</span>
                        @else
                            <span class="text-lg font-bold text-primary">{{ number_format($bookData['price'], 0, ',', '.') }}đ</span>
                        @endif
                    </div>
                </div>

                @if($showAddToCartButton)
                    <button
                        wire:click.stop="addToCart"
                        data-book-id="{{ $bookData['id'] }}"
                        data-component-id="{{ $componentId }}"
                        class="w-full bg-amber-600 text-white text-sm py-2 px-4 rounded hover:bg-amber-500 cursor-pointer transition add-to-cart-btn {{ $addingToCart ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $addingToCart ? 'disabled' : '' }}
                    >
                        {{ $addingToCart ? 'Đang thêm...' : 'Thêm vào giỏ' }}
                    </button>
                @endif
            </div>
        </div>
    @elseif($cardStyle === 'horizontal')
        <div class="p-6">
            <div class="flex gap-4">
                <div class="relative flex-shrink-0">
                    <img src="{{ asset($bookData['image_url']) }}"
                         alt="{{ $bookData['title'] }}"
                         class="w-24 h-32 object-cover rounded-md group-hover:scale-105 transition-transform duration-300">
                    @if($bookData['discount_percent'] ?? 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                            {{ $bookData['discount_percent'] ?? 0 }}%
                        </span>
                    @endif
                </div>
                <div class="flex-1 space-y-3">
                    <div>
                        <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors">
                            {{ $bookData['title'] }}
                        </h3>
                        <p class="text-sm text-muted-foreground">{{ $bookData['author']['name'] ?? 'Unknown' ?? 'Unknown' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center text-blue-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="text-sm text-muted-foreground">Số lượng: {{ $bookData['quantity'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-lg font-bold text-orange-500">{{ number_format($bookData['final_price'] ?? $bookData['price'], 0, ',', '.') }}đ</span>
                            @if($bookData['discount_percent'] ?? 0)
                                <span class="text-sm text-muted-foreground line-through text-teal-600">{{ number_format($bookData['price'], 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            @if($showWishlistButton)
                                <button
                                    wire:click.stop="toggleWishlist"
                                    class="border border-gray-300 p-2 rounded hover:bg-gray-100 {{ $this->isInWishlist() ? 'bg-red-50 hover:bg-red-100' : '' }}"
                                    title="{{ $this->isInWishlist() ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                                >
                                    <svg class="w-4 h-4 {{ $this->isInWishlist() ? 'fill-current text-red-600' : 'stroke-current text-gray-600 hover:text-red-600' }}"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            @endif
                            @if($showAddToCartButton)
                                <button
                                    wire:click.stop="addToCart"
                                    data-book-id="{{ $bookData['id'] }}"
                                    data-component-id="{{ $componentId }}"
                                    class="bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-500 cursor-pointer add-to-cart-btn {{ $addingToCart ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $addingToCart ? 'disabled' : '' }}
                                >
                                    {{ $addingToCart ? 'Đang thêm...' : 'Thêm vào giỏ' }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($cardStyle === 'compact')
        <div class="p-4">
            <div class="relative mb-4">
                <img src="{{ asset($bookData['image_url']) }}"
                     alt="{{ $bookData['title'] }}"
                     class="w-full h-[280px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300" />
                <div class="absolute top-2 left-2 flex flex-col gap-2">
                    <span class="bg-emerald-500 text-white px-2 py-1 text-xs rounded">New</span>
                </div>
                @if($showWishlistButton)
                    <button
                        wire:click.stop="toggleWishlist"
                        class="absolute top-2 right-2 bg-background/80 hover:bg-background p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity {{ $this->isInWishlist() ? 'opacity-100 bg-red-50 hover:bg-red-100' : '' }}"
                        title="{{ $this->isInWishlist() ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                    >
                        <svg class="w-4 h-4 {{ $this->isInWishlist() ? 'fill-current text-red-600' : 'stroke-current text-gray-600 hover:text-red-600' }}"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                @endif
            </div>

            <div class="space-y-3">
                <div>
                    <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors">
                        {{ $bookData['title'] }}
                    </h3>
                    <p class="text-sm text-muted-foreground">{{ $bookData['author']['name'] ?? 'Unknown' ?? 'Unknown' }}</p>
                </div>

                <div class="flex items-center gap-2">
                    <div class="flex items-center text-blue-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-sm text-muted-foreground">Số lượng: {{ $bookData['quantity'] ?? 0 }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-lg font-bold text-primary text-orange-500">{{ number_format($bookData['final_price'] ?? $bookData['price'], 0, ',', '.') }}đ</span>
                        @if($bookData['discount_percent'] ?? 0)
                            <span class="text-sm text-muted-foreground line-through text-teal-600">{{ number_format($bookData['price'], 0, ',', '.') }}đ</span>
                        @endif
                    </div>
                </div>

                @if($showAddToCartButton)
                    <button
                        wire:click.stop="addToCart"
                        data-book-id="{{ $bookData['id'] }}"
                        data-component-id="{{ $componentId }}"
                        class="w-full px-4 py-2 rounded bg-amber-600 text-white hover:bg-amber-500 cursor-pointer text-sm add-to-cart-btn {{ $addingToCart ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $addingToCart ? 'disabled' : '' }}
                    >
                        {{ $addingToCart ? 'Đang thêm...' : 'Thêm vào giỏ' }}
                    </button>
                @endif
            </div>
        </div>
    @elseif($cardStyle === 'best-seller')
        <div class="p-4">
            <div class="relative mb-4">
                <img src="{{ asset($bookData['image_url']) }}"
                     alt="{{ $bookData['title'] }}"
                     class="w-full h-[280px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300">

                @if(($bookData['discount_percent'] ?? 0) > 0 || ($bookData['discount_amount'] ?? 0) > 0)
                    <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                        {{ ($bookData['discount_percent'] ?? 0) > 0 ? $bookData['discount_percent'].'%' : (($bookData['discount_amount'] ?? 0) > 0 ? $bookData['discount_amount'].'đ' : '') }}
                    </span>
                @endif

                @if($showWishlistButton)
                    <button
                        wire:click.stop="toggleWishlist"
                        class="absolute top-2 right-2 bg-white/80 hover:bg-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity {{ $this->isInWishlist() ? 'opacity-100 bg-red-50 hover:bg-red-100' : '' }}"
                        title="{{ $this->isInWishlist() ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                    >
                        <svg class="w-4 h-4 {{ $this->isInWishlist() ? 'fill-current text-red-600' : 'stroke-current text-gray-600 hover:text-red-600' }}"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                @endif
            </div>

            <div class="space-y-3">
                <div>
                    <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors">
                        {{ $bookData['title'] }}
                    </h3>
                    <p class="text-sm text-gray-500">{{ $bookData['author']['name'] ?? 'Unknown' }}</p>
                </div>

                <div class="flex items-center gap-2">
                    <div class="flex items-center text-blue-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-sm text-gray-500">Số lượng: {{ $bookData['quantity'] ?? 0 }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-lg font-bold text-orange-500">{{ number_format($bookData['final_price'] ?? $bookData['price'], 0, ',', '.') }}đ</span>
                        @if($bookData['discount_percent'] ?? 0)
                            <span class="text-sm text-gray-500 line-through text-teal-600">{{ number_format($bookData['price'], 0, ',', '.') }}đ</span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        
                        @if($showAddToCartButton)
                            <button
                                wire:click.stop="addToCart"
                                data-book-id="{{ $bookData['id'] }}"
                                data-component-id="{{ $componentId }}"
                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-primary/90 cursor-pointer add-to-cart-btn {{ $addingToCart ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $addingToCart ? 'disabled' : '' }}
                            >
                                {{ $addingToCart ? 'Đang thêm...' : 'Thêm vào giỏ hàng' }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @elseif($cardStyle === 'new-release')
        <div class="p-4">
            <div class="relative mb-4">
                <img src="{{ asset($bookData['image_url']) }}"
                     alt="{{ $bookData['title'] }}"
                     class="w-full h-[280px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300">

                <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">New</span>

                @if($showWishlistButton)
                    <button
                        wire:click.stop="toggleWishlist"
                        class="absolute top-2 right-2 bg-background/80 hover:bg-background p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity {{ $this->isInWishlist() ? 'opacity-100 bg-red-50 hover:bg-red-100' : '' }}"
                        title="{{ $this->isInWishlist() ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                    >
                        <svg class="w-4 h-4 {{ $this->isInWishlist() ? 'fill-current text-red-600' : 'stroke-current text-gray-600 hover:text-red-600' }}"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                @endif
            </div>

            <div class="space-y-3">
                <div>
                    <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors">
                        {{ $bookData['title'] }}
                    </h3>
                    <p class="text-sm text-gray-500">{{ $bookData['author']['name'] ?? 'Unknown' }}</p>
                </div>

                <div class="flex items-center gap-2">
                    <div class="flex items-center text-blue-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-sm text-gray-500">Số lượng: {{ $bookData['quantity'] ?? 0 }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-lg font-bold text-orange-500">{{ number_format($bookData['final_price'] ?? $bookData['price'], 0, ',', '.') }}đ</span>
                        @if($bookData['discount_percent'] ?? 0)
                            <span class="text-sm text-gray-500 line-through text-teal-600">{{ number_format($bookData['price'], 0, ',', '.') }}đ</span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        @if($showAddToCartButton)
                            <button
                                wire:click.stop="addToCart"
                                data-book-id="{{ $bookData['id'] }}"
                                data-component-id="{{ $componentId }}"
                                class="bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded cursor-pointer text-sm add-to-cart-btn {{ $addingToCart ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $addingToCart ? 'disabled' : '' }}
                            >
                                {{ $addingToCart ? 'Đang thêm...' : 'Thêm vào giỏ' }}
                            </button>
                        @endif
                        <button class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90 text-sm">
                            Mua
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @elseif($cardStyle === 'top-selling')
        <div class="p-4 relative">
            <!-- Rank Badge -->
            @if($rank)
                <div class="absolute -top-2 -left-2 z-10">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm
                        {{ $rank <= 3 ? 'bg-yellow-500' : 'bg-gray-500' }}">
                        {{ $rank }}
                    </div>
                </div>
            @endif

            <div class="relative mb-4">
                <img src="{{ asset($bookData['image_url']) }}"
                     alt="{{ $bookData['title'] }}"
                     class="w-full h-[240px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300">

                @if($showWishlistButton)
                    <button
                        wire:click.stop="toggleWishlist"
                        class="absolute top-2 right-2 bg-background/80 hover:bg-background p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity {{ $this->isInWishlist() ? 'opacity-100 bg-red-50 hover:bg-red-100' : '' }}"
                        title="{{ $this->isInWishlist() ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                    >
                        <svg class="w-4 h-4 {{ $this->isInWishlist() ? 'fill-current text-red-600' : 'stroke-current text-gray-600 hover:text-red-600' }}"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                @endif
            </div>

            <div class="space-y-3">
                <div>
                    <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors">
                        {{ $bookData['title'] }}
                    </h3>
                    <p class="text-sm text-gray-500">{{ $bookData['author']['name'] ?? 'Unknown' }}</p>
                </div>

                <div class="flex items-center gap-1">
                    <span class="inline-block bg-orange-400 text-white text-xs px-2 py-1 rounded">
                        Đã bán {{ $bookData['sales_count'] ?? rand(100, 2000) }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-lg font-bold text-yellow-600">{{ number_format($bookData['final_price'] ?? $bookData['price'], 0, ',', '.') }}đ</span>
                        @if($bookData['discount_percent'] ?? 0)
                            <span class="text-sm text-teal-600 line-through">{{ number_format($bookData['price'], 0, ',', '.') }}đ</span>
                        @endif
                    </div>
                </div>

                @if($showAddToCartButton)
                    <button
                        wire:click.stop="addToCart"
                        data-book-id="{{ $bookData['id'] }}"
                        data-component-id="{{ $componentId }}"
                        class="w-full bg-amber-600 text-white font-medium py-2 px-4 rounded hover:bg-primary/90 cursor-pointer text-sm add-to-cart-btn {{ $addingToCart ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $addingToCart ? 'disabled' : '' }}
                    >
                        {{ $addingToCart ? 'Đang thêm...' : 'Mua ngay' }}
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
