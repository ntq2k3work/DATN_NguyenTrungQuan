<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookAuthors = [
            // Nguyễn Nhật Ánh
            [
                'book_id' => 1, // Tôi thấy hoa vàng trên cỏ xanh
                'author_id' => 1, // Nguyễn Nhật Ánh
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_id' => 2, // Cô gái đến từ hôm qua
                'author_id' => 1, // Nguyễn Nhật Ánh
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tô Hoài
            [
                'book_id' => 3, // Dế Mèn phiêu lưu ký
                'author_id' => 2, // Tô Hoài
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Nam Cao
            [
                'book_id' => 4, // Chí Phèo
                'author_id' => 3, // Nam Cao
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_id' => 5, // Lão Hạc
                'author_id' => 3, // Nam Cao
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Vũ Trọng Phụng
            [
                'book_id' => 6, // Số đỏ
                'author_id' => 4, // Vũ Trọng Phụng
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Thạch Lam
            [
                'book_id' => 7, // Hà Nội băm sáu phố phường
                'author_id' => 5, // Thạch Lam
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Nguyễn Tuân
            [
                'book_id' => 8, // Vang bóng một thời
                'author_id' => 6, // Nguyễn Tuân
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Xuân Quỳnh
            [
                'book_id' => 9, // Sóng
                'author_id' => 7, // Xuân Quỳnh
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Hồ Chí Minh
            [
                'book_id' => 10, // Nhật ký trong tù
                'author_id' => 8, // Hồ Chí Minh
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tố Hữu
            [
                'book_id' => 11, // Việt Bắc
                'author_id' => 9, // Tố Hữu
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Chế Lan Viên
            [
                'book_id' => 12, // Điêu tàn
                'author_id' => 10, // Chế Lan Viên
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('book_authors')->insert($bookAuthors);
    }
}
