@extends('layouts.app')

@section('title', 'Phương thức thanh toán - BookStore')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Phương thức thanh toán</h1>
            <p class="text-lg text-gray-600">Nhiều lựa chọn thanh toán an toàn và tiện lợi</p>
        </div>

        <!-- Payment Methods -->
        <div class="space-y-8">
            <!-- COD -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-3">Thanh toán khi nhận hàng (COD)</h2>
                        <div class="space-y-3 text-gray-700">
                            <p class="text-lg">Phương thức thanh toán phổ biến nhất, an toàn và tiện lợi.</p>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-green-800 mb-2">Ưu điểm:</h3>
                                <ul class="space-y-1 text-green-700">
                                    <li>• Không cần thẻ ngân hàng hoặc tài khoản online</li>
                                    <li>• Kiểm tra hàng trước khi thanh toán</li>
                                    <li>• An toàn tuyệt đối</li>
                                    <li>• Phù hợp với mọi đối tượng khách hàng</li>
                                </ul>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-yellow-800 mb-2">Lưu ý:</h3>
                                <ul class="space-y-1 text-yellow-700">
                                    <li>• Chuẩn bị đủ tiền mặt khi nhận hàng</li>
                                    <li>• Kiểm tra hàng trước khi thanh toán</li>
                                    <li>• Có thể mất thêm thời gian giao hàng</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Transfer -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-university text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-3">Chuyển khoản ngân hàng</h2>
                        <div class="space-y-3 text-gray-700">
                            <p class="text-lg">Thanh toán nhanh chóng qua chuyển khoản ngân hàng.</p>
                            
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-blue-800 mb-3">Thông tin tài khoản:</h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="font-semibold">Ngân hàng Vietcombank</p>
                                        <p>Số TK: 1234567890</p>
                                        <p>Chủ TK: CÔNG TY TNHH BOOKSTORE</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Ngân hàng BIDV</p>
                                        <p>Số TK: 0987654321</p>
                                        <p>Chủ TK: CÔNG TY TNHH BOOKSTORE</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-green-800 mb-2">Ưu điểm:</h3>
                                <ul class="space-y-1 text-green-700">
                                    <li>• Thanh toán nhanh chóng</li>
                                    <li>• An toàn và bảo mật</li>
                                    <li>• Không mất phí giao dịch</li>
                                    <li>• Có thể thanh toán từ xa</li>
                                </ul>
                            </div>

                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-yellow-800 mb-2">Hướng dẫn:</h3>
                                <ol class="space-y-1 text-yellow-700 list-decimal list-inside">
                                    <li>Chọn phương thức "Chuyển khoản ngân hàng"</li>
                                    <li>Chuyển khoản theo thông tin tài khoản trên</li>
                                    <li>Ghi rõ mã đơn hàng trong nội dung chuyển khoản</li>
                                    <li>Gửi ảnh biên lai chuyển khoản qua email hoặc Zalo</li>
                                    <li>Đơn hàng sẽ được xử lý trong 1-2 giờ làm việc</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VNPay -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-credit-card text-red-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-3">VNPay - Ví điện tử</h2>
                        <div class="space-y-3 text-gray-700">
                            <p class="text-lg">Thanh toán nhanh chóng và an toàn qua ví điện tử VNPay.</p>
                            
                            <div class="bg-red-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-red-800 mb-2">Các phương thức hỗ trợ:</h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <ul class="space-y-1 text-red-700">
                                            <li>• Thẻ ATM nội địa</li>
                                            <li>• Thẻ tín dụng quốc tế</li>
                                            <li>• Ví điện tử VNPay</li>
                                            <li>• Internet Banking</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <ul class="space-y-1 text-red-700">
                                            <li>• QR Code</li>
                                            <li>• Mobile Banking</li>
                                            <li>• Thẻ ghi nợ</li>
                                            <li>• Ví điện tử khác</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-green-800 mb-2">Ưu điểm:</h3>
                                <ul class="space-y-1 text-green-700">
                                    <li>• Thanh toán tức thì</li>
                                    <li>• Bảo mật cao với mã OTP</li>
                                    <li>• Hỗ trợ nhiều ngân hàng</li>
                                    <li>• Giao diện thân thiện</li>
                                </ul>
                            </div>

                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-yellow-800 mb-2">Hướng dẫn:</h3>
                                <ol class="space-y-1 text-yellow-700 list-decimal list-inside">
                                    <li>Chọn phương thức "VNPay"</li>
                                    <li>Chọn ngân hàng hoặc ví điện tử</li>
                                    <li>Nhập thông tin thẻ/tài khoản</li>
                                    <li>Xác thực bằng mã OTP</li>
                                    <li>Hoàn tất thanh toán</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Credit/Debit Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-credit-card text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-3">Thẻ tín dụng/Ghi nợ</h2>
                        <div class="space-y-3 text-gray-700">
                            <p class="text-lg">Thanh toán quốc tế an toàn với các thẻ Visa, Mastercard.</p>
                            
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-purple-800 mb-2">Các loại thẻ hỗ trợ:</h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <ul class="space-y-1 text-purple-700">
                                            <li>• Visa</li>
                                            <li>• Mastercard</li>
                                            <li>• JCB</li>
                                            <li>• American Express</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <ul class="space-y-1 text-purple-700">
                                            <li>• Thẻ tín dụng</li>
                                            <li>• Thẻ ghi nợ</li>
                                            <li>• Thẻ trả trước</li>
                                            <li>• Thẻ ảo</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-green-800 mb-2">Ưu điểm:</h3>
                                <ul class="space-y-1 text-green-700">
                                    <li>• Thanh toán nhanh chóng</li>
                                    <li>• Bảo mật cao với 3D Secure</li>
                                    <li>• Hỗ trợ thanh toán quốc tế</li>
                                    <li>• Tích điểm thưởng từ ngân hàng</li>
                                </ul>
                            </div>

                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-yellow-800 mb-2">Lưu ý:</h3>
                                <ul class="space-y-1 text-yellow-700">
                                    <li>• Đảm bảo thẻ có đủ hạn mức</li>
                                    <li>• Kiểm tra thông tin thẻ chính xác</li>
                                    <li>• Có thể mất phí giao dịch quốc tế</li>
                                    <li>• Liên hệ ngân hàng nếu gặp vấn đề</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security -->
        <div class="mt-12 bg-gray-50 rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4 text-center">
                <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                Bảo mật thanh toán
            </h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-lock text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Mã hóa SSL</h3>
                    <p class="text-gray-600 text-sm">Tất cả thông tin thanh toán được mã hóa an toàn</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user-shield text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Xác thực 2 lớp</h3>
                    <p class="text-gray-600 text-sm">Sử dụng mã OTP để xác thực giao dịch</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-certificate text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Chứng nhận PCI DSS</h3>
                    <p class="text-gray-600 text-sm">Tuân thủ tiêu chuẩn bảo mật quốc tế</p>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="mt-8 text-center bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">Cần hỗ trợ thanh toán?</h3>
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-6">
                <a href="tel:1900123456" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-phone mr-2"></i>
                    Hotline: 1900-123-456
                </a>
                <a href="mailto:payment@bookstore.com" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    Email: payment@bookstore.com
                </a>
                <span class="text-blue-600 flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    Hỗ trợ 24/7
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
