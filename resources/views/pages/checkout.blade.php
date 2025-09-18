@extends('layouts.app')
@section('title', 'Thanh toán - BookStore')
@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Thanh toán</h1>
                <p class="text-gray-600 mt-2">Hoàn tất đơn hàng của bạn</p>
            </div>

            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-amber-600 text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                        <span class="ml-2 text-sm font-medium text-gray-900">Giỏ hàng</span>
                    </div>
                    <div class="w-16 h-0.5 bg-amber-600"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-amber-600 text-white rounded-full flex items-center justify-center text-sm font-medium">2</div>
                        <span class="ml-2 text-sm font-medium text-gray-900">Thanh toán</span>
                    </div>
                    <div class="w-16 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Hoàn thành</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Checkout Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-6">Thông tin giao hàng</h2>

                    <form action="{{ route('orders.store') }}" method="POST" id="checkout-form" novalidate>
                        @csrf

                        <!-- Personal Information -->
                        <div class="space-y-4 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Họ và tên <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="full_name" name="full_name"
                                           value="{{ old('full_name', $user->name ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                           required
                                           autocomplete="name">
                                    <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                                    @error('full_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Số điện thoại <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="phone" name="phone"
                                           value="{{ old('phone', $user->phone ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                           required
                                           autocomplete="tel">
                                    <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email"
                                       value="{{ old('email', $user->email ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                       required
                                       autocomplete="email">
                                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Địa chỉ giao hàng <span class="text-red-500">*</span>
                                </label>
                                <textarea id="address" name="address" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                          required
                                          autocomplete="street-address">{{ old('address', $user->address ?? '') }}</textarea>
                                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Shipping Method -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Phương thức giao hàng</h3>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors duration-200 shipping-option">
                                    <input type="radio" name="shipping_method" value="standard"
                                           {{ old('shipping_method', 'standard') == 'standard' ? 'checked' : '' }}
                                           class="mr-3 text-amber-600 focus:ring-amber-500">
                                    <div class="flex-1">
                                        <div class="font-medium">Giao hàng tiêu chuẩn</div>
                                        <div class="text-sm text-gray-600">3-5 ngày làm việc</div>
                                    </div>
                                    <div class="text-sm font-medium" id="standard-fee">30.000đ</div>
                                </label>

                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors duration-200 shipping-option">
                                    <input type="radio" name="shipping_method" value="express"
                                           {{ old('shipping_method') == 'express' ? 'checked' : '' }}
                                           class="mr-3 text-amber-600 focus:ring-amber-500">
                                    <div class="flex-1">
                                        <div class="font-medium">Giao hàng nhanh</div>
                                        <div class="text-sm text-gray-600">1-2 ngày làm việc</div>
                                    </div>
                                    <div class="text-sm font-medium" id="express-fee">50.000đ</div>
                                </label>

                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors duration-200 shipping-option">
                                    <input type="radio" name="shipping_method" value="pickup"
                                           {{ old('shipping_method') == 'pickup' ? 'checked' : '' }}
                                           class="mr-3 text-amber-600 focus:ring-amber-500">
                                    <div class="flex-1">
                                        <div class="font-medium">Nhận tại cửa hàng</div>
                                        <div class="text-sm text-gray-600">Miễn phí</div>
                                    </div>
                                    <div class="text-sm font-medium text-green-600">Miễn phí</div>
                                </label>
                            </div>
                            <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                            @error('shipping_method')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Phương thức thanh toán</h3>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors duration-200 payment-option">
                                    <input type="radio" name="payment_method" value="cod"
                                           {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}
                                           class="mr-3 text-amber-600 focus:ring-amber-500">
                                    <div class="flex-1">
                                        <div class="font-medium">Thanh toán khi nhận hàng (COD)</div>
                                        <div class="text-sm text-gray-600">Thanh toán bằng tiền mặt khi nhận hàng</div>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors duration-200 payment-option">
                                    <input type="radio" name="payment_method" value="bank_transfer"
                                           {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}
                                           class="mr-3 text-amber-600 focus:ring-amber-500">
                                    <div class="flex-1">
                                        <div class="font-medium">Chuyển khoản ngân hàng</div>
                                        <div class="text-sm text-gray-600">Chuyển khoản qua ngân hàng</div>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors duration-200 payment-option">
                                    <input type="radio" name="payment_method" value="vnpay"
                                           {{ old('payment_method') == 'vnpay' ? 'checked' : '' }}
                                           class="mr-3 text-amber-600 focus:ring-amber-500">
                                    <div class="flex-1">
                                        <div class="font-medium">Ví VNPay</div>
                                        <div class="text-sm text-gray-600">Thanh toán qua ví VNPay</div>
                                    </div>
                                </label>
                            </div>
                            <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                            @error('payment_method')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Ghi chú đơn hàng
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                      placeholder="Ghi chú thêm cho đơn hàng...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full bg-amber-600 text-white py-3 px-4 rounded-md hover:bg-amber-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                id="submit-btn">
                            <span class="submit-text">Đặt hàng</span>
                            <span class="loading-text hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Đang xử lý...
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-6">Tóm tắt đơn hàng</h2>

                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                        <div class="flex items-center space-x-4 py-3 border-b border-gray-200">
                            <img src="{{ asset($item->book->image_url) }}"
                                 alt="{{ $item->book->title }}"
                                 class="w-16 h-20 object-cover rounded">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item->book->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $item->book->author->name ?? 'Unknown' }}</p>
                                <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Order Totals -->
                    <div class="mt-6 space-y-2">
                        <div class="flex justify-between">
                            <span>Tạm tính:</span>
                            <span id="subtotal">{{ number_format($cartItems->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phí vận chuyển:</span>
                            <span id="shipping-fee">30.000đ</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold border-t pt-2">
                            <span>Tổng cộng:</span>
                            <span id="total">{{ number_format($cartItems->sum(function($item) { return $item->price * $item->quantity; }) + 30000, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.querySelector('.submit-text');
    const loadingText = document.querySelector('.loading-text');
    const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const subtotal = {{ $cartItems->sum(function($item) { return $item->price * $item->quantity; }) }};

    // Form validation
    const validateForm = () => {
        let isValid = true;

        // Clear previous errors
        document.querySelectorAll('.error-message').forEach(el => el.classList.add('hidden'));

        // Validate required fields
        const requiredFields = ['full_name', 'phone', 'email', 'address'];
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            const errorEl = field.parentNode.querySelector('.error-message');

            if (!field.value.trim()) {
                errorEl.textContent = 'Trường này là bắt buộc';
                errorEl.classList.remove('hidden');
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });

        // Validate email format
        const email = document.getElementById('email');
        const emailError = email.parentNode.querySelector('.error-message');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email.value && !emailRegex.test(email.value)) {
            emailError.textContent = 'Email không hợp lệ';
            emailError.classList.remove('hidden');
            email.classList.add('border-red-500');
            isValid = false;
        }

        // Validate phone format
        const phone = document.getElementById('phone');
        const phoneError = phone.parentNode.querySelector('.error-message');
        const phoneRegex = /^[0-9+\-\s()]+$/;
        if (phone.value && !phoneRegex.test(phone.value)) {
            phoneError.textContent = 'Số điện thoại không hợp lệ';
            phoneError.classList.remove('hidden');
            phone.classList.add('border-red-500');
            isValid = false;
        }

        // Validate shipping method
        const shippingError = document.querySelector('input[name="shipping_method"]').closest('.mb-6').querySelector('.error-message');
        if (!document.querySelector('input[name="shipping_method"]:checked')) {
            shippingError.textContent = 'Vui lòng chọn phương thức giao hàng';
            shippingError.classList.remove('hidden');
            isValid = false;
        }

        // Validate payment method
        const paymentError = document.querySelector('input[name="payment_method"]').closest('.mb-6').querySelector('.error-message');
        if (!document.querySelector('input[name="payment_method"]:checked')) {
            paymentError.textContent = 'Vui lòng chọn phương thức thanh toán';
            paymentError.classList.remove('hidden');
            isValid = false;
        }

        return isValid;
    };

    // Update shipping fee
    function updateShippingFee() {
        const selectedMethod = document.querySelector('input[name="shipping_method"]:checked');
        let shippingFee = 0;

        if (selectedMethod) {
            switch(selectedMethod.value) {
                case 'standard':
                    shippingFee = subtotal >= 500000 ? 0 : 30000;
                    break;
                case 'express':
                    shippingFee = subtotal >= 500000 ? 20000 : 50000;
                    break;
                case 'pickup':
                    shippingFee = 0;
                    break;
            }
        }

        document.getElementById('shipping-fee').textContent = shippingFee === 0 ? 'Miễn phí' : shippingFee.toLocaleString() + 'đ';
        document.getElementById('total').textContent = (subtotal + shippingFee).toLocaleString() + 'đ';
    }

    // Add visual feedback for selected options
    function updateOptionStyles() {
        // Shipping options
        document.querySelectorAll('.shipping-option').forEach(option => {
            const radio = option.querySelector('input[type="radio"]');
            if (radio.checked) {
                option.classList.add('border-amber-500', 'bg-amber-50');
            } else {
                option.classList.remove('border-amber-500', 'bg-amber-50');
            }
        });

        // Payment options
        document.querySelectorAll('.payment-option').forEach(option => {
            const radio = option.querySelector('input[type="radio"]');
            if (radio.checked) {
                option.classList.add('border-amber-500', 'bg-amber-50');
            } else {
                option.classList.remove('border-amber-500', 'bg-amber-50');
            }
        });
    }

    // Event listeners
    shippingRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            updateShippingFee();
            updateOptionStyles();
        });
    });

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', updateOptionStyles);
    });

    // Real-time validation
    document.querySelectorAll('input[required], textarea[required]').forEach(field => {
        field.addEventListener('blur', () => {
            const errorEl = field.parentNode.querySelector('.error-message');
            if (!field.value.trim()) {
                errorEl.textContent = 'Trường này là bắt buộc';
                errorEl.classList.remove('hidden');
                field.classList.add('border-red-500');
            } else {
                errorEl.classList.add('hidden');
                field.classList.remove('border-red-500');
            }
        });

        field.addEventListener('input', () => {
            const errorEl = field.parentNode.querySelector('.error-message');
            if (field.value.trim()) {
                errorEl.classList.add('hidden');
                field.classList.remove('border-red-500');
            }
        });
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!validateForm()) {
            showToast('Vui lòng điền đầy đủ thông tin bắt buộc', 'error');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        loadingText.classList.remove('hidden');

        // Check payment method and redirect accordingly
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (paymentMethod.value === 'vnpay') {
            form.action = '{{ route("vnpay.payment") }}';
        }

        // Submit form
        setTimeout(() => {
            form.submit();
        }, 500);
    });

    // Initialize
    updateShippingFee();
    updateOptionStyles();

    // Set default shipping method
    if (!document.querySelector('input[name="shipping_method"]:checked')) {
        document.querySelector('input[name="shipping_method"][value="standard"]').checked = true;
        updateShippingFee();
        updateOptionStyles();
    }

    // Set default payment method
    if (!document.querySelector('input[name="payment_method"]:checked')) {
        document.querySelector('input[name="payment_method"][value="cod"]').checked = true;
        updateOptionStyles();
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `p-4 rounded-md shadow-lg max-w-sm w-full ${type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500'} text-white`;
        toast.textContent = message;

        document.getElementById('toast-container').appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
});
</script>
@endsection
