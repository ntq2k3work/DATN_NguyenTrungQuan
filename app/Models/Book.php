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
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'id', 'book_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
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
