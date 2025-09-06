@extends('layouts.app')
@section('title', 'Đặt hàng thành công - BookStore')
@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Success Icon -->
            <div class="mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Success Message -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Đặt hàng thành công!</h1>
                <p class="text-lg text-gray-600 mb-2">Cảm ơn bạn đã đặt hàng tại BookStore</p>
                <p class="text-gray-600">Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất</p>
            </div>

            <!-- Order Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin đơn hàng</h2>
                <div class="space-y-3 text-left">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mã đơn hàng:</span>
                        <span class="font-medium">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngày đặt:</span>
                        <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tổng tiền:</span>
                        <span class="font-medium text-lg text-amber-600">{{ number_format($order->total, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Trạng thái:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Bước tiếp theo</h3>
                <div class="space-y-2 text-sm text-blue-800">
                    <p>• Chúng tôi sẽ gửi email xác nhận đơn hàng đến {{ $order->email }}</p>
                    <p>• Đơn hàng sẽ được xử lý trong vòng 1-2 ngày làm việc</p>
                    <p>• Bạn có thể theo dõi trạng thái đơn hàng bằng mã đơn hàng</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('orders.track') }}" 
                   class="inline-flex items-center px-6 py-3 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Theo dõi đơn hàng
                </a>
                
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
