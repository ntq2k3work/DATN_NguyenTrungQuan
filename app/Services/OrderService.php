<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Jobs\ProcessOrderJob;
use App\Services\VNPayService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class OrderService
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * Prepare cart items for checkout
     */
    public function prepareCartItems($request, $user): Collection
    {
        $cartItems = collect();

        // Check if this is a "Buy Now" request
        if ($request->has('book_id') && $request->has('quantity')) {
            $cartItems = $this->prepareBuyNowItems($request);
        } else {
            $cartItems = $this->prepareCartItemsFromCart($user);
        }

        return $cartItems;
    }

    /**
     * Prepare buy now items
     */
    protected function prepareBuyNowItems($request): Collection
    {
        $bookId = $request->book_id;
        $quantity = $request->quantity;

        $book = Book::find($bookId);
        if (!$book) {
            return collect();
        }

        // Store buy now data in session
        Session::put('buy_now', [
            'book_id' => $bookId,
            'quantity' => $quantity,
            'price' => $book->price
        ]);

        return collect([(object) [
            'book' => $book,
            'quantity' => $quantity,
            'price' => $book->price,
            'book_id' => $bookId
        ]]);
    }

    /**
     * Prepare cart items from user's cart
     */
    protected function prepareCartItemsFromCart($user): Collection
    {
        $cartItems = collect();

        if ($user) {
            $cart = Cart::where('user_id', $user->id)->first();
            if ($cart) {
                $cartItems = CartItem::with('book')
                    ->where('cart_id', $cart->id)
                    ->get();
            }
        } else {
            // Handle guest cart from session
            $sessionCart = Session::get('cart', []);
            foreach ($sessionCart as $item) {
                $book = Book::find($item['book_id']);
                if ($book) {
                    $cartItems->push((object) [
                        'book' => $book,
                        'quantity' => $item['quantity'],
                        'price' => $book->price,
                        'book_id' => $book->id
                    ]);
                }
            }
        }

        return $cartItems;
    }

    /**
     * Validate stock availability for cart items
     */
    public function validateStockAvailability(Collection $cartItems): array
    {
        foreach ($cartItems as $item) {
            $book = Book::find($item->book_id);

            if (!$book) {
                return [
                    'valid' => false,
                    'message' => "Sản phẩm không tồn tại: " . ($item->book->title ?? 'Unknown')
                ];
            }

            if ($book->quantity < $item->quantity) {
                return [
                    'valid' => false,
                    'message' => "Không đủ số lượng tồn kho cho sản phẩm: {$book->title}. Số lượng còn lại: {$book->quantity}"
                ];
            }
        }

        return ['valid' => true];
    }

    /**
     * Calculate order totals
     */
    public function calculateOrderTotals(Collection $cartItems, string $shippingMethod): array
    {
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $shippingFee = $this->calculateShippingFee($shippingMethod, $subtotal);
        $total = $subtotal + $shippingFee;

        return [
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total' => $total
        ];
    }

    /**
     * Create order
     */
    public function createOrder(array $orderData, Collection $cartItems): Order
    {
        $totals = $this->calculateOrderTotals($cartItems, $orderData['shipping_method']);

        Log::info('Creating order', [
            'order_data' => $orderData,
            'totals' => $totals,
            'cart_items_count' => $cartItems->count()
        ]);

        $order = Order::create([
            'user_id' => $orderData['user_id'],
            'order_number' => Order::generateOrderNumber(),
            'full_name' => $orderData['full_name'],
            'phone' => $orderData['phone'],
            'email' => $orderData['email'],
            'address' => $orderData['address'],
            'shipping_method' => $orderData['shipping_method'],
            'payment_method' => $orderData['payment_method'],
            'subtotal' => $totals['subtotal'],
            'shipping_fee' => $totals['shipping_fee'],
            'total' => $totals['total'],
            'status' => 'pending',
            'payment_status' => 'pending',
            'notes' => $orderData['notes'] ?? null,
        ]);

        Log::info('Order created successfully', [
            'order_id' => $order->id,
            'order_number' => $order->order_number
        ]);

        return $order;
    }

    /**
     * Process order items and update stock
     */
    public function processOrderItems(Order $order, Collection $cartItems): void
    {
        $cartItemsData = $cartItems->map(function ($item) {
            return [
                'book_id' => $item->book_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ];
        })->toArray();

        // Dispatch job to process order items and update stock
        ProcessOrderJob::dispatch($order->toArray(), $cartItemsData);
    }

    /**
     * Clear cart after successful order
     */
    public function clearCart($user): void
    {
        if ($user) {
            $cart = Cart::where('user_id', $user->id)->first();
            if ($cart) {
                CartItem::where('cart_id', $cart->id)->delete();
            }
        } else {
            Session::forget('cart');
        }

        // Clear buy now session if exists
        Session::forget('buy_now');

        // Dispatch event to update header - using event system instead
        event('cart.updated');
    }

    /**
     * Handle VNPay payment
     */
    public function handleVNPayPayment(Order $order): string
    {
        $orderData = [
            'order_number' => $order->order_number,
            'amount' => $order->total,
            'order_info' => 'Thanh toan don hang ' . $order->order_number
        ];

        return $this->vnpayService->createPaymentUrl($orderData);
    }

    /**
     * Process VNPay callback
     */
    public function processVNPayCallback(array $callbackData): array
    {
        if (!$this->vnpayService->validateCallback($callbackData)) {
            return [
                'success' => false,
                'message' => 'Dữ liệu callback không hợp lệ'
            ];
        }

        $orderNumber = $callbackData['vnp_TxnRef'];
        $responseCode = $callbackData['vnp_ResponseCode'];
        $transactionStatus = $callbackData['vnp_TransactionStatus'];

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ];
        }

        // Check payment status
        if ($responseCode == '00' && $transactionStatus == '00') {
            // Payment successful
            $order->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'notes' => $order->notes . "\n\nVNPay Transaction ID: " . $callbackData['vnp_TransactionNo']
            ]);

            return [
                'success' => true,
                'message' => 'Thanh toán thành công!',
                'order' => $order
            ];
        } else {
            // Payment failed
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'failed',
                'notes' => $order->notes . "\n\nVNPay Error: " . $responseCode
            ]);

            return [
                'success' => false,
                'message' => 'Thanh toán thất bại. Vui lòng thử lại.',
                'order' => $order
            ];
        }
    }

    /**
     * Calculate shipping fee
     */
    private function calculateShippingFee(string $method, float $subtotal): float
    {
        switch ($method) {
            case 'standard':
                return $subtotal >= 500000 ? 0 : 30000;
            case 'express':
                return $subtotal >= 500000 ? 20000 : 50000;
            case 'pickup':
                return 0;
            default:
                return 30000;
        }
    }

    /**
     * Get order status history
     */
    public function getOrderStatusHistory(Order $order): array
    {
        $statusHistory = [
            'pending' => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'confirmed' => 'Đã xác nhận',
            'shipped' => 'Đã giao hàng',
            'delivered' => 'Đã nhận hàng',
            'cancelled' => 'Đã hủy',
            'returned' => 'Đã hoàn trả'
        ];

        return [
            'current_status' => $order->status,
            'current_status_text' => $statusHistory[$order->status] ?? 'Không xác định',
            'status_history' => $statusHistory,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at
        ];
    }
}
