<div>
    @if($pageType === 'checkout')
        <!-- Checkout Page -->
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8">Thanh toán</h1>
            
            @if($cartItems->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h2 class="mt-4 text-2xl font-semibold text-gray-900">Giỏ hàng trống</h2>
                    <p class="mt-2 text-gray-600">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Order Items -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Sản phẩm đã chọn</h2>
                            <div class="space-y-4">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center space-x-4 py-4 border-b border-gray-200 last:border-b-0">
                                        <img src="{{ $item->book->image_url }}" 
                                             alt="{{ $item->book->title }}"
                                             class="w-20 h-28 object-cover rounded">
                                        <div class="flex-1">
                                            <h3 class="font-medium text-gray-900">{{ $item->book->title }}</h3>
                                            <p class="text-sm text-gray-600">{{ $item->book->author->name ?? 'Unknown' }}</p>
                                            <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                                            <p class="text-sm text-gray-600">Giá: {{ number_format($item->price ?? $item->book->price, 0, ',', '.') }}đ</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium text-lg">{{ number_format(($item->price ?? $item->book->price) * $item->quantity, 0, ',', '.') }}đ</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Checkout Form -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin giao hàng</h2>
                            
                            <form wire:submit.prevent="storeOrder" class="space-y-4">
                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Họ và tên *</label>
                                    <input type="text" 
                                           id="full_name" 
                                           wire:model="full_name"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           required>
                                    @error('full_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại *</label>
                                    <input type="tel" 
                                           id="phone" 
                                           wire:model="phone"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           required>
                                    @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" 
                                           id="email" 
                                           wire:model="email"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           required>
                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ giao hàng *</label>
                                    <textarea id="address" 
                                              wire:model="address"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                              required></textarea>
                                    @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="shipping_method" class="block text-sm font-medium text-gray-700 mb-1">Phương thức giao hàng *</label>
                                    <select id="shipping_method" 
                                            wire:model="shipping_method"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="standard">Giao hàng tiêu chuẩn (2-3 ngày)</option>
                                        <option value="express">Giao hàng nhanh (1 ngày)</option>
                                        <option value="pickup">Nhận tại cửa hàng</option>
                                    </select>
                                    @error('shipping_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Phương thức thanh toán *</label>
                                    <select id="payment_method" 
                                            wire:model="payment_method"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                                        <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                                        <option value="credit_card">Thẻ tín dụng</option>
                                        <option value="momo">Ví MoMo</option>
                                    </select>
                                    @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                                    <textarea id="notes" 
                                              wire:model="notes"
                                              rows="2"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                              placeholder="Ghi chú thêm cho đơn hàng..."></textarea>
                                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Order Summary -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Tóm tắt đơn hàng</h3>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span>Tạm tính:</span>
                                            <span>{{ number_format($cartItems->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}đ</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Phí vận chuyển:</span>
                                            <span>
                                                @php
                                                    $subtotal = $cartItems->sum(function($item) { return $item->price * $item->quantity; });
                                                    $shippingFee = $this->calculateShippingFee($shipping_method, $subtotal);
                                                @endphp
                                                {{ $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . 'đ' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between text-lg font-semibold border-t pt-2">
                                            <span>Tổng cộng:</span>
                                            <span class="text-amber-600">
                                                {{ number_format($subtotal + $shippingFee, 0, ',', '.') }}đ
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" 
                                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                                    Đặt hàng
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    @elseif($pageType === 'track')
        <!-- Order Tracking Page -->
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8">Theo dõi đơn hàng</h1>
            
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Nhập thông tin đơn hàng</h2>
                    
                    <form wire:submit.prevent="trackOrder" class="space-y-4">
                        <div>
                            <label for="trackingOrderNumber" class="block text-sm font-medium text-gray-700 mb-1">Mã đơn hàng *</label>
                            <input type="text" 
                                   id="trackingOrderNumber" 
                                   wire:model="trackingOrderNumber"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Nhập mã đơn hàng..."
                                   required>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                            Theo dõi đơn hàng
                        </button>
                    </form>
                </div>

                @if($order)
                    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin đơn hàng</h2>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="font-medium">Mã đơn hàng:</span>
                                <span>{{ $order->order_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Ngày đặt:</span>
                                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Trạng thái:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                    @elseif($order->status == 'returned') bg-orange-100 text-orange-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Tổng tiền:</span>
                                <span class="font-semibold text-amber-600">{{ number_format($order->total, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    @elseif($pageType === 'my-orders')
        <!-- My Orders Page -->
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8">Đơn hàng của tôi</h1>
            
            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Đơn hàng #{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-600">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                    @elseif($order->status == 'returned') bg-orange-100 text-orange-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>

                            <div class="space-y-3">
                                @foreach($order->items as $item)
                                    <div class="flex items-center space-x-4 py-2 border-b border-gray-100 last:border-b-0">
                                        <img src="{{ $item->book->image_url }}" 
                                             alt="{{ $item->book->title }}"
                                             class="w-16 h-20 object-cover rounded">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $item->book->title }}</h4>
                                            <p class="text-sm text-gray-600">{{ $item->book->author->name ?? 'Unknown' }}</p>
                                            <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium">{{ number_format($item->total, 0, ',', '.') }}đ</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-between items-center mt-4 pt-4 border-t">
                                <div class="text-sm text-gray-600">
                                    <p>Tổng cộng: <span class="font-semibold text-amber-600">{{ number_format($order->total, 0, ',', '.') }}đ</span></p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('orders.show', $order->order_number) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors text-sm">
                                        Xem chi tiết
                                    </a>
                                    
                                    @if($order->status === 'shipped')
                                        <button wire:click="markDelivered('{{ $order->order_number }}')"
                                                class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm">
                                            Đã nhận hàng
                                        </button>
                                    @endif

                                    @if(in_array($order->status, ['shipped', 'delivered']))
                                        <button wire:click="openReturnModal"
                                                class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm">
                                            Hoàn hàng
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h2 class="mt-4 text-2xl font-semibold text-gray-900">Chưa có đơn hàng</h2>
                    <p class="mt-2 text-gray-600">Bạn chưa có đơn hàng nào.</p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            @endif
        </div>

    @elseif($pageType === 'show' && $order)
        <!-- Order Details Page -->
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8">Chi tiết đơn hàng</h1>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Status -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Trạng thái đơn hàng</h2>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-lg font-medium">Trạng thái hiện tại:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @elseif($order->status == 'returned') bg-orange-100 text-orange-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Sản phẩm đã đặt</h2>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center space-x-4 py-4 border-b border-gray-200 last:border-b-0">
                                    <img src="{{ $item->book->image_url }}" 
                                         alt="{{ $item->book->title }}"
                                         class="w-20 h-28 object-cover rounded">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $item->book->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $item->book->author->name ?? 'Unknown' }}</p>
                                        <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                                        <p class="text-sm text-gray-600">Giá: {{ number_format($item->book->price, 0, ',', '.') }}đ</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-lg">{{ number_format($item->book->price * $item->quantity, 0, ',', '.') }}đ</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="space-y-6">
                    <!-- Order Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin đơn hàng</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mã đơn hàng:</span>
                                <span class="font-medium">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ngày đặt:</span>
                                <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Trạng thái:</span>
                                <span class="font-medium">{{ ucfirst($order->status) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin giao hàng</h2>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Người nhận:</span> {{ $order->full_name }}</p>
                            <p><span class="font-medium">SĐT:</span> {{ $order->phone }}</p>
                            <p><span class="font-medium">Email:</span> {{ $order->email }}</p>
                            <p><span class="font-medium">Địa chỉ:</span> {{ $order->address }}</p>
                            <p><span class="font-medium">Phương thức giao hàng:</span> {{ ucfirst($order->shipping_method) }}</p>
                            <p><span class="font-medium">Phương thức thanh toán:</span> {{ ucfirst($order->payment_method) }}</p>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Tóm tắt đơn hàng</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Phí vận chuyển:</span>
                                <span>{{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . 'đ' }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold border-t pt-2">
                                <span>Tổng cộng:</span>
                                <span class="text-amber-600">{{ number_format($order->total, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                        
                        @if($order->notes)
                        <div class="mt-4">
                            <h3 class="font-medium text-gray-900 mb-2">Ghi chú:</h3>
                            <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Thao tác</h2>
                        <div class="space-y-3">
                            <a href="{{ route('orders.track') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Theo dõi đơn hàng
                            </a>

                            @if($order->status === 'shipped')
                                <button wire:click="markDelivered('{{ $order->order_number }}')"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Đánh dấu đã nhận hàng
                                </button>
                            @endif

                                    @if(in_array($order->status, ['shipped', 'delivered']))
                                        <button wire:click="openReturnModal"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m5 3v6a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h2"></path>
                                            </svg>
                                            Yêu cầu hoàn hàng
                                        </button>
                                    @endif
                            
                            <a href="{{ route('orders.index') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Xem tất cả đơn hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Return Order Modal -->
    @if($showReturnModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Hoàn hàng</h3>
                    <button wire:click="closeReturnModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="returnOrder('{{ $orderNumber ?? '' }}')">
                    <div class="mb-4">
                        <label for="return_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Lý do hoàn hàng <span class="text-red-500">*</span>
                        </label>
                        <textarea id="return_reason" 
                                  wire:model="returnReason"
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Vui lòng nhập lý do hoàn hàng..."
                                  required></textarea>
                        @error('returnReason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                wire:click="closeReturnModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Gửi yêu cầu hoàn hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
