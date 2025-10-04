<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'note',
        'changed_by',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /**
     * Get the order that owns the status history.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who changed the status.
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Get the status text.
     */
    public function getStatusTextAttribute(): string
    {
        $statusMap = [
            'pending' => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'confirmed' => 'Đã xác nhận',
            'shipped' => 'Đã giao hàng',
            'delivered' => 'Đã nhận hàng',
            'cancelled' => 'Đã hủy',
            'returned' => 'Đã hoàn trả'
        ];

        return $statusMap[$this->status] ?? 'Không xác định';
    }
}
