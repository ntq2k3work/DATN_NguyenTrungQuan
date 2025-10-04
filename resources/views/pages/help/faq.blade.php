@extends('layouts.app')

@section('title', 'Câu hỏi thường gặp - BookStore')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Câu hỏi thường gặp (FAQ)</h1>
            <p class="text-lg text-gray-600">Tìm câu trả lời cho các thắc mắc phổ biến</p>
        </div>

        <!-- Search Box -->
        <div class="mb-8">
            <div class="relative max-w-md mx-auto">
                <input 
                    type="text" 
                    id="faq-search" 
                    placeholder="Tìm kiếm câu hỏi..." 
                    class="w-full px-4 py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- FAQ Categories -->
        <div class="space-y-8">
            <!-- Account & Registration -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user text-blue-500 mr-3"></i>
                        Tài khoản & Đăng ký
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Làm thế nào để đăng ký tài khoản?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Bạn có thể đăng ký tài khoản bằng cách:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Click vào nút "Đăng ký" ở góc trên bên phải</li>
                                <li>Điền đầy đủ thông tin: họ tên, email, số điện thoại, mật khẩu</li>
                                <li>Xác thực email để kích hoạt tài khoản</li>
                                <li>Hoàn tất đăng ký và bắt đầu mua sắm</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Tôi quên mật khẩu, làm sao để lấy lại?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Để lấy lại mật khẩu:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Click "Quên mật khẩu" trên trang đăng nhập</li>
                                <li>Nhập email đã đăng ký</li>
                                <li>Kiểm tra email và click link đặt lại mật khẩu</li>
                                <li>Tạo mật khẩu mới và đăng nhập lại</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Có thể thay đổi thông tin tài khoản không?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Có, bạn có thể thay đổi thông tin tài khoản:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Đăng nhập vào tài khoản</li>
                                <li>Vào "Thông tin cá nhân"</li>
                                <li>Cập nhật thông tin cần thiết</li>
                                <li>Lưu thay đổi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shopping -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-shopping-cart text-green-500 mr-3"></i>
                        Mua sắm
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Làm thế nào để tìm sách tôi muốn?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Bạn có thể tìm sách bằng nhiều cách:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Sử dụng thanh tìm kiếm với tên sách, tác giả hoặc ISBN</li>
                                <li>Duyệt theo danh mục sách</li>
                                <li>Xem sách bán chạy nhất</li>
                                <li>Xem sách mới phát hành</li>
                                <li>Sử dụng bộ lọc theo giá, tác giả, nhà xuất bản</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Có thể đặt hàng mà không cần đăng ký không?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Không, bạn cần đăng ký tài khoản để đặt hàng. Điều này giúp:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Theo dõi đơn hàng dễ dàng</li>
                                <li>Lưu thông tin giao hàng</li>
                                <li>Nhận thông báo về đơn hàng</li>
                                <li>Tích điểm và nhận ưu đãi</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Làm thế nào để sử dụng mã giảm giá?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Để sử dụng mã giảm giá:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Thêm sách vào giỏ hàng</li>
                                <li>Vào trang giỏ hàng</li>
                                <li>Nhập mã giảm giá vào ô "Mã giảm giá"</li>
                                <li>Click "Áp dụng" để xem giảm giá</li>
                                <li>Tiến hành thanh toán</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-credit-card text-purple-500 mr-3"></i>
                        Thanh toán
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Các phương thức thanh toán nào được hỗ trợ?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Chúng tôi hỗ trợ các phương thức thanh toán:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Thanh toán khi nhận hàng (COD)</li>
                                <li>Chuyển khoản ngân hàng</li>
                                <li>VNPay (ví điện tử)</li>
                                <li>Thẻ tín dụng/ghi nợ</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Thanh toán có an toàn không?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Rất an toàn! Chúng tôi sử dụng:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Mã hóa SSL 256-bit</li>
                                <li>Xác thực 2 lớp (OTP)</li>
                                <li>Tuân thủ tiêu chuẩn PCI DSS</li>
                                <li>Không lưu trữ thông tin thẻ</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Khi nào tôi sẽ được hoàn tiền?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Thời gian hoàn tiền tùy thuộc vào phương thức:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Chuyển khoản: 1-3 ngày làm việc</li>
                                <li>Ví điện tử: 1-2 ngày làm việc</li>
                                <li>Tiền mặt: Ngay lập tức</li>
                                <li>Thẻ tín dụng: 3-7 ngày làm việc</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-truck text-orange-500 mr-3"></i>
                        Vận chuyển
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Thời gian giao hàng là bao lâu?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Thời gian giao hàng:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Giao hàng tiêu chuẩn: 2-5 ngày làm việc</li>
                                <li>Giao hàng nhanh: 1-2 ngày làm việc</li>
                                <li>Nhận tại cửa hàng: 1-2 ngày làm việc</li>
                                <li>Giao trong ngày (TP.HCM): Có thể</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Phí vận chuyển là bao nhiêu?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Phí vận chuyển:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Nội thành: 20.000 VNĐ</li>
                                <li>Các tỉnh thành khác: 25.000 VNĐ</li>
                                <li>Vùng sâu vùng xa: 35.000 VNĐ</li>
                                <li>Miễn phí từ 300.000 VNĐ (nội thành) hoặc 500.000 VNĐ (các tỉnh)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Có thể thay đổi địa chỉ giao hàng không?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Có thể thay đổi địa chỉ giao hàng:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Trước khi hàng được giao cho đơn vị vận chuyển</li>
                                <li>Liên hệ hotline để được hỗ trợ</li>
                                <li>Có thể mất thêm phí nếu thay đổi khu vực</li>
                                <li>Thời gian giao hàng có thể thay đổi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Returns -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-undo text-red-500 mr-3"></i>
                        Đổi trả
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Khi nào tôi có thể đổi trả sách?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Bạn có thể đổi trả khi:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Sách bị lỗi in ấn hoặc thiếu trang</li>
                                <li>Sách không đúng với mô tả</li>
                                <li>Sách bị hỏng trong quá trình vận chuyển</li>
                                <li>Giao nhầm sách khác</li>
                                <li>Trong vòng 7 ngày kể từ ngày nhận hàng</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Quy trình đổi trả như thế nào?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Quy trình đổi trả:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Liên hệ hotline hoặc email hỗ trợ</li>
                                <li>Cung cấp mã đơn hàng và hình ảnh sách bị lỗi</li>
                                <li>Nhân viên xác nhận và hướng dẫn</li>
                                <li>Gửi hàng về địa chỉ chỉ định</li>
                                <li>Nhận sách mới hoặc hoàn tiền</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question w-full text-left p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Ai chịu phí vận chuyển khi đổi trả?</span>
                            <i class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                        <div class="faq-answer hidden p-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700">Phí vận chuyển đổi trả:</p>
                            <ul class="mt-2 space-y-1 text-gray-700 list-disc list-inside">
                                <li>Miễn phí: Sách bị lỗi từ nhà sản xuất</li>
                                <li>Miễn phí: Giao nhầm sách</li>
                                <li>Miễn phí: Sách không đúng mô tả</li>
                                <li>Có phí: Khách hàng thay đổi ý định (20.000 VNĐ)</li>
                                <li>Có phí: Đổi trả quá thời hạn (30.000 VNĐ)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="mt-12 bg-blue-50 rounded-lg p-6 text-center">
            <h3 class="text-xl font-semibold text-blue-900 mb-4">Không tìm thấy câu trả lời?</h3>
            <p class="text-blue-700 mb-6">Đội ngũ hỗ trợ của chúng tôi luôn sẵn sàng giúp đỡ bạn!</p>
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-3 sm:space-y-0 sm:space-x-6">
                <a href="tel:1900123456" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-phone mr-2"></i>
                    Gọi hotline: 1900-123-456
                </a>
                <a href="mailto:support@bookstore.com" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    Email hỗ trợ
                </a>
                <button class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors duration-200 flex items-center" onclick="openChatbot()">
                    <i class="fas fa-comments mr-2"></i>
                    Chat trực tuyến
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Toggle functionality
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('i');
            
            // Close other open FAQs
            faqQuestions.forEach(otherQuestion => {
                if (otherQuestion !== this) {
                    const otherAnswer = otherQuestion.nextElementSibling;
                    const otherIcon = otherQuestion.querySelector('i');
                    otherAnswer.classList.add('hidden');
                    otherIcon.style.transform = 'rotate(0deg)';
                }
            });
            
            // Toggle current FAQ
            if (answer.classList.contains('hidden')) {
                answer.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                answer.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('faq-search');
    const faqItems = document.querySelectorAll('.faq-item');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question span').textContent.toLowerCase();
            const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

function openChatbot() {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    if (chatbotToggle) {
        chatbotToggle.click();
    }
}
</script>
@endpush
@endsection
