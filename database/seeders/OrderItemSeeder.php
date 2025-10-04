<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderItems = [
            // Đơn hàng 1 của Nguyễn Văn A
            [
                'order_id' => 1,
                'book_id' => 1, // Tôi thấy hoa vàng trên cỏ xanh
                'quantity' => 2,
                'price' => 120000,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'order_id' => 1,
                'book_id' => 3, // Dế Mèn phiêu lưu ký
                'quantity' => 1,
                'price' => 85000,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            // Đơn hàng 2 của Trần Thị B
            [
                'order_id' => 2,
                'book_id' => 2, // Cô gái đến từ hôm qua
                'quantity' => 1,
                'price' => 95000,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'order_id' => 2,
                'book_id' => 4, // Chí Phèo
                'quantity' => 1,
                'price' => 65000,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            // Đơn hàng 3 của Lê Văn C
            [
                'order_id' => 3,
                'book_id' => 5, // Lão Hạc
                'quantity' => 2,
                'price' => 55000,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'order_id' => 3,
                'book_id' => 7, // Hà Nội băm sáu phố phường
                'quantity' => 1,
                'price' => 75000,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            // Đơn hàng 4 của Phạm Thị D
            [
                'order_id' => 4,
                'book_id' => 6, // Số đỏ
                'quantity' => 1,
                'price' => 110000,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'order_id' => 4,
                'book_id' => 8, // Vang bóng một thời
                'quantity' => 1,
                'price' => 90000,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            // Đơn hàng 5 của Hoàng Văn E (đã hủy)
            [
                'order_id' => 5,
                'book_id' => 9, // Sóng
                'quantity' => 1,
                'price' => 45000,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'order_id' => 5,
                'book_id' => 10, // Nhật ký trong tù
                'quantity' => 1,
                'price' => 80000,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
        ];

        DB::table('order_items')->insert($orderItems);
    }
}
