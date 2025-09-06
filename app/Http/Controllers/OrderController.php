<?php

namespace App\Http\Controllers;

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

            
            DB::beginTransaction();
            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $shippingFee = $this->calculateShippingFee($request->shipping_method, $subtotal);
            $total = $subtotal + $shippingFee;


            // Create order
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
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            
            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
                
                // Update book quantity
                $book = Book::find($item->book_id);
                if ($book) {
                    $book->decrement('quantity', $item->quantity);
                }
            }
            
            // Clear cart
            if ($user && $cart) {
                \App\Models\CartItem::where('cart_id', $cart->id)->delete();
            } else {
                Session::forget('cart');
            }

            DB::commit();

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
        $orders = Order::where('user_id', $user->id)
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
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Không tìm thấy đơn hàng');
        }

        return view('pages.order-details', compact('order'));
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