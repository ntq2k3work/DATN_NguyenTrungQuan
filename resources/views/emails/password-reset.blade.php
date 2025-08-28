<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #dc2626;
        }
        .logo {
            width: 80px;
            height: 80px;
        }
        .content {
            padding: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #dc2626;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #b91c1c;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .warning {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="Bookstore Logo" class="logo">
        <h1 style="color: #dc2626; margin: 10px 0;">Đặt lại mật khẩu</h1>
    </div>

    <div class="content">
        <p>Xin chào <strong>{{ $user->name }}</strong>,</p>

        <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản Bookstore của mình.</p>

        <p>Vui lòng nhấn vào nút bên dưới để đặt lại mật khẩu:</p>

        <div style="text-align: center;">
            <a href="{{ $resetUrl }}" class="button">Đặt lại mật khẩu</a>
        </div>

        <p>Hoặc bạn có thể copy và paste link sau vào trình duyệt:</p>
        <p style="word-break: break-all; color: #dc2626;">{{ $resetUrl }}</p>

        <div class="warning">
            <strong>Lưu ý:</strong> Link này sẽ hết hạn sau 60 phút. Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.
        </div>

        <p>Nếu bạn gặp vấn đề khi nhấn vào nút trên, hãy copy và paste URL vào trình duyệt web.</p>

        <p>Trân trọng,<br>
        <strong>Đội ngũ Bookstore</strong></p>
    </div>

    <div class="footer">
        <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        <p>Nếu bạn có câu hỏi, vui lòng liên hệ với chúng tôi qua email hỗ trợ.</p>
    </div>
</body>
</html>
