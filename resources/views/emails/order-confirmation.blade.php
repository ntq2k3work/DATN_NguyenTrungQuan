<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
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
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .order-info {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-items {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .item-row:last-child {
            border-bottom: none;
        }
        .total-row {
            font-weight: bold;
            font-size: 18px;
            color: #d97706;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            font-size: 14px;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #d97706;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
        }
        .btn:hover {
            background-color: #b45309;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="color: #d97706; margin: 0;">BookStore</h1>
        <h2 style="margin: 10px 0 0 0;">Xác nhận đơn hàng thành công!</h2>
    </div>

    <div class="order-info">
        <h3 style="margin-top: 0; color: #333;">Thông tin đơn hàng</h3>
        <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Trạng thái:</strong> {{ $order->status_label }}</p>
        <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method_label }}</p>
        <p><strong>Phương thức giao hàng:</strong> {{ $order->shipping_method_label }}</p>
    </div>

    <div class="order-info">
        <h3 style="margin-top: 0; color: #333;">Thông tin giao hàng</h3>
        <p><strong>Họ tên:</strong> {{ $order->full_name }}</p>
        <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
        @if($order->notes)
        <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
        @endif
    </div>

    <div class="order-items">
        <h3 style="margin-top: 0; color: #333;">Chi tiết đơn hàng</h3>
        @foreach($order->items as $item)
        <div class="item-row">
            <div>
                <strong>{{ $item->book->title }}</strong><br>
                <small>Số lượng: {{ $item->quantity }}</small>
            </div>
            <div>
                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
            </div>
        </div>
        @endforeach
        
        <div class="item-row">
            <div>Tạm tính:</div>
            <div>{{ number_format($order->subtotal, 0, ',', '.') }}đ</div>
        </div>
        
        <div class="item-row">
            <div>Phí vận chuyển:</div>
            <div>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</div>
        </div>
        
        <div class="item-row total-row">
            <div>Tổng cộng:</div>
            <div>{{ number_format($order->total, 0, ',', '.') }}đ</div>
        </div>
    </div>

    <div style="text-align: center; margin: 20px 0;">
        @if($order->isPlacedByAuthenticatedUser())
            <a href="{{ route('orders.track') }}" class="btn">Theo dõi đơn hàng</a>
        @endif
        <a href="{{ route('home') }}" class="btn" style="background-color: #6b7280;">Về trang chủ</a>
    </div>

    <div class="footer">
        <p>Cảm ơn bạn đã đặt hàng tại BookStore!</p>
        <p>Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>
        <p>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.</p>
    </div>
</body>
</html>
