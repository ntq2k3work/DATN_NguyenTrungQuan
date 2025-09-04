<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        // Lấy thông tin sản phẩm từ session hoặc request
        $bookId = $request->get('book_id');
        $quantity = $request->get('quantity', 1);
        
        if (!$bookId) {
            return redirect()->route('home')->with('error', 'Không tìm thấy sản phẩm');
        }

        $book = Book::with(['author', 'category', 'publisher', 'discount'])->findOrFail($bookId);
        
        // Tính giá sau giảm giá
        $price = $book->price;
        $percent = $book->discount?->percent ?? 0;
        $amount = $book->discount?->amount ?? 0;
        
        $book->final_price = $price - ($price * $percent / 100) - $amount;
        if ($book->final_price <= 0) {
            $book->final_price = 0;
            $book->discount_percent = 100;
        } else {
            $book->discount_percent = $percent;
        }

        // Tính tổng tiền
        $subtotal = $book->final_price * $quantity;
        $shipping = 30000; // Phí vận chuyển
        $total = $subtotal + $shipping;

        return view('pages.checkout.show', compact('book', 'quantity', 'subtotal', 'shipping', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'shipping_method' => 'required|in:standard,express',
            'payment_method' => 'required|in:cash,cod,bank_transfer',
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
            'discount_code' => 'nullable|string|max:50',
        ]);

        try {
            $book = Book::findOrFail($request->book_id);
            
            // Tính giá
            $price = $book->price;
            $percent = $book->discount?->percent ?? 0;
            $amount = $book->discount?->amount ?? 0;
            $finalPrice = $price - ($price * $percent / 100) - $amount;
            if ($finalPrice <= 0) $finalPrice = 0;

            $subtotal = $finalPrice * $request->quantity;
            $shipping = $request->shipping_method === 'express' ? 50000 : 30000;
            
            // Xử lý mã khuyến mãi
            $discountAmount = 0;
            if ($request->discount_code) {
                $coupon = \App\Models\Coupon::where('code', $request->discount_code)->first();
                
                if ($coupon && $coupon->isValid()) {
                    $discountAmount = $coupon->calculateDiscount($subtotal);
                }
            }
            
            $total = $subtotal + $shipping - $discountAmount;

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::check() ? Auth::id() : null, // Có thể null nếu user chưa đăng nhập
                'order_number' => 'ORD-' . time(),
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'shipping_method' => $request->shipping_method,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping,
                'total' => $total,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Tạo chi tiết đơn hàng
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $book->id,
                'quantity' => $request->quantity,
                'price' => $finalPrice,
                'total' => $finalPrice * $request->quantity,
            ]);

            // Cập nhật số lượng sách
            $book->decrement('quantity', $request->quantity);

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.');
        }
    }

    public function success($orderId)
    {
        $order = Order::with(['items.book'])->findOrFail($orderId);
        return view('pages.checkout.success', compact('order'));
    }

    public function checkDiscount(Request $request)
    {
        $code = $request->get('code');
        $subtotal = $request->get('subtotal', 0);
        
        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập mã khuyến mãi'
            ]);
        }
        
        $coupon = \App\Models\Coupon::where('code', $code)->first();
        
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại'
            ]);
        }
        
        if (!$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn'
            ]);
        }
        
        if ($subtotal < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng tối thiểu ' . number_format($coupon->min_order_amount, 0, ',', '.') . '₫ để áp dụng mã này'
            ]);
        }
        
        // Tính toán giảm giá
        $discountAmount = $coupon->calculateDiscount($subtotal);
        
        $discountText = '';
        if ($coupon->type === 'percentage') {
            $discountText = $coupon->value . '%';
        } else {
            $discountText = number_format($coupon->value, 0, ',', '.') . '₫';
        }
        
        return response()->json([
            'success' => true,
            'discount_amount' => $discountAmount,
            'discount_text' => $discountText,
            'message' => 'Áp dụng mã khuyến mãi thành công!'
        ]);
    }
}
