@extends('layouts.app', ['wishlistCount' => $wishlistItems->count(),'cartCount' => $cartCount])
@section('title', 'Danh sách yêu thích')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Danh sách yêu thích</h1>
                    <p class="text-gray-600">Các sản phẩm bạn đã thêm vào danh sách yêu thích</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">
                        {{ $wishlistItems->count() }} sản phẩm
                    </span>
                </div>
            </div>
        </div>

        @if($wishlistItems->count() > 0)
            <!-- Wishlist Items -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlistItems as $item)
                    @php
                        $book = $item->book;
                    @endphp
                    <div class="group bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <div class="p-4">
                            <div class="relative mb-4">
                                <a href="{{ route('product.show', $book->slug) }}">
                                    <img
                                        src="{{ asset($book->image_url) }}"
                                        alt="{{ $book->title }}"
                                        class="w-full h-[200px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300"
                                    />
                                </a>
                                @if($book->discount_percent > 0)
                                    <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                                        -{{ $book->discount_percent }}%
                                    </span>
                                @endif
                                
                                <!-- Remove from wishlist button -->
                                <button 
                                    class="absolute top-2 right-2 bg-white/80 hover:bg-white p-2 rounded-full shadow-md transition-colors"
                                    onclick="removeFromWishlist({{ $book->id }})"
                                    data-book-id="{{ $book->id }}"
                                    title="Xóa khỏi danh sách yêu thích"
                                >
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900 line-clamp-2 group-hover:text-red-600 transition-colors">
                                        <a href="{{ route('product.show', $book->slug) }}">
                                            {{ $book->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $book->author->name ?? 'Unknown Author' }}</p>
                                    @if($book->category)
                                        <span class="inline-block mt-2 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                                            {{ $book->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="flex items-center text-blue-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <span class="text-sm text-gray-500">Số lượng: {{ $book->quantity ?? 0 }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        @if($book->discount_percent > 0)
                                            <span class="text-lg font-bold text-red-600">
                                                {{ number_format($book->final_price, 0, ',', '.') }}₫
                                            </span>
                                            <span class="text-sm text-gray-500 line-through">
                                                {{ number_format($book->price, 0, ',', '.') }}₫
                                            </span>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">
                                                {{ number_format($book->price, 0, ',', '.') }}₫
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <button 
                                        onclick="addToCartFromWishlist({{ $book->id }})"
                                        class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors font-medium text-sm"
                                    >
                                        Thêm vào giỏ hàng
                                    </button>
                                    <a 
                                        href="{{ route('product.show', $book->slug) }}"
                                        class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm text-center"
                                    >
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty state for when all items are removed -->
            <div id="empty-wishlist" class="hidden text-center py-12">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Danh sách yêu thích trống</h3>
                    <p class="text-gray-500 mb-6">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                    <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Khám phá sản phẩm
                    </a>
                </div>
            </div>
        @else
            <!-- Empty state -->
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Danh sách yêu thích trống</h3>
                    <p class="text-gray-500 mb-6">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                    <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Khám phá sản phẩm
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Remove from wishlist function
function removeFromWishlist(bookId) {
    // Remove confirmation dialog - delete directly
    
    const button = document.querySelector(`[data-book-id="${bookId}"]`);
    if (button) {
        button.disabled = true;
        button.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    }

    fetch('/wishlist/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            book_id: bookId
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response from server:', data); // Debug log
        
        if (data.success) {
            // Find and remove the product card
            const productCard = document.querySelector(`[data-book-id="${bookId}"]`).closest('.group');
            if (productCard) {
                productCard.remove();
            }

            // Update count using response data
            const countElement = document.querySelector('.text-sm.text-gray-500');
            if (countElement && data.wishlist_count !== undefined) {
                countElement.textContent = `${data.wishlist_count} sản phẩm`;
            }

            // Update wishlist count in header
            if (window.WishlistManager && data.wishlist_count !== undefined) {
                window.WishlistManager.updateWishlistCount(data.wishlist_count);
            }

            // Show empty state if no items left
            const remainingCards = document.querySelectorAll('.group.bg-white');
            if (remainingCards.length === 0) {
                document.querySelector('#empty-wishlist').classList.remove('hidden');
            }

            // Show success message
            if (window.WishlistManager) {
                window.WishlistManager.showToast(data.message, 'success');
            } else {
                // Fallback toast
                showToast(data.message, 'success');
            }
        } else {
            console.log('Error response:', data); // Debug log
            if (window.WishlistManager) {
                window.WishlistManager.showToast(data.error || 'Có lỗi xảy ra', 'error');
            } else {
                // Fallback toast
                showToast(data.error || 'Có lỗi xảy ra', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.WishlistManager) {
            window.WishlistManager.showToast('Có lỗi xảy ra khi xóa sản phẩm', 'error');
        }
    })
    .finally(() => {
        // Re-enable button
        if (button) {
            button.disabled = false;
            button.innerHTML = '<svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>';
        }
    });
}

// Fallback toast function
function showToast(message, type = 'success') {
    // Create toast if it doesn't exist
    let toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50';
        toast.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span id="toast-message">${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
    }
    
    const toastMessage = document.getElementById('toast-message');
    toastMessage.textContent = message;
    
    // Change color based on type
    if (type === 'error') {
        toast.classList.remove('bg-green-500');
        toast.classList.add('bg-red-500');
    } else {
        toast.classList.remove('bg-red-500');
        toast.classList.add('bg-green-500');
    }
    
    // Show toast
    toast.classList.remove('translate-x-full');
    
    // Hide toast after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
    }, 3000);
}

// Update cart count function
function updateCartCount(count) {
    // Update cart count in header if exists
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

// Add to cart from wishlist
function addToCartFromWishlist(bookId) {
    const button = document.querySelector(`[onclick="addToCartFromWishlist(${bookId})"]`);
    const originalText = button.textContent;
    
    // Disable button and show loading
    button.disabled = true;
    button.textContent = 'Đang thêm...';
    button.classList.add('opacity-50');
    
    // Make API call
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            book_id: bookId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            if (window.WishlistManager) {
                window.WishlistManager.showToast(data.message, 'success');
            }
            
            // Update cart count in header
            updateCartCount(data.cart_count);
            
            // Change button text temporarily
            button.textContent = 'Đã thêm!';
            button.classList.remove('bg-red-600', 'hover:bg-red-700');
            button.classList.add('bg-green-600');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600');
                button.classList.add('bg-red-600', 'hover:bg-red-700');
            }, 2000);
        } else {
            if (window.WishlistManager) {
                window.WishlistManager.showToast(data.error || 'Có lỗi xảy ra', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.WishlistManager) {
            window.WishlistManager.showToast('Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
        }
    })
    .finally(() => {
        // Re-enable button
        button.disabled = false;
        button.classList.remove('opacity-50');
    });
}
</script>
@endsection
