<div class="flex items-center space-x-6">
    <!-- Wishlist -->
    <div
        class="relative flex items-center cursor-pointer"
        tabindex="0"
        aria-label="Xem danh sách yêu thích"
        onclick="window.location.href='{{ route('wishlist.index') }}'"
        onkeydown="if(event.key==='Enter'||event.key===' '){window.location.href='{{ route('wishlist.index') }}'}"
        role="button"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        @if($wishlistCount > 0)
            <span class="wishlist-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1 min-w-[18px] h-[18px] flex items-center justify-center">{{ $wishlistCount }}</span>
        @endif
        <span class="ml-2 text-sm">Yêu thích</span>
    </div>

    <!-- Giỏ hàng -->
    <div
        class="relative flex items-center cursor-pointer"
        tabindex="0"
        aria-label="Xem giỏ hàng"
        onclick="window.location.href='{{ route('cart.show') }}'"
        onkeydown="if(event.key==='Enter'||event.key===' '){window.location.href='{{ route('cart.show') }}'}"
        role="button"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9m-6-9v9" />
        </svg>
        @if($cartCount > 0)
            <span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1 min-w-[18px] h-[18px] flex items-center justify-center">{{ $cartCount }}</span>
        @endif
        <span class="ml-2 text-sm">Giỏ hàng</span>
    </div>
</div>
