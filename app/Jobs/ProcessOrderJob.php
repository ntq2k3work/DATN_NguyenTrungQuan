<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessOrderJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 120;
    public $tries = 3;

    protected $orderData;
    protected $cartItems;

    /**
     * Create a new job instance.
     */
    public function __construct(array $orderData, array $cartItems)
    {
        $this->orderData = $orderData;
        $this->cartItems = $cartItems;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();
        
        try {
            // Kiểm tra số lượng tồn kho trước khi xử lý
            $this->validateStockAvailability();
            
            // Tìm order đã tồn tại
            $order = Order::find($this->orderData['id']);
            if (!$order) {
                throw new \Exception("Order not found: {$this->orderData['id']}");
            }
            
            // Cập nhật status thành processing
            $order->update(['status' => 'processing']);
            
            // Tạo order items và cập nhật số lượng tồn kho
            foreach ($this->cartItems as $item) {
                $this->createOrderItem($order, $item);
                $this->updateBookStock($item['book_id'], $item['quantity']);
            }
            
            // Cập nhật status thành pending sau khi xử lý xong
            $order->update(['status' => 'pending']);
            
            DB::commit();
            
            // Gửi thông báo thành công
            SendOrderNotificationJob::dispatch($order->id, 'pending');
            
            Log::info("Order processed successfully", [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Cập nhật order status thành cancelled nếu có lỗi
            if (isset($order)) {
                $order->update(['status' => 'cancelled']);
                // Gửi thông báo hủy đơn hàng
                SendOrderNotificationJob::dispatch($order->id, 'cancelled');
            }
            
            Log::error("Order processing failed", [
                'error' => $e->getMessage(),
                'order_data' => $this->orderData
            ]);
            
            throw $e;
        }
    }

    /**
     * Validate stock availability for all items
     */
    protected function validateStockAvailability(): void
    {
        foreach ($this->cartItems as $item) {
            $book = Book::lockForUpdate()->find($item['book_id']);
            
            if (!$book) {
                throw new \Exception("Book not found: {$item['book_id']}");
            }
            
            if ($book->quantity < $item['quantity']) {
                throw new \Exception("Insufficient stock for book: {$book->title}. Available: {$book->quantity}, Requested: {$item['quantity']}");
            }
        }
    }

    /**
     * Create order item
     */
    protected function createOrderItem(Order $order, array $item): void
    {
        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $item['book_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    /**
     * Update book stock with lock
     */
    protected function updateBookStock(int $bookId, int $quantity): void
    {
        $book = Book::lockForUpdate()->find($bookId);
        
        if (!$book) {
            throw new \Exception("Book not found: {$bookId}");
        }
        
        if ($book->quantity < $quantity) {
            throw new \Exception("Insufficient stock for book: {$book->title}. Available: {$book->quantity}, Requested: {$quantity}");
        }
        
        $book->decrement('quantity', $quantity);
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Order processing job failed permanently", [
            'error' => $exception->getMessage(),
            'order_data' => $this->orderData,
            'cart_items' => $this->cartItems
        ]);
    }
}
