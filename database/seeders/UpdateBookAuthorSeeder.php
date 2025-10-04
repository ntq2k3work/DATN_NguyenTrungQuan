<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateBookAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cập nhật author_id cho các book dựa trên title
        $bookAuthors = [
            'Tôi thấy hoa vàng trên cỏ xanh' => 1, // Nguyễn Nhật Ánh
            'Cô gái đến từ hôm qua' => 1, // Nguyễn Nhật Ánh
            'Dế Mèn phiêu lưu ký' => 2, // Tô Hoài
            'Chí Phèo' => 3, // Nam Cao
            'Lão Hạc' => 3, // Nam Cao
            'Số đỏ' => 4, // Vũ Trọng Phụng
            'Hà Nội băm sáu phố phường' => 5, // Thạch Lam
            'Vang bóng một thời' => 6, // Nguyễn Tuân
            'Sóng' => 7, // Xuân Quỳnh
            'Nhật ký trong tù' => 8, // Hồ Chí Minh
            'Việt Bắc' => 9, // Tố Hữu
            'Điêu tàn' => 10, // Chế Lan Viên
        ];

        foreach ($bookAuthors as $title => $authorId) {
            DB::table('books')
                ->where('title', $title)
                ->update(['author_id' => $authorId]);
        }
    }
}
