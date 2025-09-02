<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publishers = [
            [
                'name' => 'Nhà xuất bản Kim Đồng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Trẻ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Văn học',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Giáo dục',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Chính trị Quốc gia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Khoa học xã hội',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Đại học Quốc gia Hà Nội',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Đại học Sư phạm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Thanh niên',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Phụ nữ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('publishers')->insert($publishers);
    }
}
