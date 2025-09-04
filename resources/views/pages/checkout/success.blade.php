@extends('layouts.app')
@section('title', 'Đặt hàng thành công')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Đặt hàng thành công!</h1>
                    <p class="text-gray-600">Cảm ơn bạn đã mua sản phẩm của chúng tôi.</p>
                </div>

                <!-- Order Details -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông tin đơn hàng</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                        <div>
                            <p class="text-sm text-gray-600">Mã đơn hàng:</p>
                            <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ngày đặt:</p>
                            <p class="font-medium text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tổng tiền:</p>
                            <p class="font-medium text-red-600">{{ number_format($order->total, 0, ',', '.') }}₫</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Trạng thái:</p>
                            <span class="inline-block px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">
                                Đang xử lý
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông tin giao hàng</h2>
                    <div class="text-left space-y-2">
                        <div>
                            <p class="text-sm text-gray-600">Họ và tên:</p>
                            <p class="font-medium text-gray-900">{{ $order->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Số điện thoại:</p>
                            <p class="font-medium text-gray-900">{{ $order->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email:</p>
                            <p class="font-medium text-gray-900">{{ $order->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Địa chỉ:</p>
                            <p class="font-medium text-gray-900">{{ $order->address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Sản phẩm đã đặt</h2>
                    @foreach($order->items as $item)
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset($item->book->image_url) }}" alt="{{ $item->book->title }}" 
                            class="w-16 h-16 object-cover rounded">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $item->book->title }}</h4>
                            <p class="text-sm text-gray-500">Số lượng: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">{{ number_format($item->total, 0, ',', '.') }}₫</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Next Steps -->
                <div class="bg-blue-50 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Những bước tiếp theo</h2>
                    <div class="text-left space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium flex-shrink-0 mt-0.5">1</div>
                            <div>
                                <p class="font-medium text-gray-900">Xác nhận đơn hàng</p>
                                <p class="text-sm text-gray-600">Chúng tôi sẽ gửi email xác nhận trong vòng 24 giờ</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium flex-shrink-0 mt-0.5">2</div>
                            <div>
                                <p class="font-medium text-gray-900">Chuẩn bị và giao hàng</p>
                                <p class="text-sm text-gray-600">Đơn hàng sẽ được chuẩn bị và giao trong 2-3 ngày làm việc</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium flex-shrink-0 mt-0.5">3</div>
                            <div>
                                <p class="font-medium text-gray-900">Nhận hàng</p>
                                <p class="text-sm text-gray-600">Nhân viên giao hàng sẽ liên hệ trước khi giao</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('home') }}" 
                        class="flex-1 bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition-colors font-semibold text-center">
                        Tiếp tục mua sắm
                    </a>
                    <button onclick="window.print()" 
                        class="flex-1 bg-gray-600 text-white py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors font-semibold">
                        In đơn hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
