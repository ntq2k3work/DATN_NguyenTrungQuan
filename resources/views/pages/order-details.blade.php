@extends('layouts.app')
@section('title', 'Chi tiết đơn hàng - BookStore')
@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Chi tiết đơn hàng</h1>
                        <p class="text-gray-600 mt-2">Đơn hàng #{{ $order->order_number }}</p>
                    </div>
                    <a href="{{ route('orders.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Quay lại
                    </a>
                </div>
            </div>

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
                                @endif">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        
                        <!-- Status Timeline -->
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
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Sản phẩm đã đặt</h2>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                            <div class="flex items-center space-x-4 py-4 border-b border-gray-200 last:border-b-0">
                                <img src="{{ asset($item->book->image_url) }}" 
                                     alt="{{ $item->book->title }}"
                                     class="w-20 h-28 object-cover rounded">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $item->book->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $item->book->author->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                                    <p class="text-sm text-gray-600">Giá: {{ number_format($item->price, 0, ',', '.') }}đ</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-lg">{{ number_format($item->total, 0, ',', '.') }}đ</p>
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
                                <span class="font-medium">{{ $order->status_label }}</span>
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
                            <p><span class="font-medium">Phương thức giao hàng:</span> {{ $order->shipping_method_label }}</p>
                            <p><span class="font-medium">Phương thức thanh toán:</span> {{ $order->payment_method_label }}</p>
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
                            <a href="{{ route('orders.track') }}?order_number={{ $order->order_number }}&phone={{ $order->phone }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Theo dõi đơn hàng
                            </a>
                            
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
    </div>
</div>
@endsection
