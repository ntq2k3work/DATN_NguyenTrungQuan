@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

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
            <div class="bg-white rounded-lg shadow-md p-6 flex items-center space-x-6 cart-item" data-book-id="${book.id}">
                <img src="${book.image_url || '/images/placeholder-book.jpg'}" alt="${book.title}" class="w-20 h-28 object-cover rounded">
                
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">${book.title}</h3>
                    <p class="text-gray-600">${book.author?.name || 'Unknown Author'}</p>
                    <div class="flex items-center space-x-4 mt-2">
                        <p class="text-lg font-bold text-red-600">${formatPrice(price)}đ</p>
                        <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded stock-info">
                            Còn lại: ${book.quantity || 0} sản phẩm
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button onclick="updateQuantity(${book.id}, ${quantity - 1})" class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300">
                        -
                    </button>
                    <input type="number" 
                           value="${quantity}" 
                           min="1" 
                           max="99"
                           onchange="updateQuantityFromInput(${book.id}, this.value)"
                           class="w-16 h-8 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
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
            showToast(data.error || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra khi cập nhật số lượng', 'error');
    });
}

function updateQuantityFromInput(bookId, inputValue) {
    const newQuantity = parseInt(inputValue);
    
    // Validate input
    if (isNaN(newQuantity) || newQuantity < 1) {
        // Reset to current quantity if invalid input
        loadCart();
        return;
    }
    
    // Find the book to check stock
    const cartItems = document.querySelectorAll('.cart-item');
    let maxStock = 99; // Default fallback
    
    cartItems.forEach(item => {
        const itemBookId = item.dataset.bookId;
        if (itemBookId == bookId) {
            const stockElement = item.querySelector('.stock-info');
            if (stockElement) {
                const stockText = stockElement.textContent;
                const stockMatch = stockText.match(/(\d+)/);
                if (stockMatch) {
                    maxStock = parseInt(stockMatch[1]);
                }
            }
        }
    });
    
    if (newQuantity > maxStock) {
        showToast(`Chỉ còn ${maxStock} sản phẩm trong kho`, 'warning');
        loadCart();
        return;
    }
    
    updateQuantity(bookId, newQuantity);
}

function removeFromCart(bookId) {
    // Show confirmation toast instead of browser confirm
    showToast('Đang xóa sản phẩm khỏi giỏ hàng...', 'info');
    
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
            showToast('Đã xóa sản phẩm khỏi giỏ hàng', 'success');
        } else {
            showToast(data.error || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra khi xóa sản phẩm', 'error');
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

// Toast notification functions
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toastId = 'toast-' + Date.now();
    
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>`,
        error: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                 <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
               </svg>`,
        warning: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                  </svg>`,
        info: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                 <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
               </svg>`
    };
    
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;
    toast.innerHTML = `
        <div class="flex-shrink-0">
            ${icons[type]}
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium">${message}</p>
        </div>
        <button onclick="removeToast('${toastId}')" class="flex-shrink-0 ml-4 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        removeToast(toastId);
    }, 5000);
}

function removeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}
</script>
@endsection
