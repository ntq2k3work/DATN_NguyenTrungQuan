<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hủy đăng ký Newsletter - BookStore</title>
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
            color: #1f2937;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background-color: #dc2626;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 0;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background-color: #b91c1c;
        }
        .button-secondary {
            background-color: #6b7280;
        }
        .button-secondary:hover {
            background-color: #4b5563;
        }
        .footer {
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .message {
            margin: 20px 0;
            padding: 15px;
            border-radius: 8px;
            display: none;
        }
        .message.success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .message.error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📚 BookStore</div>
            <div class="title">Hủy đăng ký Newsletter</div>
        </div>

        <div class="content">
            <p>Xin chào,</p>
            <p>Bạn đang muốn hủy đăng ký nhận thông tin sách mới từ BookStore?</p>
            <p>Email: <strong>{{ $subscription->email }}</strong></p>
            <p>Nếu bạn không muốn nhận email thông báo về sách mới nữa, vui lòng nhấn nút bên dưới:</p>

            <div id="message" class="message"></div>

            <form id="unsubscribe-form" method="POST">
                @csrf
                <button type="submit" class="button" id="unsubscribe-btn">
                    Hủy đăng ký
                </button>
            </form>

            <div style="margin-top: 20px;">
                <a href="{{ route('home') }}" class="button button-secondary">
                    Quay lại trang chủ
                </a>
            </div>
        </div>

        <div class="footer">
            <p><strong>BookStore</strong> - Nơi tìm kiếm những cuốn sách hay</p>
            <p>&copy; {{ date('Y') }} BookStore. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>

    <script>
        document.getElementById('unsubscribe-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('unsubscribe-btn');
            const message = document.getElementById('message');
            
            btn.disabled = true;
            btn.textContent = 'Đang xử lý...';
            
            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                       document.querySelector('input[name="_token"]')?.value
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    message.textContent = data.message;
                    message.className = 'message success';
                    message.style.display = 'block';
                    btn.style.display = 'none';
                } else {
                    message.textContent = data.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
                    message.className = 'message error';
                    message.style.display = 'block';
                }
            } catch (error) {
                message.textContent = 'Có lỗi xảy ra. Vui lòng thử lại.';
                message.className = 'message error';
                message.style.display = 'block';
            } finally {
                btn.disabled = false;
                btn.textContent = 'Hủy đăng ký';
            }
        });
    </script>
</body>
</html>
