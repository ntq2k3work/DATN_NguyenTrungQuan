<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'full_name',
        'phone',
        'email',
        'address',
        'shipping_method',
        'payment_method',
        'subtotal',
        'shipping_fee',
        'total',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đã giao hàng',
            'delivered' => 'Đã nhận hàng',
            'cancelled' => 'Đã hủy',
            'returned' => 'Đã hoàn hàng',
            default => $this->status,
        };
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cod' => 'Thanh toán khi nhận hàng',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'credit_card' => 'Thẻ tín dụng',
            'momo' => 'Ví MoMo',
            default => $this->payment_method,
        };
    }

    /**
     * Get shipping method label
     */
    public function getShippingMethodLabelAttribute(): string
    {
        return match ($this->shipping_method) {
            'standard' => 'Giao hàng tiêu chuẩn',
            'express' => 'Giao hàng nhanh',
            'pickup' => 'Nhận tại cửa hàng',
            default => $this->shipping_method,
        };
    }
}

