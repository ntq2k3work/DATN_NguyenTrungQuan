<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Giảm giá chào mừng 10%',
                'description' => 'Giảm 10% cho đơn hàng đầu tiên',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 100000,
                'max_uses' => 100,
                'used_count' => 0,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
            ],
            [
                'code' => 'SAVE50K',
                'name' => 'Giảm giá 50.000₫',
                'description' => 'Giảm 50.000₫ cho đơn hàng từ 200.000₫',
                'type' => 'fixed',
                'value' => 50000,
                'min_order_amount' => 200000,
                'max_uses' => 50,
                'used_count' => 0,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Miễn phí vận chuyển',
                'description' => 'Miễn phí vận chuyển cho đơn hàng từ 500.000₫',
                'type' => 'fixed',
                'value' => 30000,
                'min_order_amount' => 500000,
                'max_uses' => 30,
                'used_count' => 0,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addMonths(1),
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}
