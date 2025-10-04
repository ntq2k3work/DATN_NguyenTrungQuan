<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tài khoản admin mặc định
        User::create([
            'name' => 'Admin',
            'email' => 'admin@bookstore.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'address' => 'Hà Nội, Việt Nam',
        ]);

        // Tạo thêm một số tài khoản admin khác
        User::create([
            'name' => 'Quản trị viên',
            'email' => 'quanly@bookstore.com',
            'password' => Hash::make('quanly123'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'address' => 'TP. Hồ Chí Minh, Việt Nam',
        ]);

        $this->command->info('Đã tạo thành công tài khoản admin!');
        $this->command->info('Email: admin@bookstore.com | Password: admin123');
        $this->command->info('Email: quanly@bookstore.com | Password: quanly123');
    }
}
