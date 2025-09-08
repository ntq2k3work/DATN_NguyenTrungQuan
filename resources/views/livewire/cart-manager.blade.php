<div>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Giỏ hàng của bạn</h1>
        
        @if($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-4">Sản phẩm trong giỏ hàng</h2>
                            
                            <div class="space-y-4">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center space-x-4 p-4 border rounded-lg">
                                        <!-- Book Image -->
                                        <div class="w-20 h-28 flex-shrink-0">
                                            <img src="{{ $item->book->image_url }}" 
                                                 alt="{{ $item->book->title }}" 
                                                 class="w-full h-full object-cover rounded">
                                        </div>
                                        
                                        <!-- Book Details -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-medium text-gray-900 truncate">
                                                {{ $item->book->title }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                Tác giả: {{ $item->book->author->name }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Nhà xuất bản: {{ $item->book->publisher->name }}
                                            </p>
                                        </div>
                                        
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center space-x-2">
                                            <button wire:click="updateQuantity({{ $item->book_id }}, {{ $item->quantity - 1 }})"
                                                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            
                                            <span class="w-12 text-center font-medium">{{ $item->quantity }}</span>
                                            
                                            <button wire:click="updateQuantity({{ $item->book_id }}, {{ $item->quantity + 1 }})"
                                                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Price -->
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-gray-900">
                                                {{ number_format(($item->price ?? $item->book->price) * $item->quantity, 0, ',', '.') }}đ
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ number_format($item->price ?? $item->book->price, 0, ',', '.') }}đ × {{ $item->quantity }}
                                            </p>
                                        </div>
                                        
                                        <!-- Remove Button -->
                                        <button wire:click="removeFromCart({{ $item->book_id }})"
                                                class="text-red-600 hover:text-red-800 p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Clear Cart Button -->
                            <div class="mt-6 pt-6 border-t">
                                <button wire:click="clearCart"
                                        class="text-red-600 hover:text-red-800 font-medium">
                                    Xóa tất cả sản phẩm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                        <h2 class="text-xl font-semibold mb-4">Tóm tắt đơn hàng</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-medium">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                <span class="font-medium">{{ number_format($shippingFee, 0, ',', '.') }}đ</span>
                            </div>
                            
                            <div class="border-t pt-3">
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Tổng cộng:</span>
                                    <span class="text-red-600">{{ number_format($total, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Checkout Button -->
                        <div class="mt-6">
                            <a href="{{ route('checkout') }}" 
                               class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 text-center block">
                                Tiến hành thanh toán
                            </a>
                        </div>
                        
                        <!-- Continue Shopping -->
                        <div class="mt-4">
                            <a href="{{ route('home') }}" 
                               class="w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-lg hover:bg-gray-300 transition duration-200 text-center block">
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <h2 class="mt-4 text-2xl font-semibold text-gray-900">Giỏ hàng trống</h2>
                <p class="mt-2 text-gray-600">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Bắt đầu mua sắm
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
