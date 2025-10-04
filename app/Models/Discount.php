<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    protected $fillable = [
        'book_id',
        'amount',
        'percent',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function isActive(): bool
    {
        $now = Carbon::now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    public function getDiscountAmount($originalPrice): float
    {
        if ($this->percent) {
            return $originalPrice * ($this->percent / 100);
        }
        
        if ($this->amount) {
            return min($this->amount, $originalPrice);
        }
        
        return 0;
    }
}
