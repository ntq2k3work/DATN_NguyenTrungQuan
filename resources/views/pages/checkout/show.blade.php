@extends('layouts.app')
@section('title', 'Thanh toán')

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
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Thanh toán</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form thông tin -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Thông tin giao hàng</h2>
                    
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <input type="hidden" name="quantity" value="{{ $quantity }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên *</label>
                                <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>

                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ giao hàng *</label>
                            <textarea id="address" name="address" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('address') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức giao hàng *</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="shipping_method" value="standard" checked
                                            class="mr-2 text-red-600 focus:ring-red-500">
                                        <span class="text-sm text-gray-700">Giao hàng tiêu chuẩn (2-3 ngày)</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="shipping_method" value="express"
                                            class="mr-2 text-red-600 focus:ring-red-500">
                                        <span class="text-sm text-gray-700">Giao hàng nhanh (1 ngày)</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán *</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_method" value="cod" checked
                                            class="mr-2 text-red-600 focus:ring-red-500">
                                        <span class="text-sm text-gray-700">Thanh toán khi nhận hàng (COD)</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_method" value="bank_transfer"
                                            class="mr-2 text-red-600 focus:ring-red-500">
                                        <span class="text-sm text-gray-700">Chuyển khoản ngân hàng</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_method" value="cash"
                                            class="mr-2 text-red-600 focus:ring-red-500">
                                        <span class="text-sm text-gray-700">Tiền mặt</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('notes') }}</textarea>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Mã khuyến mãi -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Mã khuyến mãi</h3>
                    <div class="flex">
                        <input type="text" id="discount_code" name="discount_code" placeholder="Nhập mã khuyến mãi" 
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <button type="button" onclick="applyDiscount()" class="px-4 py-2 bg-red-600 text-white rounded-r-md hover:bg-red-700 transition-colors">
                            Áp dụng
                        </button>
                    </div>
                    <div id="discount_message" class="mt-2 text-sm hidden"></div>
                </div>

                <!-- Tóm tắt đơn hàng -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tóm tắt đơn hàng</h3>
                    
                    <!-- Sản phẩm -->
                    <div class="flex items-center space-x-4 mb-4">
                        <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}" 
                            class="w-16 h-16 object-cover rounded">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $book->title }}</h4>
                            <p class="text-sm text-gray-500">Số lượng: {{ $quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">{{ number_format($book->final_price * $quantity, 0, ',', '.') }}₫</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Chi tiết giá -->
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="text-gray-900">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="text-gray-900">{{ number_format($shipping, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="flex justify-between" id="discount_row" style="display: none;">
                            <span class="text-gray-600">Giảm giá:</span>
                            <span class="text-red-600" id="discount_amount">-0₫</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Tổng cộng -->
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-lg font-semibold text-gray-900">Tổng cộng:</span>
                        <span class="text-xl font-bold text-red-600">{{ number_format($total, 0, ',', '.') }}₫</span>
                    </div>

                    <!-- Nút đặt hàng -->
                    <button onclick="document.querySelector('form').submit()" 
                        class="w-full bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition-colors font-semibold">
                        Đặt hàng
                    </button>

                    <p class="text-xs text-gray-500 mt-3 text-center">
                        Bằng cách đặt hàng, bạn đồng ý với <a href="#" class="text-red-600">Điều khoản sử dụng</a> và <a href="#" class="text-red-600">Chính sách bảo mật</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentDiscount = 0;
let currentDiscountCode = '';

function applyDiscount() {
    const discountCode = document.getElementById('discount_code').value.trim();
    const messageDiv = document.getElementById('discount_message');
    
    if (!discountCode) {
        showDiscountMessage('Vui lòng nhập mã khuyến mãi', 'error');
        return;
    }
    
    // Gọi API để kiểm tra mã khuyến mãi
    fetch(`/api/check-discount?code=${encodeURIComponent(discountCode)}&subtotal={{ $subtotal }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentDiscount = data.discount_amount;
                currentDiscountCode = discountCode;
                showDiscountMessage(`Áp dụng mã khuyến mãi thành công! Giảm ${data.discount_text}`, 'success');
                updateOrderSummary();
            } else {
                showDiscountMessage(data.message, 'error');
            }
        })
        .catch(error => {
            showDiscountMessage('Có lỗi xảy ra, vui lòng thử lại', 'error');
        });
}

function showDiscountMessage(message, type) {
    const messageDiv = document.getElementById('discount_message');
    messageDiv.textContent = message;
    messageDiv.className = `mt-2 text-sm ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
    messageDiv.classList.remove('hidden');
}

function updateOrderSummary() {
    const discountRow = document.getElementById('discount_row');
    const discountAmount = document.getElementById('discount_amount');
    const totalElement = document.querySelector('.text-xl.font-bold.text-red-600');
    
    if (currentDiscount > 0) {
        discountRow.style.display = 'flex';
        discountAmount.textContent = `-${formatCurrency(currentDiscount)}`;
        
        // Cập nhật tổng tiền
        const subtotal = {{ $subtotal }};
        const shipping = {{ $shipping }};
        const newTotal = subtotal + shipping - currentDiscount;
        totalElement.textContent = formatCurrency(newTotal);
    } else {
        discountRow.style.display = 'none';
        const subtotal = {{ $subtotal }};
        const shipping = {{ $shipping }};
        totalElement.textContent = formatCurrency(subtotal + shipping);
    }
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
}
</script>
@endsection
