<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carts = [
            [
                'user_id' => 1, // Nguyễn Văn A
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9, // Trần Thị B
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 10, // Lê Văn C
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 11, // Phạm Thị D
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 12, // Hoàng Văn E
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('carts')->insert($carts);
    }
}
