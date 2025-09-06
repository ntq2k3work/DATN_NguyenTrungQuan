<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderNotificationJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 60;
    public $tries = 3;

    protected $orderId;
    protected $status;

    /**
     * Create a new job instance.
     */
    public function __construct(int $orderId, string $status)
    {
        $this->orderId = $orderId;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::with('user')->find($this->orderId);
        
        if (!$order) {
            Log::warning("Order not found for notification", ['order_id' => $this->orderId]);
            return;
        }

        try {
            // Gửi email thông báo cho user
            if ($order->user && $order->email) {
                $this->sendEmailNotification($order);
            }

            // Log thông báo
            Log::info("Order notification sent", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $this->status,
                'email' => $order->email
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to send order notification", [
                'order_id' => $this->orderId,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Send email notification
     */
    protected function sendEmailNotification(Order $order): void
    {
        $subject = match($this->status) {
            'processing' => 'Đơn hàng đang được xử lý',
            'pending' => 'Đơn hàng đã được xác nhận',
            'cancelled' => 'Đơn hàng đã bị hủy',
            default => 'Cập nhật đơn hàng'
        };

        // TODO: Implement email sending logic
        // Mail::to($order->email)->send(new OrderStatusMail($order, $this->status));
        
        Log::info("Email notification prepared", [
            'order_id' => $order->id,
            'email' => $order->email,
            'subject' => $subject
        ]);
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Order notification job failed permanently", [
            'order_id' => $this->orderId,
            'status' => $this->status,
            'error' => $exception->getMessage()
        ]);
    }
}
