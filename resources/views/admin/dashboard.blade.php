
@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Chào mừng, {{ Auth::user()->name }}!</h2>
                <p class="text-blue-100 mt-1">Đây là trang quản trị của BookStore</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-blue-100">Hôm nay</p>
                <p class="text-lg font-semibold">{{ date('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tổng người dùng</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>
                    +12% so với tháng trước
                </span>
            </div>
        </div>

        <!-- Total Books -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tổng sách</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalBooks) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>
                    +8% so với tháng trước
                </span>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tổng đơn hàng</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>
                    +15% so với tháng trước
                </span>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Đơn hàng chờ xử lý</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($pendingOrders) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-red-600">
                    <i class="fas fa-arrow-down mr-1"></i>
                    -5% so với tháng trước
                </span>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Đơn hàng gần đây</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @php
                        $recentOrders = \App\Models\Order::with('user')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-shopping-bag text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-600">{{ $order->full_name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">{{ number_format($order->total) }}₫</p>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Chưa có đơn hàng nào</p>
                    </div>
                    @endforelse
                </div>
                
                <div class="mt-6">
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Xem tất cả đơn hàng <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Thao tác nhanh</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <a href="#" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Thêm sách mới</p>
                            <p class="text-sm text-gray-600">Tạo sản phẩm mới</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-tags text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Quản lý danh mục</p>
                            <p class="text-sm text-gray-600">Thêm/sửa danh mục</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                        <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-percent text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Khuyến mãi</p>
                            <p class="text-sm text-gray-600">Tạo mã giảm giá</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Quản lý người dùng</p>
                            <p class="text-sm text-gray-600">Xem danh sách user</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Trạng thái hệ thống</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Database: Hoạt động bình thường</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Email Service: Hoạt động bình thường</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">File Storage: Hoạt động bình thường</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
