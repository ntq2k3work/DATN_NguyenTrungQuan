@extends('layouts.app')

@section('title', 'Thông tin vận chuyển - BookStore')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Thông tin vận chuyển</h1>
            <p class="text-lg text-gray-600">Giao hàng nhanh chóng, an toàn và tiết kiệm</p>
        </div>

        <!-- Shipping Methods -->
        <div class="space-y-8">
            <!-- Standard Shipping -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-truck text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-3">Giao hàng tiêu chuẩn</h2>
                        <div class="space-y-4 text-gray-700">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h3 class="font-semibold text-blue-800 mb-2">Thông tin:</h3>
                                    <ul class="space-y-1 text-blue-700">
                                        <li>• Thời gian: 2-5 ngày làm việc</li>
                                        <li>• Phí vận chuyển: 25.000 VNĐ</li>
                                        <li>• Miễn phí từ 500.000 VNĐ</li>
                                        <li>• Giao hàng toàn quốc</li>
                                    </ul>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h3 class="font-semibold text-green-800 mb-2">Ưu điểm:</h3>
                                    <ul class="space-y-1 text-green-700">
                                        <li>• Tiết kiệm chi phí</li>
                                        <li>• Phù hợp đơn hàng lớn</li>
                                        <li>• Theo dõi đơn hàng</li>
                                        <li>• Bảo hiểm hàng hóa</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Express Shipping -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shipping-fast text-red-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-3">Giao hàng nhanh</h2>
                        <div class="space-y-4 text-gray-700">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="bg-red-50 p-4 rounded-lg">
                                    <h3 class="font-semibold text-red-800 mb-2">Thông tin:</h3>
                                    <ul class="space-y-1 text-red-700">
                                        <li>• Thời gian: 1-2 ngày làm việc</li>
                                        <li>• Phí vận chuyển: 50.000 VNĐ</li>
                                        <li>• Miễn phí từ 1.000.000 VNĐ</li>
                                        <li>• Giao hàng trong ngày (TP.HCM)</li>
                                    </ul>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h3 class="font-semibold text-green-800 mb-2">Ưu điểm:</h3>
                                    <ul class="space-y-1 text-green-700">
                                        <li>• Giao hàng siêu tốc</li>
                                        <li>• Phù hợp sách cần gấp</li>
                                        <li>• Giao hàng cuối tuần</li>
                                        <li>• Hỗ trợ giao giờ cụ thể</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Pickup -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-store text-green-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-3">Nhận tại cửa hàng</h2>
                        <div class="space-y-4 text-gray-700">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h3 class="font-semibold text-green-800 mb-2">Thông tin:</h3>
                                    <ul class="space-y-1 text-green-700">
                                        <li>• Thời gian: 1-2 ngày làm việc</li>
                                        <li>• Phí vận chuyển: Miễn phí</li>
                                        <li>• Giờ làm việc: 8:00 - 22:00</li>
                                        <li>• Có sẵn tại tất cả chi nhánh</li>
                                    </ul>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h3 class="font-semibold text-blue-800 mb-2">Ưu điểm:</h3>
                                    <ul class="space-y-1 text-blue-700">
                                        <li>• Tiết kiệm phí vận chuyển</li>
                                        <li>• Kiểm tra hàng trước khi nhận</li>
                                        <li>• Tư vấn thêm về sách</li>
                                        <li>• Mua thêm sách khác</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Areas -->
        <div class="mt-12 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
                <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                Khu vực giao hàng
            </h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-blue-800 mb-3">Miền Bắc</h3>
                    <ul class="space-y-1 text-blue-700 text-sm">
                        <li>• Hà Nội</li>
                        <li>• Hải Phòng</li>
                        <li>• Quảng Ninh</li>
                        <li>• Thái Nguyên</li>
                        <li>• Vĩnh Phúc</li>
                        <li>• Và các tỉnh lân cận</li>
                    </ul>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-green-800 mb-3">Miền Trung</h3>
                    <ul class="space-y-1 text-green-700 text-sm">
                        <li>• Đà Nẵng</li>
                        <li>• Huế</li>
                        <li>• Quảng Nam</li>
                        <li>• Quảng Ngãi</li>
                        <li>• Bình Định</li>
                        <li>• Và các tỉnh lân cận</li>
                    </ul>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-red-800 mb-3">Miền Nam</h3>
                    <ul class="space-y-1 text-red-700 text-sm">
                        <li>• TP. Hồ Chí Minh</li>
                        <li>• Bình Dương</li>
                        <li>• Đồng Nai</li>
                        <li>• Cần Thơ</li>
                        <li>• An Giang</li>
                        <li>• Và các tỉnh lân cận</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Shipping Timeline -->
        <div class="mt-12 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
                <i class="fas fa-clock text-blue-500 mr-2"></i>
                Quy trình giao hàng
            </h2>
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                            1
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Xác nhận đơn hàng</h3>
                        <p class="text-gray-700">Trong vòng 1-2 giờ sau khi đặt hàng</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                            2
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Chuẩn bị hàng</h3>
                        <p class="text-gray-700">Đóng gói và kiểm tra chất lượng sách</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                            3
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Giao cho đơn vị vận chuyển</h3>
                        <p class="text-gray-700">Chuyển hàng đến đơn vị vận chuyển</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                            4
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Vận chuyển</h3>
                        <p class="text-gray-700">Hàng được vận chuyển đến địa chỉ nhận</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                            ✓
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Giao hàng thành công</h3>
                        <p class="text-gray-700">Khách hàng nhận hàng và xác nhận</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Fees -->
        <div class="mt-12 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
                <i class="fas fa-calculator text-blue-500 mr-2"></i>
                Bảng phí vận chuyển
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="border border-gray-300 px-4 py-2 text-left">Khu vực</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Giao hàng tiêu chuẩn</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Giao hàng nhanh</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Miễn phí từ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Nội thành Hà Nội</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">20.000 VNĐ</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">40.000 VNĐ</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">300.000 VNĐ</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Nội thành TP.HCM</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">20.000 VNĐ</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">40.000 VNĐ</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">300.000 VNĐ</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Các tỉnh thành khác</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">25.000 VNĐ</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">50.000 VNĐ</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">500.000 VNĐ</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Vùng sâu, vùng xa</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">35.000 VNĐ</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">70.000 VNĐ</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">800.000 VNĐ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="mt-12 bg-yellow-50 border-l-4 border-yellow-400 p-6">
            <h2 class="text-xl font-semibold text-yellow-800 mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Lưu ý quan trọng
            </h2>
            <ul class="space-y-2 text-yellow-700">
                <li>• Thời gian giao hàng có thể thay đổi do thời tiết hoặc dịch vụ vận chuyển</li>
                <li>• Vui lòng cung cấp địa chỉ chính xác và số điện thoại liên lạc</li>
                <li>• Có người nhận hàng tại địa chỉ giao hàng trong giờ làm việc</li>
                <li>• Kiểm tra hàng trước khi ký nhận</li>
                <li>• Liên hệ hotline nếu có vấn đề về giao hàng</li>
                <li>• Không giao hàng vào ngày lễ, tết</li>
            </ul>
        </div>

        <!-- Contact -->
        <div class="mt-8 text-center bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">Cần hỗ trợ về vận chuyển?</h3>
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-6">
                <a href="tel:1900123456" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-phone mr-2"></i>
                    Hotline: 1900-123-456
                </a>
                <a href="mailto:shipping@bookstore.com" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    Email: shipping@bookstore.com
                </a>
                <span class="text-blue-600 flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    Hỗ trợ: 8:00 - 22:00
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
