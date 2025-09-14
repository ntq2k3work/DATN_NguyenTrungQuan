@extends('layouts.app')

@section('title', 'Chính sách đổi trả - BookStore')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Chính sách đổi trả</h1>
            <p class="text-lg text-gray-600">Cam kết chất lượng và dịch vụ tốt nhất cho khách hàng</p>
        </div>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Điều kiện đổi trả -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-exchange-alt text-blue-500 mr-2"></i>
                    Điều kiện đổi trả
                </h2>
                <div class="space-y-4 text-gray-700">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-green-800 mb-2">Sách được đổi trả khi:</h3>
                        <ul class="space-y-2 ml-4">
                            <li>• Sách bị lỗi in ấn, thiếu trang, hoặc chất lượng giấy không đạt chuẩn</li>
                            <li>• Sách không đúng với mô tả trên website</li>
                            <li>• Sách bị hỏng trong quá trình vận chuyển</li>
                            <li>• Giao nhầm sách khác với đơn hàng</li>
                            <li>• Sách bị cũ, rách, hoặc có dấu hiệu sử dụng</li>
                        </ul>
                    </div>
                    
                    <div class="bg-red-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-red-800 mb-2">Sách không được đổi trả khi:</h3>
                        <ul class="space-y-2 ml-4">
                            <li>• Khách hàng thay đổi ý định mua</li>
                            <li>• Sách đã được sử dụng, có dấu hiệu đọc qua</li>
                            <li>• Sách bị hỏng do lỗi của khách hàng</li>
                            <li>• Đã quá thời hạn đổi trả (7 ngày)</li>
                            <li>• Sách thuộc danh mục không được đổi trả</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Thời gian đổi trả -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-clock text-blue-500 mr-2"></i>
                    Thời gian đổi trả
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">Đổi trả tại cửa hàng</h3>
                        <p class="text-blue-700">Trong vòng <strong>7 ngày</strong> kể từ ngày nhận hàng</p>
                        <p class="text-sm text-blue-600 mt-2">Giờ làm việc: 8:00 - 22:00 hàng ngày</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-green-800 mb-2">Đổi trả online</h3>
                        <p class="text-green-700">Trong vòng <strong>7 ngày</strong> kể từ ngày nhận hàng</p>
                        <p class="text-sm text-green-600 mt-2">Liên hệ hotline hoặc email hỗ trợ</p>
                    </div>
                </div>
            </div>

            <!-- Quy trình đổi trả -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list-ol text-blue-500 mr-2"></i>
                    Quy trình đổi trả
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                1
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Liên hệ hỗ trợ</h3>
                            <p class="text-gray-700">Gọi hotline 1900-123-456 hoặc gửi email đến support@bookstore.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                2
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Cung cấp thông tin</h3>
                            <p class="text-gray-700">Cung cấp mã đơn hàng, hình ảnh sách bị lỗi và lý do đổi trả</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                3
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Xác nhận đổi trả</h3>
                            <p class="text-gray-700">Nhân viên sẽ xác nhận và hướng dẫn cách thức đổi trả</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                4
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Gửi hàng về</h3>
                            <p class="text-gray-700">Đóng gói sách và gửi về địa chỉ được chỉ định</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                5
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Hoàn tất đổi trả</h3>
                            <p class="text-gray-700">Nhận sách mới hoặc hoàn tiền trong vòng 3-5 ngày làm việc</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phương thức hoàn tiền -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-money-bill-wave text-blue-500 mr-2"></i>
                    Phương thức hoàn tiền
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-blue-800 mb-2">Thanh toán bằng tiền mặt</h3>
                            <p class="text-blue-700">Hoàn tiền mặt tại cửa hàng hoặc qua chuyển khoản</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-green-800 mb-2">Thanh toán online</h3>
                            <p class="text-green-700">Hoàn tiền về tài khoản ngân hàng hoặc ví điện tử</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-yellow-800 mb-2">Thời gian hoàn tiền</h3>
                            <ul class="text-yellow-700 space-y-1">
                                <li>• Chuyển khoản: 1-3 ngày làm việc</li>
                                <li>• Ví điện tử: 1-2 ngày làm việc</li>
                                <li>• Tiền mặt: Ngay lập tức</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi phí đổi trả -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-shipping-fast text-blue-500 mr-2"></i>
                    Chi phí đổi trả
                </h2>
                <div class="space-y-4">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-green-800 mb-2">Miễn phí đổi trả</h3>
                        <ul class="text-green-700 space-y-1">
                            <li>• Sách bị lỗi từ nhà sản xuất</li>
                            <li>• Giao nhầm sách</li>
                            <li>• Sách không đúng mô tả</li>
                            <li>• Sách bị hỏng trong quá trình vận chuyển</li>
                        </ul>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-red-800 mb-2">Có phí đổi trả</h3>
                        <ul class="text-red-700 space-y-1">
                            <li>• Khách hàng thay đổi ý định: 20.000 VNĐ</li>
                            <li>• Đổi trả quá thời hạn: 30.000 VNĐ</li>
                            <li>• Sách bị hỏng do lỗi khách hàng: 50.000 VNĐ</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Lưu ý quan trọng -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6">
                <h2 class="text-xl font-semibold text-yellow-800 mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Lưu ý quan trọng
                </h2>
                <ul class="space-y-2 text-yellow-700">
                    <li>• Giữ nguyên tem niêm phong và bao bì gốc của sách</li>
                    <li>• Không viết, vẽ hoặc làm bẩn sách</li>
                    <li>• Chụp ảnh sách bị lỗi để làm bằng chứng</li>
                    <li>• Liên hệ hỗ trợ trước khi gửi hàng về</li>
                    <li>• Đóng gói cẩn thận để tránh hư hỏng thêm</li>
                </ul>
            </div>
        </div>

        <!-- Contact -->
        <div class="mt-8 text-center bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cần hỗ trợ đổi trả?</h3>
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-6">
                <a href="tel:1900123456" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-phone mr-2"></i>
                    Hotline: 1900-123-456
                </a>
                <a href="mailto:support@bookstore.com" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    Email: support@bookstore.com
                </a>
                <span class="text-gray-600 flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    Thời gian: 8:00 - 22:00
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
