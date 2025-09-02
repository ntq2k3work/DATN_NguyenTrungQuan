<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cartItems = [
            // Giỏ hàng của Nguyễn Văn A
            [
                'cart_id' => 1,
                'book_id' => 1, // Tôi thấy hoa vàng trên cỏ xanh
                'quantity' => 2,
                'price' => 120000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cart_id' => 1,
                'book_id' => 3, // Dế Mèn phiêu lưu ký
                'quantity' => 1,
                'price' => 85000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Giỏ hàng của Trần Thị B
            [
                'cart_id' => 2,
                'book_id' => 2, // Cô gái đến từ hôm qua
                'quantity' => 1,
                'price' => 95000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cart_id' => 2,
                'book_id' => 4, // Chí Phèo
                'quantity' => 1,
                'price' => 65000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Giỏ hàng của Lê Văn C
            [
                'cart_id' => 3,
                'book_id' => 5, // Lão Hạc
                'quantity' => 2,
                'price' => 55000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cart_id' => 3,
                'book_id' => 7, // Hà Nội băm sáu phố phường
                'quantity' => 1,
                'price' => 75000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Giỏ hàng của Phạm Thị D
            [
                'cart_id' => 4,
                'book_id' => 6, // Số đỏ
                'quantity' => 1,
                'price' => 110000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cart_id' => 4,
                'book_id' => 8, // Vang bóng một thời
                'quantity' => 1,
                'price' => 90000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Giỏ hàng của Hoàng Văn E
            [
                'cart_id' => 5,
                'book_id' => 9, // Sóng
                'quantity' => 1,
                'price' => 45000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cart_id' => 5,
                'book_id' => 10, // Nhật ký trong tù
                'quantity' => 1,
                'price' => 80000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('cart_items')->insert($cartItems);
    }
}
