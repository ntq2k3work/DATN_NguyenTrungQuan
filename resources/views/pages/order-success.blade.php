@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Success Message -->
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">Đặt hàng thành công!</span>
            </div>
        </div>

        <!-- Order Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Thông tin đơn hàng</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mã đơn hàng</label>
                    <p class="text-lg font-semibold text-primary">{{ $order->order_number }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ngày đặt hàng</label>
                    <p class="text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tổng tiền</label>
                    <p class="text-lg font-semibold text-primary">{{ number_format($order->total, 0, ',', '.') }}đ</p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Sản phẩm đã đặt</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                        <img src="{{ asset('images/books/' . $item->book->image) }}" 
                             alt="{{ $item->book->title }}" 
                             class="w-16 h-20 object-cover rounded">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $item->book->title }}</h4>
                            <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-600">Giá: {{ number_format($item->price, 0, ',', '.') }}đ</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-primary">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" 
               class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-dark transition-colors text-center">
                Tiếp tục mua sắm
            </a>
            <a href="{{ route('orders.track') }}" 
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors text-center">
                Theo dõi đơn hàng
            </a>
            @auth
            <a href="{{ route('orders.index') }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors text-center">
                Xem đơn hàng của tôi
            </a>
            @endauth
        </div>
    </div>
</div>
@endsection
