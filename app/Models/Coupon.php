<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type', // percentage, fixed
        'value',
        'min_order_amount',
        'max_uses',
        'used_count',
        'status', // active, inactive
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
    ];

    public function isValid()
    {
        return $this->status === 'active' &&
               $this->start_date <= now() &&
               $this->end_date >= now() &&
               ($this->max_uses === null || $this->used_count < $this->max_uses);
    }

    public function calculateDiscount($subtotal)
    {
        if (!$this->isValid() || $subtotal < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;
        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
        } else {
            $discount = $this->value;
        }

        // Đảm bảo không giảm quá subtotal
        return min($discount, $subtotal);
    }
}
