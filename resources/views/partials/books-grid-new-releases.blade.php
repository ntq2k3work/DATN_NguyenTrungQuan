@foreach ($books as $book)
<div class="group hover:shadow-lg transition-shadow duration-300 bg-amber-50 rounded-lg overflow-hidden">
    <div class="p-3 sm:p-4">
        <div class="relative mb-3 sm:mb-4">
            <a href="{{ route('product.show', $book->slug) }}">
                <img
                    src="{{ asset($book->image_url) }}"
                    alt="{{ $book->title }}"
                    class="w-full h-[200px] sm:h-[220px] lg:h-[280px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300"
                />
            </a>
            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">New</span>
            <x-wishlist-button :book="$book" :in-wishlist="false" size="sm" />
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
                        class="bg-amber-600 hover:bg-amber-500 text-white px-3 sm:px-4 py-2 rounded text-sm sm:text-base transition-colors"
                        onclick="addToCart({{ $book->id }}, this)"
                        data-book-id="{{ $book->id }}"
                    >
                        Thêm vào giỏ
                    </button>
                    <button class="bg-primary text-white px-3 sm:px-4 py-2 rounded hover:bg-primary/90 text-sm sm:text-base">Mua</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
