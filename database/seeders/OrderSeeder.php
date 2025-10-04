<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                'user_id' => 2, // Nguyễn Văn A
                'total_price' => 185000, // 2x120000 + 1x85000
                'status' => 'delivered',
                'shipping_address' => '123 Đường ABC, Quận 1, TP.HCM',
                'payment_method' => 'COD',
                'payment_status' => 'paid',
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(25),
            ],
            [
                'user_id' => 3, // Trần Thị B
                'total_price' => 160000, // 1x95000 + 1x65000
                'status' => 'shipped',
                'shipping_address' => '456 Đường XYZ, Quận 3, TP.HCM',
                'payment_method' => 'Bank Transfer',
                'payment_status' => 'paid',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id' => 4, // Lê Văn C
                'total_price' => 185000, // 2x55000 + 1x75000
                'status' => 'processing',
                'shipping_address' => '789 Đường DEF, Quận 5, TP.HCM',
                'payment_method' => 'Credit Card',
                'payment_status' => 'paid',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id' => 5, // Phạm Thị D
                'total_price' => 200000, // 1x110000 + 1x90000
                'status' => 'pending',
                'shipping_address' => '321 Đường GHI, Quận 7, TP.HCM',
                'payment_method' => 'COD',
                'payment_status' => 'pending',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'user_id' => 6, // Hoàng Văn E
                'total_price' => 125000, // 1x45000 + 1x80000
                'status' => 'cancelled',
                'shipping_address' => '654 Đường JKL, Quận 10, TP.HCM',
                'payment_method' => 'Bank Transfer',
                'payment_status' => 'refunded',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(18),
            ],
        ];

        DB::table('orders')->insert($orders);
    }
}
