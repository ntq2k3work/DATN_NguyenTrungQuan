@extends('layouts.app')
@section('title', 'Đơn hàng của tôi - BookStore')
@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Đơn hàng của tôi</h1>
                <p class="text-gray-600 mt-2">Quản lý và theo dõi các đơn hàng của bạn</p>
            </div>

            @if($orders->count() > 0)
            <!-- Orders List -->
            <div class="space-y-6">
                @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <!-- Order Header -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Đơn hàng #{{ $order->order_number }}</h2>
                            <p class="text-sm text-gray-600">Đặt ngày: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mt-2 md:mt-0 flex items-center space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ $order->status_label }}
                            </span>
                            <span class="text-lg font-semibold text-gray-900">{{ number_format($order->total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="mb-4">
                        <div class="flex items-center space-x-4">
                            @foreach($order->items->take(3) as $item)
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset($item->book->image_url) }}" 
                                     alt="{{ $item->book->title }}"
                                     class="w-12 h-16 object-cover rounded">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $item->book->title }}</p>
                                    <p class="text-xs text-gray-600">Số lượng: {{ $item->quantity }}</p>
                                </div>
                            </div>
                            @endforeach
                            @if($order->items->count() > 3)
                            <div class="text-sm text-gray-600">
                                và {{ $order->items->count() - 3 }} sản phẩm khác
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="flex flex-col sm:flex-row gap-2">
                        <a href="{{ route('orders.show', $order->order_number) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Xem chi tiết
                        </a>
                        
                        <a href="{{ route('orders.track') }}?order_number={{ $order->order_number }}&phone={{ $order->phone }}" 
                           class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Theo dõi đơn hàng
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có đơn hàng nào</h3>
                <p class="text-gray-600 mb-6">Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm ngay!</p>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-3 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Mua sắm ngay
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
