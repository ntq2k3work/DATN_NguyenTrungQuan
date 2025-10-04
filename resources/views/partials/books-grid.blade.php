@forelse ($books as $book)
<div class="group hover:shadow-lg transition-shadow duration-300 bg-amber-50 rounded-lg overflow-hidden">
    <div class="p-3 sm:p-4">
        <div class="relative mb-3 sm:mb-4">
            <a href="{{ route('product.show', $book->slug) }}">
                <img
                    src="{{ asset($book->image_url ? (str_starts_with($book->image_url, 'http') ? $book->image_url : '/storage/' . ltrim($book->image_url, '/')) : '/images/placeholder.jpg') }}"
                    alt="{{ $book->title }}"
                    class="w-full h-[200px] sm:h-[220px] lg:h-[280px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300"
                />
            </a>
            <span class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">{{ $category->name }}</span>
            <button
                class="wishlist-btn absolute top-2 right-2 bg-background/80 hover:bg-background p-1.5 sm:p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity {{ ($book->in_wishlist ?? false) ? 'opacity-100 bg-red-50 hover:bg-red-100' : '' }}"
                onclick="toggleWishlist({{ $book->id }})"
                data-book-id="{{ $book->id }}"
                data-in-wishlist="{{ ($book->in_wishlist ?? false) ? 'true' : 'false' }}"
                title="{{ ($book->in_wishlist ?? false) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
            >
                <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ ($book->in_wishlist ?? false) ? 'fill-current text-red-600' : 'stroke-current text-gray-600 hover:text-red-600' }}"
                     fill="{{ ($book->in_wishlist ?? false) ? 'currentColor' : 'none' }}"
                     stroke="{{ ($book->in_wishlist ?? false) ? 'none' : 'currentColor' }}"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
        </div>

        <div class="space-y-2 sm:space-y-3">
            <div>
                            <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors text-sm sm:text-base">
                <a href="{{ route('product.show', $book->slug) }}" class="hover:text-primary transition-colors">
                    {{ $book->title }}
                </a>
            </h3>
                <p class="text-xs sm:text-sm text-muted-foreground">{{ $book->author?->name ?? 'Unknown Author' }}</p>
            </div>

            <div class="flex items-center gap-2">
                <div class="flex items-center text-blue-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="text-sm text-muted-foreground">Số lượng: {{ $book->quantity ?? 0 }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="text-base sm:text-lg font-bold text-orange-500">{{ number_format($book->final_price ?? $book->price, 0, ',', '.') }}đ</span>
                    @if(isset($book->discount_percent) && $book->discount_percent > 0)
                    <span class="text-xs sm:text-sm text-muted-foreground line-through text-teal-600">{{ number_format($book->price, 0, ',', '.') }}đ</span>
                    @endif
                </div>
                <div class="flex gap-1 sm:gap-2">
                    <button
                        class="wishlist-btn border border-gray-300 p-1.5 sm:p-2 rounded hover:bg-gray-100 hover:border-red-500 transition-colors {{ ($book->in_wishlist ?? false) ? 'bg-red-50 border-red-500' : '' }}"
                        onclick="toggleWishlist({{ $book->id }})"
                        data-book-id="{{ $book->id }}"
                        data-in-wishlist="{{ ($book->in_wishlist ?? false) ? 'true' : 'false' }}"
                        title="{{ ($book->in_wishlist ?? false) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                    >
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ ($book->in_wishlist ?? false) ? 'fill-current text-red-600' : 'stroke-current text-gray-600 hover:text-red-600' }}"
                             fill="{{ ($book->in_wishlist ?? false) ? 'currentColor' : 'none' }}"
                             stroke="{{ ($book->in_wishlist ?? false) ? 'none' : 'currentColor' }}"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <button
                        class="bg-primary text-white px-3 sm:px-4 py-2 rounded hover:bg-primary/90 text-sm sm:text-base transition-colors"
                        onclick="buyNow({{ $book->id }})"
                        data-book-id="{{ $book->id }}"
                    >
                        Mua ngay
                    </button>
                    <button
                        class="bg-amber-600 text-white px-3 sm:px-4 py-2 rounded hover:bg-amber-700 text-sm sm:text-base transition-colors add-to-cart-btn"
                        onclick="addToCart({{ $book->id }})"
                        data-book-id="{{ $book->id }}"
                    >
                        Thêm vào giỏ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@empty
<div class="col-span-full text-center py-8 sm:py-12">
    <div class="text-gray-500 text-base sm:text-lg">
        <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.477-4.5-1.253" />
        </svg>
        <p>Không tìm thấy sách nào phù hợp với bộ lọc</p>
    </div>
</div>
@endforelse
