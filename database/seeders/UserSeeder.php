<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'date_of_birth' => '2024-01-01',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'gender' => 'male',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'date_of_birth' => '2024-01-02',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'gender' => 'male',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'tranthib@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'date_of_birth' => '2024-01-03',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'gender' => 'female',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'levanc@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'date_of_birth' => '2024-01-04',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'gender' => 'male',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phạm Thị D',
                'email' => 'phamthid@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'date_of_birth' => '2024-01-05',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'gender' => 'female',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hoàng Văn E',
                'email' => 'hoangvane@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'date_of_birth' => '2024-01-06',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'gender' => 'male',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vũ Thị F',
                'email' => 'vuthif@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'date_of_birth' => '2024-01-07',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'gender' => 'female',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Đặng Văn G',
                'email' => 'dangvang@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'date_of_birth' => '2024-01-08',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'gender' => 'male',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bùi Thị H',
                'email' => 'buithih@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'date_of_birth' => '2024-01-09',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'gender' => 'male',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Đỗ Văn I',
                'email' => 'dovani@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'date_of_birth' => '2024-01-10',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'gender' => 'male',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
