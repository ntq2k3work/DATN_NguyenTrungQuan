<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sách mới: {{ $book->title }}</title>
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
        .book-info {
            display: flex;
            gap: 20px;
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .book-image {
            flex-shrink: 0;
            width: 150px;
            height: 200px;
            background-color: #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #6b7280;
        }
        .book-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        .book-details {
            flex: 1;
        }
        .book-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .book-author {
            color: #6b7280;
            margin-bottom: 10px;
        }
        .book-category {
            color: #6b7280;
            margin-bottom: 15px;
        }
        .book-price {
            font-size: 18px;
            font-weight: bold;
            color: #d97706;
            margin-bottom: 15px;
        }
        .book-description {
            color: #4b5563;
            margin-bottom: 20px;
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
        .highlight {
            background-color: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #d97706;
            margin: 20px 0;
        }
        .footer {
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .unsubscribe {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }
        .unsubscribe a {
            color: #6b7280;
            text-decoration: none;
            font-size: 12px;
        }
        .unsubscribe a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📚 BookStore</div>
            <div class="title">Sách mới vừa được thêm!</div>
        </div>

        <div class="content">
            <p>Xin chào,</p>

            <p>Chúng tôi có tin vui cho bạn! Một cuốn sách mới vừa được thêm vào BookStore và chúng tôi nghĩ bạn có thể quan tâm:</p>

            <div class="book-info">
                <div class="book-image">
                    @if($book->image_url)
                        <img src="{{ $book->image_url }}" alt="{{ $book->title }}">
                    @else
                        📖
                    @endif
                </div>
                <div class="book-details">
                    <div class="book-title">{{ $book->title }}</div>
                    <div class="book-author">
                        @if($book->author)
                            Tác giả: {{ $book->author->name }}
                        @endif
                    </div>
                    <div class="book-category">
                        @if($book->category)
                            Danh mục: {{ $book->category->name }}
                        @endif
                    </div>
                    <a href="{{ route('product.show', $book->slug) }}" class="button">Xem chi tiết</a>
                </div>
            </div>

            <p>Cảm ơn bạn đã quan tâm đến BookStore. Chúng tôi sẽ tiếp tục cập nhật những cuốn sách hay nhất cho bạn!</p>
        </div>

        <div class="footer">
            <p><strong>BookStore</strong> - Nơi tìm kiếm những cuốn sách hay</p>
            <p>Email này được gửi tự động từ hệ thống BookStore.</p>
            <p>&copy; {{ date('Y') }} BookStore. Tất cả quyền được bảo lưu.</p>
        </div>

        <div class="unsubscribe">
            <a href="{{ route('newsletter.unsubscribe', $unsubscribeToken) }}">Hủy đăng ký nhận email</a>
        </div>
    </div>
</body>
</html>
