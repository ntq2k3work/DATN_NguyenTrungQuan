<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng đến với BookStore Newsletter</title>
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
        }
        .header {
            text-align: center;
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
            color: #1f2937;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .highlight {
            background-color: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #d97706;
            margin: 20px 0;
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
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            color: #d97706;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📚 BookStore</div>
            <div class="title">Chào mừng bạn đến với Newsletter!</div>
        </div>

        <div class="content">
            <p>Xin chào,</p>

            <p>Cảm ơn bạn đã đăng ký nhận thông tin sách mới từ <strong>BookStore</strong>! Chúng tôi rất vui được chia sẻ những cuốn sách hay và những ưu đãi đặc biệt với bạn.</p>

            <div class="highlight">
                <strong>🎉 Những gì bạn sẽ nhận được:</strong>
                <ul>
                    <li>Thông báo về những cuốn sách mới nhất</li>
                    <li>Ưu đãi đặc biệt và khuyến mãi</li>
                    <li>Gợi ý sách hay theo sở thích</li>
                    <li>Thông tin về các sự kiện và chương trình đặc biệt</li>
                </ul>
            </div>

            <p>Chúng tôi sẽ gửi email cho bạn mỗi khi có sách mới được thêm vào cửa hàng. Bạn có thể yên tâm rằng chúng tôi sẽ không spam và chỉ gửi những thông tin hữu ích.</p>

            <div style="text-align: center;">
                <a href="{{ route('home') }}" class="button">Khám phá BookStore ngay</a>
            </div>

            <div class="social-links">
                <p>Theo dõi chúng tôi trên:</p>
                <a href="#">Facebook</a> |
                <a href="#">Instagram</a> |
                <a href="#">Twitter</a>
            </div>
        </div>

        <div class="footer">
            <p><strong>BookStore</strong> - Nơi tìm kiếm những cuốn sách hay</p>
            <p>Email này được gửi tự động từ hệ thống BookStore.</p>
            <p>Nếu bạn không muốn nhận email này nữa, bạn có thể <a href="#">hủy đăng ký</a>.</p>
            <p>&copy; {{ date('Y') }} BookStore. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
