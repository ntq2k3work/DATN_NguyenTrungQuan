<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Jobs\SendOrderNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessOrderJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 120;
    public $tries = 3;
    public $backoff = [30, 60, 120]; // Exponential backoff

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
            Log::info("Starting order processing", [
                'order_id' => $this->orderData['id'],
                'order_number' => $this->orderData['order_number']
            ]);

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
                'order_number' => $order->order_number,
                'items_count' => count($this->cartItems)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Order processing failed", [
                'error' => $e->getMessage(),
                'order_data' => $this->orderData,
                'cart_items' => $this->cartItems
            ]);

            // Cập nhật order status thành cancelled nếu có lỗi
            if (isset($order)) {
                $order->update(['status' => 'cancelled']);
                // Gửi thông báo hủy đơn hàng
                SendOrderNotificationJob::dispatch($order->id, 'cancelled');
            }

            throw $e;
        }
    }

    /**
     * Validate stock availability for all items with enhanced error handling
     */
    protected function validateStockAvailability(): void
    {
        $unavailableItems = [];

        foreach ($this->cartItems as $item) {
            $book = Book::lockForUpdate()->find($item['book_id']);

            if (!$book) {
                $unavailableItems[] = "Book not found: {$item['book_id']}";
                continue;
            }

            if ($book->quantity < $item['quantity']) {
                $unavailableItems[] = "Insufficient stock for book: {$book->title}. Available: {$book->quantity}, Requested: {$item['quantity']}";
            }
        }

        if (!empty($unavailableItems)) {
            throw new \Exception("Stock validation failed: " . implode('; ', $unavailableItems));
        }
    }

    /**
     * Create order item with enhanced data
     */
    protected function createOrderItem(Order $order, array $item): void
    {
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $item['book_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);

        Log::info("Order item created", [
            'order_item_id' => $orderItem->id,
            'book_id' => $item['book_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ]);
    }

    /**
     * Update book stock with enhanced logging and error handling
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

        $oldQuantity = $book->quantity;
        $book->decrement('quantity', $quantity);

        Log::info("Book stock updated", [
            'book_id' => $bookId,
            'book_title' => $book->title,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $book->fresh()->quantity,
            'decremented_by' => $quantity
        ]);
    }

    /**
     * Handle job failure with detailed logging
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Order processing job failed permanently", [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'order_data' => $this->orderData,
            'cart_items' => $this->cartItems,
            'attempts' => $this->attempts()
        ]);

        // Try to update order status to cancelled if possible
        try {
            $order = Order::find($this->orderData['id']);
            if ($order && $order->status === 'processing') {
                $order->update(['status' => 'cancelled']);
                SendOrderNotificationJob::dispatch($order->id, 'cancelled');
            }
        } catch (\Exception $e) {
            Log::error("Failed to update order status after job failure", [
                'order_id' => $this->orderData['id'],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'order:' . $this->orderData['id'],
            'order_number:' . $this->orderData['order_number']
        ];
    }
}
