@extends('layouts.app')
@section('title', 'Theo dõi đơn hàng - BookStore')
@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Theo dõi đơn hàng</h1>
                <p class="text-gray-600 mt-2">Kiểm tra trạng thái đơn hàng của bạn</p>
            </div>

            <!-- Search Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form action="{{ route('orders.track') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã đơn hàng
                            </label>
                            <input type="text" id="order_number" name="order_number" 
                                   value="{{ request('order_number') }}"
                                   placeholder="VD: ORD-20241206-0001"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Số điện thoại
                            </label>
                            <input type="tel" id="phone" name="phone" 
                                   value="{{ request('phone') }}"
                                   placeholder="Số điện thoại đặt hàng"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full md:w-auto bg-amber-600 text-white py-2 px-6 rounded-md hover:bg-amber-700 transition-colors">
                        Tra cứu đơn hàng
                    </button>
                </form>
            </div>

            <!-- Order Details -->
            @if($order)
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- Order Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Đơn hàng #{{ $order->order_number }}</h2>
                        <p class="text-gray-600">Đặt ngày: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mt-2 md:mt-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                            @endif">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Order Status Timeline -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Trạng thái đơn hàng</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white text-sm font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600 mt-1">Đặt hàng</span>
                        </div>
                        
                        <div class="flex-1 h-0.5 bg-gray-300"></div>
                        
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $order->status == 'processing' || $order->status == 'shipped' || $order->status == 'delivered' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center text-white text-sm font-medium">
                                @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'delivered')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                @else
                                <span>2</span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-600 mt-1">Xác nhận</span>
                        </div>
                        
                        <div class="flex-1 h-0.5 {{ $order->status == 'shipped' || $order->status == 'delivered' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                        
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $order->status == 'shipped' || $order->status == 'delivered' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center text-white text-sm font-medium">
                                @if($order->status == 'shipped' || $order->status == 'delivered')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                @else
                                <span>3</span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-600 mt-1">Giao hàng</span>
                        </div>
                        
                        <div class="flex-1 h-0.5 {{ $order->status == 'delivered' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                        
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $order->status == 'delivered' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center text-white text-sm font-medium">
                                @if($order->status == 'delivered')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                @else
                                <span>4</span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-600 mt-1">Hoàn thành</span>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sản phẩm đã đặt</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center space-x-4 py-3 border-b border-gray-200">
                            <img src="{{ asset($item->book->image_url) }}" 
                                 alt="{{ $item->book->title }}"
                                 class="w-16 h-20 object-cover rounded">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->book->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $item->book->author->name ?? 'Unknown' }}</p>
                                <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">{{ number_format($item->total, 0, ',', '.') }}đ</p>
                                <p class="text-sm text-gray-600">{{ number_format($item->price, 0, ',', '.') }}đ × {{ $item->quantity }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Shipping Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin giao hàng</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Người nhận:</span> {{ $order->full_name }}</p>
                            <p><span class="font-medium">SĐT:</span> {{ $order->phone }}</p>
                            <p><span class="font-medium">Email:</span> {{ $order->email }}</p>
                            <p><span class="font-medium">Địa chỉ:</span> {{ $order->address }}</p>
                            <p><span class="font-medium">Phương thức giao hàng:</span> {{ $order->shipping_method_label }}</p>
                            <p><span class="font-medium">Phương thức thanh toán:</span> {{ $order->payment_method_label }}</p>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tóm tắt đơn hàng</h3>
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
                                <span>{{ number_format($order->total, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                        
                        @if($order->notes)
                        <div class="mt-4">
                            <h4 class="font-medium text-gray-900 mb-2">Ghi chú:</h4>
                            <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @elseif(request()->has('order_number') || request()->has('phone'))
            <!-- No Order Found -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 6.291A7.962 7.962 0 0012 4c-2.34 0-4.29 1.009-5.824 2.709"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy đơn hàng</h3>
                <p class="text-gray-600 mb-4">Vui lòng kiểm tra lại mã đơn hàng và số điện thoại</p>
                <a href="{{ route('orders.track') }}" 
                   class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
                    Tra cứu lại
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
