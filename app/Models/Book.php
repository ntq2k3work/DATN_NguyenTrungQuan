<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'quantity',
        'image_url',
        'category_id',
        'publisher_id',
    ];

    // public $casts = [
    //     'price' => 'double',
    // ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function discount()
    {
        return $this->belongsTo(Book::class,'id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // public function getPriceAttribute($value)
    // {
    //     return number_format($value, 0, ',', '.');
    // }

    public function getDiscountPriceAttribute()
    {
        return $this->price * (1 - $this->discount_percent / 100);
    }
}
