<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lỗi hủy đăng ký - BookStore</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #d97706;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            color: #dc2626;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background-color: #d97706;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 0;
        }
        .button:hover {
            background-color: #b45309;
        }
        .footer {
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📚 BookStore</div>
            <div class="title">Lỗi hủy đăng ký</div>
        </div>

        <div class="content">
            <p>Xin lỗi, chúng tôi không thể tìm thấy thông tin đăng ký của bạn.</p>
            <p>Token hủy đăng ký có thể đã hết hạn hoặc không hợp lệ.</p>
            <p>Nếu bạn vẫn muốn hủy đăng ký nhận email từ BookStore, vui lòng:</p>
            <ul style="text-align: left; max-width: 400px; margin: 0 auto;">
                <li>Kiểm tra lại email từ BookStore</li>
                <li>Sử dụng link hủy đăng ký trong email gần nhất</li>
                <li>Liên hệ với chúng tôi qua email: support@bookstore.vn</li>
            </ul>

            <div style="margin-top: 30px;">
                <a href="{{ route('home') }}" class="button">
                    Quay lại trang chủ
                </a>
            </div>
        </div>

        <div class="footer">
            <p><strong>BookStore</strong> - Nơi tìm kiếm những cuốn sách hay</p>
            <p>&copy; {{ date('Y') }} BookStore. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
