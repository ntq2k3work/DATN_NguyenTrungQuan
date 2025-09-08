<?php

namespace App\Livewire;

use App\Jobs\ProcessOrderJob;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrderManager extends Component
{
    public $cartItems = [];
    public $user = null;
    public $order = null;
    public $recentOrders = [];
    public $pageType = 'checkout'; // checkout, track, my-orders, show
    public $orderNumber = null;

    // Checkout form fields
    public $full_name = '';
    public $phone = '';
    public $email = '';
    public $address = '';
    public $shipping_method = 'standard';
    public $payment_method = 'cod';
    public $notes = '';

    // Order tracking
    public $trackingOrderNumber = '';
    public $returnReason = '';
    public $showReturnModal = false;

    protected $listeners = ['cartUpdated' => 'loadCartItems'];

    public function mount($pageType = 'checkout', $orderNumber = null)
    {
        $this->user = Auth::user();
        $this->pageType = $pageType;
        $this->orderNumber = $orderNumber;
        
        if ($pageType === 'checkout') {
            $this->loadCartItems();
        } elseif ($pageType === 'my-orders') {
            $this->loadUserOrders();
        } elseif ($pageType === 'show' && $orderNumber) {
            $this->loadOrderDetails($orderNumber);
        }
    }

    public function loadCartItems()
    {
        $this->cartItems = collect();

        if ($this->user) {
            $cart = \App\Models\Cart::where('user_id', $this->user->id)->first();
            if ($cart) {
                $this->cartItems = \App\Models\CartItem::with('book')->where('cart_id', $cart->id)->get();
            }
        } else {
            $sessionCart = Session::get('cart', []);
            foreach ($sessionCart as $item) {
                $book = Book::find($item['book_id']);
                if ($book) {
                    $this->cartItems->push((object) [
                        'book' => $book,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'] ?? $book->price, // Use stored discounted price
                        'book_id' => $book->id
                    ]);
                }
            }
        }
    }

    public function checkout()
    {
        if ($this->cartItems->isEmpty()) {
            $this->dispatch('showError', 'Giỏ hàng trống');
            return redirect()->route('home');
        }

        // Pre-fill user data if logged in
        if ($this->user) {
            $this->full_name = $this->user->name;
            $this->email = $this->user->email;
            $this->phone = $this->user->phone ?? '';
            $this->address = $this->user->address ?? '';
        }

        return view('pages.checkout');
    }

    public function storeOrder()
    {
        try {
            $this->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'address' => 'required|string',
                'shipping_method' => 'required|in:standard,express,pickup',
                'payment_method' => 'required|in:cod,bank_transfer,credit_card,momo',
                'notes' => 'nullable|string',
            ]);

            // Check if this is a "Buy Now" request (from session)
            $buyNowData = Session::get('buy_now');
            
            if ($buyNowData) {
                $book = Book::find($buyNowData['book_id']);
                if ($book) {
                    $this->cartItems = collect([(object) [
                        'book' => $book,
                        'quantity' => $buyNowData['quantity'],
                        'price' => $buyNowData['price'], // Use stored discounted price
                        'book_id' => $book->id
                    ]]);
                }
                // Clear buy now session
                Session::forget('buy_now');
            }

            if ($this->cartItems->isEmpty()) {
                $this->dispatch('showError', 'Giỏ hàng trống');
                return redirect()->route('home');
            }

            // Validate stock availability before processing
            foreach ($this->cartItems as $item) {
                $book = Book::find($item->book_id);
                if (!$book) {
                    $this->dispatch('showError', "Sản phẩm không tồn tại: " . ($item->book->title ?? 'Unknown'));
                    return;
                }
                
                if ($book->quantity < $item->quantity) {
                    $this->dispatch('showError', "Không đủ số lượng tồn kho cho sản phẩm: {$book->title}. Số lượng còn lại: {$book->quantity}");
                    return;
                }
            }

            // Calculate totals
            $subtotal = $this->cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $shippingFee = $this->calculateShippingFee($this->shipping_method, $subtotal);
            $total = $subtotal + $shippingFee;

            // Create order first with pending status
            $order = Order::create([
                'user_id' => $this->user ? $this->user->id : null,
                'order_number' => Order::generateOrderNumber(),
                'full_name' => $this->full_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'shipping_method' => $this->shipping_method,
                'payment_method' => $this->payment_method,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'status' => 'pending', // Will be updated by job
                'notes' => $this->notes,
            ]);

            // Prepare cart items data
            $cartItemsData = $this->cartItems->map(function ($item) {
                return [
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            })->toArray();

            // Dispatch job to process order items and update stock
            ProcessOrderJob::dispatch($order->toArray(), $cartItemsData);
            
            // Clear cart immediately after dispatching job
            if ($this->user) {
                $cart = \App\Models\Cart::where('user_id', $this->user->id)->first();
                if ($cart) {
                    \App\Models\CartItem::where('cart_id', $cart->id)->delete();
                }
            } else {
                Session::forget('cart');
            }

            return redirect()->route('orders.success', $order->order_number)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('form', 'Có lỗi xảy ra khi đặt hàng. Vui lòng kiểm tra lại thông tin.');
        } catch (\Exception $e) {
            $this->dispatch('showError', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.');
        }
    }

    public function loadOrderDetails($orderNumber)
    {
        $this->order = Order::with(['items.book', 'items.book.author'])
            ->where('order_number', $orderNumber)
            ->where('user_id', $this->user->id)
            ->first();

        if (!$this->order) {
            $this->dispatch('showError', 'Không tìm thấy đơn hàng');
        }
    }

    public function trackOrder()
    {
        if (empty($this->trackingOrderNumber)) {
            $this->dispatch('showError', 'Vui lòng nhập mã đơn hàng');
            return;
        }

        $this->order = Order::with(['items.book', 'items.book.author'])
            ->where('order_number', $this->trackingOrderNumber)
            ->where('user_id', $this->user->id)
            ->first();

        if (!$this->order) {
            $this->dispatch('showError', 'Không tìm thấy đơn hàng');
        }
    }

    public function loadRecentOrders()
    {
        $this->recentOrders = Order::with(['items.book'])
            ->where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function loadUserOrders()
    {
        return Order::with(['items.book'])
            ->where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function markDelivered($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $this->user->id)
            ->first();

        if (!$order) {
            $this->dispatch('showError', 'Không tìm thấy đơn hàng');
            return;
        }

        // Chỉ cho phép đánh dấu đã nhận hàng nếu đơn hàng đang ở trạng thái 'shipped'
        if ($order->status !== 'shipped') {
            $this->dispatch('showError', 'Chỉ có thể đánh dấu đã nhận hàng khi đơn hàng đã được giao');
            return;
        }

        $order->update(['status' => 'delivered']);
        $this->dispatch('showSuccess', 'Đã đánh dấu đơn hàng là đã nhận hàng');
        
        // Refresh orders
        $this->loadUserOrders();
    }

    public function returnOrder($orderNumber)
    {
        $this->validate([
            'returnReason' => 'required|string|max:500',
        ]);

        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $this->user->id)
            ->first();

        if (!$order) {
            $this->dispatch('showError', 'Không tìm thấy đơn hàng');
            return;
        }

        // Chỉ cho phép hoàn hàng nếu đơn hàng đã được giao hoặc đã nhận hàng
        if (!in_array($order->status, ['shipped', 'delivered'])) {
            $this->dispatch('showError', 'Chỉ có thể hoàn hàng khi đơn hàng đã được giao hoặc đã nhận hàng');
            return;
        }

        $order->update([
            'status' => 'returned',
            'notes' => $order->notes . "\n\nLý do hoàn hàng: " . $this->returnReason
        ]);

        $this->dispatch('showSuccess', 'Đã gửi yêu cầu hoàn hàng. Chúng tôi sẽ liên hệ với bạn sớm nhất');
        $this->returnReason = '';
        $this->showReturnModal = false;
        
        // Refresh orders
        $this->loadUserOrders();
    }

    public function openReturnModal()
    {
        $this->showReturnModal = true;
        $this->returnReason = '';
    }

    public function closeReturnModal()
    {
        $this->showReturnModal = false;
        $this->returnReason = '';
    }

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

    public function render()
    {
        switch ($this->pageType) {
            case 'my-orders':
                $orders = $this->loadUserOrders();
                return view('livewire.order-manager', compact('orders'));
            case 'track':
                return view('livewire.order-manager');
            case 'show':
                return view('livewire.order-manager');
            case 'checkout':
            default:
                return view('livewire.order-manager');
        }
    }
}
