@props(['book', 'inWishlist' => false, 'size' => 'sm'])

@php
    $sizeClasses = [
        'sm' => 'w-3 h-3 sm:w-4 sm:h-4',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5'
    ];
    
    $buttonSizeClasses = [
        'sm' => 'p-1.5 sm:p-2',
        'md' => 'p-2',
        'lg' => 'p-3'
    ];
@endphp

<button 
    class="wishlist-btn absolute top-2 right-2 bg-background/80 hover:bg-background {{ $buttonSizeClasses[$size] }} rounded-full opacity-0 group-hover:opacity-100 transition-opacity {{ $inWishlist ? 'opacity-100 bg-red-50 hover:bg-red-100' : '' }}"
    onclick="toggleWishlist({{ $book->id }})"
    data-book-id="{{ $book->id }}"
    data-in-wishlist="{{ $inWishlist ? 'true' : 'false' }}"
    title="{{ $inWishlist ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
>
    <svg class="{{ $sizeClasses[$size] }} {{ $inWishlist ? 'fill-current text-red-600' : 'stroke-current text-gray-600 hover:text-red-600' }}" 
         fill="{{ $inWishlist ? 'currentColor' : 'none' }}" 
         stroke="{{ $inWishlist ? 'none' : 'currentColor' }}" 
         viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </svg>
</button>
