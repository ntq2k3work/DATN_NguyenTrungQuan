<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .title {
            color: #28a745;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📚 BookStore</div>
            <div class="title">Xác thực Email Đăng ký</div>
        </div>

        <div class="content">
            <p>Xin chào <strong>{{ $user->name }}</strong>,</p>

            <p>Cảm ơn bạn đã đăng ký tài khoản tại BookStore! Để hoàn tất quá trình đăng ký, vui lòng xác thực địa chỉ email của bạn.</p>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">Xác thực Email</a>
            </div>

            <div class="warning">
                <strong>Lưu ý:</strong> Link xác thực này sẽ hết hạn sau 60 phút. Nếu bạn không thực hiện xác thực trong thời gian này, vui lòng đăng ký lại.
            </div>

            <p>Nếu bạn không thực hiện đăng ký tài khoản này, vui lòng bỏ qua email này.</p>

            <p>Nếu nút trên không hoạt động, bạn có thể copy và paste link sau vào trình duyệt:</p>
            <p style="word-break: break-all; color: #007bff;">{{ $verificationUrl }}</p>
        </div>

        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống BookStore.</p>
            <p>Vui lòng không trả lời email này.</p>
            <p>&copy; {{ date('Y') }} BookStore. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
