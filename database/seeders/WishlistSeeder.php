<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wishlists = [
            // Danh sách yêu thích của Nguyễn Văn A
            [
                'user_id' => 1,
                'book_id' => 11, // Việt Bắc
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id' => 1,
                'book_id' => 12, // Điêu tàn
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            // Danh sách yêu thích của Trần Thị B
            [
                'user_id' => 1,
                'book_id' => 1, // Tôi thấy hoa vàng trên cỏ xanh
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            [
                'user_id' => 1,
                'book_id' => 7, // Hà Nội băm sáu phố phường
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            // Danh sách yêu thích của Lê Văn C
            [
                'user_id' => 10,
                'book_id' => 6, // Số đỏ
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'user_id' => 10,
                'book_id' => 8, // Vang bóng một thời
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            // Danh sách yêu thích của Phạm Thị D
            [
                'user_id' => 10,
                'book_id' => 3, // Dế Mèn phiêu lưu ký
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'user_id' => 10,
                'book_id' => 9, // Sóng
                'created_at' => now()->subDays(18),
                'updated_at' => now()->subDays(18),
            ],
            // Danh sách yêu thích của Hoàng Văn E
            [
                'user_id' => 10,
                'book_id' => 2, // Cô gái đến từ hôm qua
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'user_id' => 10,
                'book_id' => 4, // Chí Phèo
                'created_at' => now()->subDays(22),
                'updated_at' => now()->subDays(22),
            ],
            // Danh sách yêu thích của Vũ Thị F
            [
                'user_id' => 10,
                'book_id' => 5, // Lão Hạc
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'user_id' => 11,
                'book_id' => 10, // Nhật ký trong tù
                'created_at' => now()->subDays(28),
                'updated_at' => now()->subDays(28),
            ],
            // Danh sách yêu thích của Đặng Văn G
            [
                'user_id' => 11,
                'book_id' => 1, // Tôi thấy hoa vàng trên cỏ xanh
                'created_at' => now()->subDays(35),
                'updated_at' => now()->subDays(35),
            ],
            [
                'user_id' => 12,
                'book_id' => 3, // Dế Mèn phiêu lưu ký
                'created_at' => now()->subDays(32),
                'updated_at' => now()->subDays(32),
            ],
            // Danh sách yêu thích của Bùi Thị H
            [
                'user_id' => 12,
                'book_id' => 6, // Số đỏ
                'created_at' => now()->subDays(40),
                'updated_at' => now()->subDays(40),
            ],
            [
                'user_id' => 12,
                'book_id' => 8, // Vang bóng một thời
                'created_at' => now()->subDays(38),
                'updated_at' => now()->subDays(38),
            ],
        ];

        DB::table('wishlists')->insert($wishlists);
    }
}
