@extends('layouts.app')
@section('title', $book->title)

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('categories.show', $book->category->slug) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">
                            {{ $book->category->name }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $book->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Product Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Product Image -->
            <div class="space-y-4">
                <div class="relative">
                    <img 
                        src="{{ asset($book->image_url) }}" 
                        alt="{{ $book->title }}"
                        class="w-full h-[500px] object-cover rounded-lg shadow-lg"
                    />
                    @if($book->discount_percent > 0)
                        <div class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                            -{{ $book->discount_percent }}% OFF
                        </div>
                    @endif
                    <!-- Navigation arrows -->
                    <button class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Thumbnail Images -->
                <div class="flex space-x-2">
                    <img 
                        src="{{ asset($book->image_url) }}" 
                        alt="{{ $book->title }}"
                        class="w-20 h-20 object-cover rounded border-2 border-gray-200 cursor-pointer hover:border-red-500"
                    />
                    <img 
                        src="{{ asset($book->image_url) }}" 
                        alt="{{ $book->title }}"
                        class="w-20 h-20 object-cover rounded border-2 border-gray-200 cursor-pointer hover:border-red-500"
                    />
                </div>
                
                <!-- Dots indicator -->
                <div class="flex justify-center space-x-2">
                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-2xl font-bold text-red-600 mb-2">{{ $book->title }}</h1>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-green-600 font-medium">Tình trạng: Còn hàng</span>
                        <span class="text-gray-600">Nhà xuất bản: {{ $book->publisher->name ?? 'Chưa có thông tin' }}</span>
                    </div>
                    <div class="mt-2">
                        <span class="text-blue-600 font-medium text-sm">Số lượng có sẵn: {{ $book->quantity }} sản phẩm</span>
                    </div>
                </div>

                <!-- Price Section -->
                <div class="space-y-2">
                    @if($book->discount_percent > 0)
                        <div class="flex items-center space-x-3">
                            <span class="text-3xl font-bold text-red-600">{{ number_format($book->final_price, 0, ',', '.') }}₫</span>
                            <span class="text-xl text-gray-500 line-through">{{ number_format($book->price, 0, ',', '.') }}₫</span>
                            <span class="bg-red-100 text-red-800 text-sm font-medium px-2 py-1 rounded">-{{ $book->discount_percent }}%</span>
                        </div>
                    @else
                        <span class="text-3xl font-bold text-red-600">{{ number_format($book->price, 0, ',', '.') }}₫</span>
                    @endif
                    
                    <!-- Wishlist Button -->
                    <div class="flex items-center space-x-2">
                        <button 
                            id="wishlist-btn" 
                            class="flex items-center space-x-2 px-4 py-2 rounded-lg border border-gray-300 hover:border-red-500 transition-colors {{ $inWishlist ? 'bg-red-50 border-red-500 text-red-600' : 'bg-white text-gray-600 hover:text-red-600' }}"
                            onclick="toggleWishlist({{ $book->id }})"
                            data-book-id="{{ $book->id }}"
                            data-in-wishlist="{{ $inWishlist ? 'true' : 'false' }}"
                        >
                            <svg class="w-5 h-5 {{ $inWishlist ? 'fill-current' : 'stroke-current' }}" 
                                 fill="{{ $inWishlist ? 'currentColor' : 'none' }}" 
                                 stroke="{{ $inWishlist ? 'none' : 'currentColor' }}" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span id="wishlist-text">{{ $inWishlist ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}</span>
                        </button>
                    </div>
                </div>

                <!-- Quantity and Buy Button -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 font-medium">Số lượng:</span>
                        <div class="flex items-center border border-gray-300 rounded">
                            <button class="px-3 py-2 hover:bg-gray-100" onclick="decreaseQuantity()">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $book->quantity }}" class="w-16 text-center border-none focus:outline-none" onchange="validateQuantity()" oninput="validateQuantity()">
                            <button class="px-3 py-2 hover:bg-gray-100" onclick="increaseQuantity()">+</button>
                        </div>
                        <div id="quantity-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <button onclick="buyNow()" class="w-full bg-red-600 text-white py-4 px-6 rounded-lg hover:bg-red-700 transition-colors font-semibold text-lg">
                        MUA NGAY
                    </button>
                    
                    <button onclick="addToCart({{ $book->id }})" class="w-full bg-amber-600 text-white py-3 px-6 rounded-lg hover:bg-amber-700 transition-colors font-semibold text-lg add-to-cart-btn" data-book-id="{{ $book->id }}">
                        THÊM VÀO GIỎ HÀNG
                    </button>
                </div>

                <!-- Commitments and Delivery -->
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Cam kết 100% chính hãng</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Giao hàng dự kiến: Thứ 2 - Thứ 6 từ 9h00 - 17h00</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Hỗ trợ 24/7 Với các kênh chat, email & phone</span>
                        </div>
                    </div>
                </div>

                <!-- Product Description -->
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">MÔ TẢ</h2>
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <div class="relative">
                            <div id="description-content" class="text-gray-700 leading-relaxed transition-all duration-300 ease-in-out">
                                {{ $book->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.' }}
                            </div>
                            <div id="description-overlay" class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white via-white/80 to-transparent pointer-events-none transition-opacity duration-300 group-hover:opacity-0"></div>
                        </div>
                        <button id="toggle-description" class="mt-4 text-red-600 hover:text-red-800 font-medium text-sm hidden transition-colors duration-200 flex items-center gap-1 px-3 py-1 rounded-md hover:bg-red-50">
                            <span id="toggle-text">Xem thêm nội dung</span>
                            <svg id="toggle-icon" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Features -->
                <div class="space-y-2">
                    <div class="flex items-start space-x-2">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 flex-shrink-0"></div>
                        <span class="text-gray-700">Tiếp cận toàn diện các dạng bài trọng điểm.</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 flex-shrink-0"></div>
                        <span class="text-gray-700">Luyện thi hơn 1000 câu hỏi thực hành bám sát đề thi thật</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 flex-shrink-0"></div>
                        <span class="text-gray-700">Củng cố kiến thức bằng hệ thống bài test và đáp án chi tiết.</span>
                    </div>
                </div>

                <!-- Logos -->
                <div class="flex items-center space-x-4 pt-4">
                    <div class="text-sm font-bold text-gray-600">1980 EDU</div>
                    <div class="text-sm text-gray-600">NHÀ XUẤT BẢN {{ strtoupper($book->publisher->name ?? 'DÂN TRÍ') }}</div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedBooks->count() > 0)
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Sách liên quan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedBooks as $relatedBook)
                <div class="group bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300">
                    <div class="p-4">
                        <div class="relative mb-4">
                            <a href="{{ route('product.show', $relatedBook->slug) }}">
                                <img
                                    src="{{ asset($relatedBook->image_url) }}"
                                    alt="{{ $relatedBook->title }}"
                                    class="w-full h-[200px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300"
                                />
                            </a>
                            @if($relatedBook->discount_percent > 0)
                                <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">-{{ $relatedBook->discount_percent }}%</span>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <div>
                                <a href="{{ route('product.show', $relatedBook->slug) }}" class="font-semibold text-gray-900 line-clamp-2 group-hover:text-red-600 transition-colors">
                                    {{ $relatedBook->title }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $relatedBook->author->name ?? 'Unknown' }}</p>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    @if($relatedBook->discount_percent > 0)
                                        <span class="text-sm font-bold text-red-600">{{ number_format($relatedBook->final_price, 0, ',', '.') }}₫</span>
                                        <span class="text-xs text-gray-500 line-through">{{ number_format($relatedBook->price, 0, ',', '.') }}₫</span>
                                    @else
                                        <span class="text-sm font-bold text-gray-900">{{ number_format($relatedBook->price, 0, ',', '.') }}₫</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Floating Icons -->
<div class="fixed bottom-6 right-6 space-y-3 z-50">
    <button class="bg-red-600 text-white p-3 rounded-full shadow-lg hover:bg-red-700 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
    </button>
    <button class="bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-colors">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
        </svg>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const descriptionContent = document.getElementById('description-content');
    const descriptionOverlay = document.getElementById('description-overlay');
    const toggleButton = document.getElementById('toggle-description');
    const toggleText = document.getElementById('toggle-text');
    const toggleIcon = document.getElementById('toggle-icon');
    
    let isExpanded = false;
    const maxHeight = 120; // Chiều cao tối đa khi thu gọn
    
    // Kiểm tra xem nội dung có dài không
    function checkContentHeight() {
        // Reset để đo chiều cao thực
        descriptionContent.style.maxHeight = 'none';
        descriptionContent.style.overflow = 'visible';
        
        const contentHeight = descriptionContent.scrollHeight;
        
        if (contentHeight > maxHeight) {
            // Nội dung dài, hiển thị overlay và nút toggle
            descriptionOverlay.classList.remove('hidden');
            toggleButton.classList.remove('hidden');
            descriptionContent.style.maxHeight = maxHeight + 'px';
            descriptionContent.style.overflow = 'hidden';
            isExpanded = false;
            
            // Thêm class để ẩn overlay khi hover
            descriptionContent.parentElement.classList.add('group');
        } else {
            // Nội dung ngắn, ẩn overlay và nút toggle
            descriptionOverlay.classList.add('hidden');
            toggleButton.classList.add('hidden');
            descriptionContent.style.maxHeight = 'none';
            descriptionContent.style.overflow = 'visible';
            isExpanded = true;
        }
    }
    
    // Xử lý sự kiện click nút toggle
    toggleButton.addEventListener('click', function() {
        if (isExpanded) {
            // Thu gọn
            descriptionContent.style.maxHeight = maxHeight + 'px';
            descriptionContent.style.overflow = 'hidden';
            descriptionOverlay.classList.remove('hidden');
            toggleText.textContent = 'Xem thêm nội dung';
            toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>';
            isExpanded = false;
        } else {
            // Mở rộng
            const fullHeight = descriptionContent.scrollHeight;
            descriptionContent.style.maxHeight = fullHeight + 'px';
            descriptionContent.style.overflow = 'visible';
            descriptionOverlay.classList.add('hidden');
            toggleText.textContent = 'Thu gọn';
            toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>';
            isExpanded = true;
            
            // Sau khi mở rộng hoàn toàn, reset maxHeight để có thể thu gọn lại
            setTimeout(() => {
                if (isExpanded) {
                    descriptionContent.style.maxHeight = 'none';
                }
            }, 300);
        }
    });
    
    // Kiểm tra khi trang load
    setTimeout(checkContentHeight, 100);
    
    // Kiểm tra khi cửa sổ thay đổi kích thước
    window.addEventListener('resize', function() {
        setTimeout(checkContentHeight, 100);
    });
});

// Quantity functions
function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
        hideQuantityError();
    }
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.getAttribute('max'));
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
        hideQuantityError();
    } else {
        showQuantityError(`Số lượng tối đa có thể mua là ${maxValue} sản phẩm`);
    }
}

function validateQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.getAttribute('max'));
    const minValue = parseInt(quantityInput.getAttribute('min'));
    
    if (isNaN(currentValue) || currentValue < minValue) {
        quantityInput.value = minValue;
        showQuantityError(`Số lượng tối thiểu là ${minValue} sản phẩm`);
        return false;
    } else if (currentValue > maxValue) {
        quantityInput.value = maxValue;
        showQuantityError(`Số lượng tối đa có thể mua là ${maxValue} sản phẩm`);
        return false;
    } else {
        hideQuantityError();
        return true;
    }
}

function showQuantityError(message) {
    const errorElement = document.getElementById('quantity-error');
    errorElement.textContent = message;
    errorElement.classList.remove('hidden');
}

function hideQuantityError() {
    const errorElement = document.getElementById('quantity-error');
    errorElement.classList.add('hidden');
}

function buyNow() {
    if (!validateQuantity()) {
        return;
    }
    const quantity = document.getElementById('quantity').value;
    const bookId = {{ $book->id }};
    window.location.href = `{{ route('checkout') }}?book_id=${bookId}&quantity=${quantity}`;
}

function addToCart(bookId) {
    if (!validateQuantity()) {
        return;
    }
    const quantity = document.getElementById('quantity').value;
    const button = document.querySelector(`[data-book-id="${bookId}"]`);
    const originalText = button.textContent;
    
    // Disable button and show loading
    button.disabled = true;
    button.textContent = 'ĐANG THÊM...';
    button.classList.add('opacity-50');
    
    // Make API call
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            book_id: bookId,
            quantity: parseInt(quantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showToast(data.message);
            
            // Update cart count if cart icon exists
            updateCartCount(data.cart_count);
            
            // Change button text temporarily
            button.textContent = 'ĐÃ THÊM!';
            button.classList.remove('bg-amber-600', 'hover:bg-amber-700');
            button.classList.add('bg-green-600');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600');
                button.classList.add('bg-amber-600', 'hover:bg-amber-700');
            }, 2000);
        } else {
            showToast(data.error || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
    })
    .finally(() => {
        // Re-enable button
        button.disabled = false;
        button.classList.remove('opacity-50');
    });
}

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

function updateCartCount(count) {
    // Update cart count in header if exists
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

// Wishlist functions
function toggleWishlist(bookId) {
    const button = document.getElementById('wishlist-btn');
    const text = document.getElementById('wishlist-text');
    const svg = button.querySelector('svg');
    const originalText = text.textContent;
    
    // Disable button and show loading
    button.disabled = true;
    text.textContent = 'Đang xử lý...';
    button.classList.add('opacity-50');
    
    // Make API call
    fetch('{{ route("wishlist.toggle") }}', {
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
        if (data.success) {
            // Update button state
            const isInWishlist = data.in_wishlist;
            
            if (isInWishlist) {
                // Added to wishlist
                button.classList.remove('bg-white', 'text-gray-600', 'hover:text-red-600');
                button.classList.add('bg-red-50', 'border-red-500', 'text-red-600');
                svg.classList.remove('stroke-current');
                svg.classList.add('fill-current');
                svg.setAttribute('fill', 'currentColor');
                svg.setAttribute('stroke', 'none');
                text.textContent = 'Đã yêu thích';
                button.setAttribute('data-in-wishlist', 'true');
                
                showToast(data.message, 'success');
                
                // Update wishlist count in header
                if (window.WishlistManager && data.wishlist_count !== undefined) {
                    window.WishlistManager.updateWishlistCount(data.wishlist_count);
                }
            } else {
                // Removed from wishlist
                button.classList.remove('bg-red-50', 'border-red-500', 'text-red-600');
                button.classList.add('bg-white', 'text-gray-600', 'hover:text-red-600');
                svg.classList.remove('fill-current');
                svg.classList.add('stroke-current');
                svg.setAttribute('fill', 'none');
                svg.setAttribute('stroke', 'currentColor');
                text.textContent = 'Thêm vào yêu thích';
                button.setAttribute('data-in-wishlist', 'false');
                
                showToast(data.message, 'success');
                
                // Update wishlist count in header
                if (window.WishlistManager && data.wishlist_count !== undefined) {
                    window.WishlistManager.updateWishlistCount(data.wishlist_count);
                }
            }
        } else {
            showToast(data.error || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra khi thực hiện thao tác', 'error');
    })
    .finally(() => {
        // Re-enable button
        button.disabled = false;
        button.classList.remove('opacity-50');
    });
}
</script>
@endsection
