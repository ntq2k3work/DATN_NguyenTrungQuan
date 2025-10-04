<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessOrderJob;
use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\VNPayService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct()
    {
        $this->orderService = app(OrderService::class);
    }

    /**
     * Show checkout page
     */
    public function checkout(Request $request)
    {
        try {
            $user = Auth::user();
            $cartItems = $this->orderService->prepareCartItems($request, $user);

            if ($cartItems->isEmpty()) {
                return redirect()->route('home')->with('error', 'Giỏ hàng trống');
            }

            // Validate stock availability
            $stockValidation = $this->orderService->validateStockAvailability($cartItems);
            if (!$stockValidation['valid']) {
                return redirect()->back()->with('error', $stockValidation['message']);
            }

            return view('pages.checkout', compact('cartItems', 'user'));
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Có lỗi xảy ra khi tải trang thanh toán');
        }
    }

    /**
     * Xử lý thanh toán VNPay
     */
    public function handleVNPay(Request $request)
    {
        try {
            $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'address' => 'required|string',
                'shipping_method' => 'required|in:standard,express,pickup',
                'notes' => 'nullable|string',
            ]);

            $user = Auth::user();
            $cartItems = $this->orderService->prepareCartItems($request, $user);

            if ($cartItems->isEmpty()) {
                return redirect()->route('home')->with('error', 'Giỏ hàng trống');
            }

            // Validate stock availability
            $stockValidation = $this->orderService->validateStockAvailability($cartItems);
            if (!$stockValidation['valid']) {
                return redirect()->back()->with('error', $stockValidation['message']);
            }

            // Create order
            $orderData = [
                'user_id' => $user ? $user->id : null,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'shipping_method' => $request->shipping_method,
                'payment_method' => 'vnpay',
                'notes' => $request->notes,
            ];

            $order = $this->orderService->createOrder($orderData, $cartItems);

            // Process order items
            $this->orderService->processOrderItems($order, $cartItems);

            // Clear cart
            $this->orderService->clearCart($user);

            // Create VNPay payment URL
            $paymentUrl = $this->orderService->handleVNPayPayment($order);

            return redirect($paymentUrl);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('VNPay payment error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.');
        }
    }

    /**
     * Process order
     */
    public function store(Request $request)
    {
        try {
            Log::info('Order store method called', [
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'address' => 'required|string',
                'shipping_method' => 'required|in:standard,express,pickup',
                'payment_method' => 'required|in:cod,bank_transfer,credit_card,momo,vnpay',
                'notes' => 'nullable|string',
            ]);

            $user = Auth::user();
            $cartItems = $this->orderService->prepareCartItems($request, $user);

            Log::info('Cart items prepared', [
                'cart_items_count' => $cartItems->count(),
                'cart_items' => $cartItems->toArray()
            ]);

            if ($cartItems->isEmpty()) {
                Log::warning('Cart is empty');
                return redirect()->route('home')->with('error', 'Giỏ hàng trống');
            }

            // Validate stock availability
            $stockValidation = $this->orderService->validateStockAvailability($cartItems);
            if (!$stockValidation['valid']) {
                Log::warning('Stock validation failed', ['message' => $stockValidation['message']]);
                return redirect()->back()->with('error', $stockValidation['message']);
            }

            // Create order
            $orderData = [
                'user_id' => $user ? $user->id : null,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'shipping_method' => $request->shipping_method,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ];

            Log::info('Creating order with data', ['order_data' => $orderData]);

            $order = $this->orderService->createOrder($orderData, $cartItems);

            // Process order items
            $this->orderService->processOrderItems($order, $cartItems);

            // Clear cart
            $this->orderService->clearCart($user);

            Log::info('Order processed successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

            return redirect()->route('orders.success', $order->order_number)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Order processing error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.');
        }
    }

    /**
     * Order success page
     */
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }

        return view('pages.order-success', compact('order'));
    }

    /**
     * Order tracking page
     */
    public function track(Request $request)
    {
        $user = Auth::user();
        $order = null;

        // If user is searching for a specific order by order number
        if ($request->has('order_number') && !empty($request->order_number)) {
            $order = Order::with(['items.book', 'items.book.author'])
                ->where('order_number', $request->order_number)
                ->where('user_id', $user->id) // Only show orders belonging to the authenticated user
                ->first();
        }

        // Get user's recent orders for quick access
        $recentOrders = Order::with(['items.book'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.order-tracking', compact('order', 'recentOrders'));
    }

    /**
     * User's orders
     */
    public function index()
    {
        $user = Auth::user();
        $orders = Order::with(['items.book'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.my-orders', compact('orders'));
    }

    /**
     * Order details
     */
    public function show($orderNumber)
    {
        $user = Auth::user();
        $order = Order::with(['items.book'])
            ->where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Không tìm thấy đơn hàng');
        }

        // Get order status history
        $statusHistory = $this->orderService->getOrderStatusHistory($order);

        return view('pages.order-details', compact('order', 'statusHistory'));
    }

    /**
     * Mark order as delivered by user
     */
    public function markDelivered($orderNumber)
    {
        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Không tìm thấy đơn hàng');
        }

        // Chỉ cho phép đánh dấu đã nhận hàng nếu đơn hàng đang ở trạng thái 'shipped'
        if ($order->status !== 'shipped') {
            return redirect()->back()->with('error', 'Chỉ có thể đánh dấu đã nhận hàng khi đơn hàng đã được giao');
        }

        $order->update(['status' => 'delivered']);

        return redirect()->back()->with('success', 'Đã đánh dấu đơn hàng là đã nhận hàng');
    }

    /**
     * Return order by user
     */
    public function returnOrder(Request $request, $orderNumber)
    {
        $request->validate([
            'return_reason' => 'required|string|max:500',
        ]);

        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Không tìm thấy đơn hàng');
        }

        // Chỉ cho phép hoàn hàng nếu đơn hàng đã được giao hoặc đã nhận hàng
        if (!in_array($order->status, ['shipped', 'delivered'])) {
            return redirect()->back()->with('error', 'Chỉ có thể hoàn hàng khi đơn hàng đã được giao hoặc đã nhận hàng');
        }

        $order->update([
            'status' => 'returned',
            'notes' => $order->notes . "\n\nLý do hoàn hàng: " . $request->return_reason
        ]);

        return redirect()->back()->with('success', 'Đã gửi yêu cầu hoàn hàng. Chúng tôi sẽ liên hệ với bạn sớm nhất');
    }

    /**
     * Xử lý callback từ VNPay
     */
    public function vnpayCallback(Request $request)
    {
        $result = $this->orderService->processVNPayCallback($request->all());

        if ($result['success']) {
            return redirect()->route('orders.success', $result['order']->order_number)
                ->with('success', $result['message']);
        } else {
            return redirect()->route('orders.index')
                ->with('error', $result['message']);
        }
    }

    /**
     * Xử lý return từ VNPay
     */
    public function vnpayReturn(Request $request)
    {
        $result = $this->orderService->processVNPayCallback($request->all());

        if ($result['success']) {
            return redirect()->route('orders.success', $result['order']->order_number)
                ->with('success', $result['message']);
        } else {
            return redirect()->route('orders.index')
                ->with('error', $result['message']);
        }
    }

    /**
     * Calculate shipping fee
     */
    private function calculateShippingFee($method, $subtotal)
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
}
