<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'id',
        'book_id',
        'amount',
        'percent'
    ];

    public function book()
    {
        return $this->belongsTo(Discount::class,'book_id');
    }
}
