<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessOrderJob;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Show checkout page
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cartItems = collect();

        // Check if this is a "Buy Now" request
        if ($request->has('book_id') && $request->has('quantity')) {
            $bookId = $request->book_id;
            $quantity = $request->quantity;
            
            $book = Book::find($bookId);
            if ($book) {
                // Store buy now data in session
                Session::put('buy_now', [
                    'book_id' => $bookId,
                    'quantity' => $quantity,
                    'price' => $book->price
                ]);
                
                // Create temporary cart items for display
                $cartItems = collect([(object) [
                    'book' => $book,
                    'quantity' => $quantity,
                    'price' => $book->price,
                    'book_id' => $bookId
                ]]);
            }
        } else {
            // Handle regular cart checkout
            if ($user) {
                $cart = \App\Models\Cart::where('user_id', $user->id)->first();
                if ($cart) {
                    $cartItems = \App\Models\CartItem::with('book')
                        ->where('cart_id', $cart->id)
                        ->get();
                }
            } else {
                // Handle guest cart from session
                $sessionCart = Session::get('cart', []);
                $cartItems = collect();
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
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Giỏ hàng trống');
        }

        return view('pages.checkout', compact('cartItems', 'user'));
    }

    /**
     * Process order
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'address' => 'required|string',
                'shipping_method' => 'required|in:standard,express,pickup',
                'payment_method' => 'required|in:cod,bank_transfer,credit_card,momo',
                'notes' => 'nullable|string',
            ]);


            $user = Auth::user();
            $cartItems = collect();
            $cart = null;


            // Check if this is a "Buy Now" request (from session)
            $buyNowData = Session::get('buy_now');
            
            if ($buyNowData) {
                $book = Book::find($buyNowData['book_id']);
                if ($book) {
                    $cartItems = collect([(object) [
                        'book' => $book,
                        'quantity' => $buyNowData['quantity'],
                        'price' => $book->price,
                        'book_id' => $book->id
                    ]]);
                }
                // Clear buy now session
                Session::forget('buy_now');
            } else {
                // Get cart items
                if ($user) {
                    $cart = \App\Models\Cart::where('user_id', $user->id)->first();
                    if ($cart) {
                        $cartItems = \App\Models\CartItem::with('book')->where('cart_id', $cart->id)->get();
                    }
                } else {
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
            }

          
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('home')->with('error', 'Giỏ hàng trống');
            }

            // Validate stock availability before processing
            foreach ($cartItems as $item) {
                $book = Book::find($item->book_id);
                if (!$book) {
                    return redirect()->back()->with('error', "Sản phẩm không tồn tại: " . ($item->book->title ?? 'Unknown'));
                }
                
                if ($book->quantity < $item->quantity) {
                    return redirect()->back()->with('error', "Không đủ số lượng tồn kho cho sản phẩm: {$book->title}. Số lượng còn lại: {$book->quantity}");
                }
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $shippingFee = $this->calculateShippingFee($request->shipping_method, $subtotal);
            $total = $subtotal + $shippingFee;

            // Create order first with pending status
            $order = Order::create([
                'user_id' => $user ? $user->id : null,
                'order_number' => Order::generateOrderNumber(),
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'shipping_method' => $request->shipping_method,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'status' => 'pending', // Will be updated by job
                'notes' => $request->notes,
            ]);

            // Prepare cart items data
            $cartItemsData = $cartItems->map(function ($item) {
                return [
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            })->toArray();

            // Dispatch job to process order items and update stock
            ProcessOrderJob::dispatch($order->toArray(), $cartItemsData);
            
            // Clear cart immediately after dispatching job
            if ($user && $cart) {
                \App\Models\CartItem::where('cart_id', $cart->id)->delete();
            } else {
                Session::forget('cart');
            }

            return redirect()->route('orders.success', $order->order_number)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
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
    public function track()
    {
        return view('pages.order-tracking');
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

        return view('pages.order-details', compact('order'));
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