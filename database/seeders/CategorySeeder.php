<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Văn học thiếu nhi',
                'slug' => 'van-hoc-thieu-nhi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Văn học hiện thực',
                'slug' => 'van-hoc-hien-thuc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tùy bút - Bút ký',
                'slug' => 'tuy-but-but-ky',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thơ ca',
                'slug' => 'tho-ca',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tiểu thuyết',
                'slug' => 'tieu-thuyet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Truyện ngắn',
                'slug' => 'truyen-ngan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sách giáo khoa',
                'slug' => 'sach-giao-khoa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sách tham khảo',
                'slug' => 'sach-tham-khao',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
