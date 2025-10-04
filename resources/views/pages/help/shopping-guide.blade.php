@extends('layouts.app')

@section('title', 'Hướng dẫn mua hàng - BookStore')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Hướng dẫn mua hàng</h1>
            <p class="text-lg text-gray-600">Các bước đơn giản để mua sách tại BookStore</p>
        </div>

        <!-- Steps -->
        <div class="space-y-8">
            <!-- Step 1 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg">
                            1
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Tìm kiếm sách</h3>
                        <div class="space-y-3 text-gray-700">
                            <p>• Sử dụng thanh tìm kiếm ở đầu trang để tìm sách theo tên, tác giả hoặc ISBN</p>
                            <p>• Duyệt qua các danh mục sách phổ biến như:</p>
                            <ul class="ml-6 space-y-1">
                                <li>- Sách bán chạy nhất</li>
                                <li>- Sách mới phát hành</li>
                                <li>- Sách được đề xuất</li>
                                <li>- Sách theo thể loại</li>
                            </ul>
                            <p>• Sử dụng bộ lọc để tìm sách theo giá, tác giả, nhà xuất bản</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg">
                            2
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Xem chi tiết sách</h3>
                        <div class="space-y-3 text-gray-700">
                            <p>• Click vào sách để xem thông tin chi tiết</p>
                            <p>• Kiểm tra:</p>
                            <ul class="ml-6 space-y-1">
                                <li>- Giá sách và các chương trình khuyến mãi</li>
                                <li>- Mô tả chi tiết và nội dung sách</li>
                                <li>- Thông tin tác giả và nhà xuất bản</li>
                                <li>- Đánh giá và nhận xét từ khách hàng</li>
                                <li>- Tình trạng còn hàng</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg">
                            3
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Thêm vào giỏ hàng</h3>
                        <div class="space-y-3 text-gray-700">
                            <p>• Click nút "Thêm vào giỏ hàng" để thêm sách vào giỏ</p>
                            <p>• Chọn số lượng sách muốn mua</p>
                            <p>• Có thể tiếp tục mua sắm hoặc vào giỏ hàng để thanh toán</p>
                            <p>• Sử dụng tính năng "Yêu thích" để lưu sách quan tâm</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg">
                            4
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Đăng nhập/Tạo tài khoản</h3>
                        <div class="space-y-3 text-gray-700">
                            <p>• Nếu chưa có tài khoản, click "Đăng ký" để tạo tài khoản mới</p>
                            <p>• Nếu đã có tài khoản, click "Đăng nhập"</p>
                            <p>• Thông tin cần thiết:</p>
                            <ul class="ml-6 space-y-1">
                                <li>- Họ tên</li>
                                <li>- Email</li>
                                <li>- Số điện thoại</li>
                                <li>- Mật khẩu</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg">
                            5
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Kiểm tra giỏ hàng</h3>
                        <div class="space-y-3 text-gray-700">
                            <p>• Vào giỏ hàng để kiểm tra các sách đã chọn</p>
                            <p>• Có thể thay đổi số lượng hoặc xóa sách không cần thiết</p>
                            <p>• Kiểm tra tổng tiền và phí vận chuyển</p>
                            <p>• Áp dụng mã giảm giá nếu có</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 6 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg">
                            6
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Nhập thông tin giao hàng</h3>
                        <div class="space-y-3 text-gray-700">
                            <p>• Nhập địa chỉ giao hàng chính xác</p>
                            <p>• Chọn phương thức vận chuyển phù hợp</p>
                            <p>• Thông tin cần thiết:</p>
                            <ul class="ml-6 space-y-1">
                                <li>- Họ tên người nhận</li>
                                <li>- Số điện thoại</li>
                                <li>- Địa chỉ chi tiết</li>
                                <li>- Tỉnh/Thành phố</li>
                                <li>- Quận/Huyện</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 7 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg">
                            7
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Chọn phương thức thanh toán</h3>
                        <div class="space-y-3 text-gray-700">
                            <p>• Chọn phương thức thanh toán phù hợp:</p>
                            <ul class="ml-6 space-y-1">
                                <li>- Thanh toán khi nhận hàng (COD)</li>
                                <li>- Chuyển khoản ngân hàng</li>
                                <li>- Ví điện tử VNPay</li>
                                <li>- Thẻ tín dụng/ghi nợ</li>
                            </ul>
                            <p>• Kiểm tra lại thông tin đơn hàng trước khi thanh toán</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 8 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg">
                            8
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Xác nhận đơn hàng</h3>
                        <div class="space-y-3 text-gray-700">
                            <p>• Click "Đặt hàng" để xác nhận</p>
                            <p>• Lưu mã đơn hàng để theo dõi</p>
                            <p>• Kiểm tra email xác nhận đơn hàng</p>
                            <p>• Theo dõi trạng thái đơn hàng trong tài khoản</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="mt-12 bg-blue-50 rounded-lg p-6">
            <h3 class="text-xl font-semibold text-blue-900 mb-4">
                <i class="fas fa-lightbulb mr-2"></i>
                Mẹo mua sách tiết kiệm
            </h3>
            <div class="grid md:grid-cols-2 gap-4 text-blue-800">
                <div>
                    <p>• Đăng ký nhận bản tin để nhận thông báo về sách mới và khuyến mãi</p>
                    <p>• Theo dõi các chương trình giảm giá đặc biệt</p>
                </div>
                <div>
                    <p>• Mua combo sách để được giảm giá</p>
                    <p>• Tích điểm để đổi quà tặng</p>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="mt-8 text-center">
            <p class="text-gray-600 mb-4">Cần hỗ trợ thêm?</p>
            <div class="flex justify-center space-x-4">
                <a href="mailto:support@bookstore.com" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-envelope mr-1"></i>
                    Email hỗ trợ
                </a>
                <a href="tel:1900123456" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-phone mr-1"></i>
                    Hotline: 1900-123-456
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
