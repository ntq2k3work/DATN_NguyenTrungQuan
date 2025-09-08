@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Giỏ hàng của bạn</h1>
    
    <div id="cart-container">
        <!-- Cart items will be loaded here -->
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-500 mx-auto"></div>
            <p class="mt-4 text-gray-600">Đang tải giỏ hàng...</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
});

function loadCart() {
    fetch('{{ route("cart.index") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayCart(data.cart_items);
            } else {
                displayEmptyCart();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            displayEmptyCart();
        });
}

function displayCart(cartItems) {
    const container = document.getElementById('cart-container');
    
    if (cartItems.length === 0) {
        displayEmptyCart();
        return;
    }
    
    let html = '<div class="space-y-4">';
    
    cartItems.forEach(item => {
        const book = item.book || item;
        const quantity = item.quantity;
        const price = item.price || book.final_price || book.price;
        
        html += `
            <div class="bg-white rounded-lg shadow-md p-6 flex items-center space-x-6">
                <img src="${book.image_url || '/images/placeholder-book.jpg'}" alt="${book.title}" class="w-20 h-28 object-cover rounded">
                
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">${book.title}</h3>
                    <p class="text-gray-600">${book.author?.name || 'Unknown Author'}</p>
                    <p class="text-lg font-bold text-red-600">${formatPrice(price)}đ</p>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button onclick="updateQuantity(${book.id}, ${quantity - 1})" class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300">
                        -
                    </button>
                    <span class="w-12 text-center font-semibold">${quantity}</span>
                    <button onclick="updateQuantity(${book.id}, ${quantity + 1})" class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300">
                        +
                    </button>
                </div>
                
                <div class="text-right">
                    <p class="text-lg font-bold text-red-600">${formatPrice(price * quantity)}đ</p>
                    <button onclick="removeFromCart(${book.id})" class="text-red-500 hover:text-red-700 text-sm mt-2">
                        Xóa
                    </button>
                </div>
            </div>
        `;
    });
    
    // Calculate total
    const total = cartItems.reduce((sum, item) => {
        const book = item.book || item;
        const price = item.price || book.final_price || book.price;
        return sum + (price * item.quantity);
    }, 0);
    
    html += `
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Tổng cộng:</h3>
                <p class="text-2xl font-bold text-red-600">${formatPrice(total)}đ</p>
            </div>
            
            <div class="mt-6 flex space-x-4">
                <button onclick="window.location.href='{{ route('home') }}'" class="flex-1 bg-gray-500 text-white py-3 px-6 rounded-lg hover:bg-gray-600 transition-colors">
                    Tiếp tục mua sắm
                </button>
                <button onclick="window.location.href='{{ route('checkout') }}'" class="flex-1 bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition-colors">
                    Thanh toán
                </button>
            </div>
        </div>
    `;
    
    html += '</div>';
    container.innerHTML = html;
}

function displayEmptyCart() {
    const container = document.getElementById('cart-container');
    container.innerHTML = `
        <div class="text-center py-16">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9m-6-9v9"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Giỏ hàng trống</h3>
            <p class="mt-2 text-gray-500">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
            <div class="mt-6">
                <button onclick="window.location.href='{{ route('home') }}'" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors">
                    Bắt đầu mua sắm
                </button>
            </div>
        </div>
    `;
}

function updateQuantity(bookId, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(bookId);
        return;
    }
    
    fetch('{{ route("cart.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            book_id: bookId,
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCart();
            updateCartCount(data.cart_count);
        } else {
            alert(data.error || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật số lượng');
    });
}

function removeFromCart(bookId) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        return;
    }
    
    fetch('{{ route("cart.remove") }}', {
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
            loadCart();
            updateCartCount(data.cart_count);
        } else {
            alert(data.error || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa sản phẩm');
    });
}

function updateCartCount(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}
</script>
@endsection
