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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Checkout Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-6">Thông tin giao hàng</h2>
                    
                    <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
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
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                                           required>
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
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                                           required>
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
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                                       required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Địa chỉ giao hàng <span class="text-red-500">*</span>
                                </label>
                                <textarea id="address" name="address" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                                          required>{{ old('address', $user->address ?? '') }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Shipping Method -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Phương thức giao hàng</h3>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="shipping_method" value="standard" 
                                           {{ old('shipping_method', 'standard') == 'standard' ? 'checked' : '' }}
                                           class="mr-3 text-amber-600 focus:ring-amber-500">
                                    <div class="flex-1">
                                        <div class="font-medium">Giao hàng tiêu chuẩn</div>
                                        <div class="text-sm text-gray-600">3-5 ngày làm việc</div>
                                    </div>
                                    <div class="text-sm font-medium" id="standard-fee">30.000đ</div>
                                </label>
                                
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="shipping_method" value="express" 
                                           {{ old('shipping_method') == 'express' ? 'checked' : '' }}
                                           class="mr-3 text-amber-600 focus:ring-amber-500">
                                    <div class="flex-1">
                                        <div class="font-medium">Giao hàng nhanh</div>
                                        <div class="text-sm text-gray-600">1-2 ngày làm việc</div>
                                    </div>
                                    <div class="text-sm font-medium" id="express-fee">50.000đ</div>
                                </label>
                                
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
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
                            @error('shipping_method')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Phương thức thanh toán</h3>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="cod" 
                                           {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}
                                           class="mr-3 text-amber-600 focus:ring-amber-500">
                                    <div class="flex-1">
                                        <div class="font-medium">Thanh toán khi nhận hàng (COD)</div>
                                        <div class="text-sm text-gray-600">Thanh toán bằng tiền mặt khi nhận hàng</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="bank_transfer" 
                                           {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}
                                           class="mr-3 text-amber-600 focus:ring-amber-500">
                                    <div class="flex-1">
                                        <div class="font-medium">Chuyển khoản ngân hàng</div>
                                        <div class="text-sm text-gray-600">Chuyển khoản qua ngân hàng</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="momo" 
                                           {{ old('payment_method') == 'momo' ? 'checked' : '' }}
                                           class="mr-3 text-amber-600 focus:ring-amber-500">
                                    <div class="flex-1">
                                        <div class="font-medium">Ví MoMo</div>
                                        <div class="text-sm text-gray-600">Thanh toán qua ví MoMo</div>
                                    </div>
                                </label>
                            </div>
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
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                                      placeholder="Ghi chú thêm cho đơn hàng...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-amber-600 text-white py-3 px-4 rounded-md hover:bg-amber-700 transition-colors font-medium">
                            Đặt hàng
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
    const subtotal = {{ $cartItems->sum(function($item) { return $item->price * $item->quantity; }) }};
    
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
    
    shippingRadios.forEach(radio => {
        radio.addEventListener('change', updateShippingFee);
    });
    
    // Set default shipping method
    if (!document.querySelector('input[name="shipping_method"]:checked')) {
        document.querySelector('input[name="shipping_method"][value="standard"]').checked = true;
        updateShippingFee();
    }
    
    // Set default payment method
    if (!document.querySelector('input[name="payment_method"]:checked')) {
        document.querySelector('input[name="payment_method"][value="cod"]').checked = true;
    }
    
    // Form submission handling
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        console.log('Form submitted');
        
        // Check if required fields are filled
        const fullName = document.getElementById('full_name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();
        const address = document.getElementById('address').value.trim();
        const shippingMethod = document.querySelector('input[name="shipping_method"]:checked');
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!fullName || !phone || !email || !address || !shippingMethod || !paymentMethod) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc');
            return false;
        }
        
        console.log('Form validation passed, submitting...');
    });
});
</script>
@endsection
