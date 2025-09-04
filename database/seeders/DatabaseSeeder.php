<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi các seeder theo thứ tự để đảm bảo ràng buộc khóa ngoại
        $this->call([
            CategorySeeder::class,      // 1. Tạo danh mục trước
            AuthorSeeder::class,        // 2. Tạo tác giả
            PublisherSeeder::class,     // 3. Tạo nhà xuất bản
            BookSeeder::class,          // 4. Tạo sách (cần category_id và publisher_id)
            BookAuthorSeeder::class,    // 5. Tạo quan hệ sách-tác giả (cần book_id và author_id)
            UserSeeder::class,          // 6. Tạo người dùng
            CouponSeeder::class,        // 7. Tạo mã khuyến mãi
            CartSeeder::class,          // 8. Tạo giỏ hàng (cần user_id)
            CartItemSeeder::class,      // 9. Tạo mục giỏ hàng (cần cart_id và book_id)
            OrderSeeder::class,         // 10. Tạo đơn hàng (cần user_id)
            OrderItemSeeder::class,     // 11. Tạo mục đơn hàng (cần order_id và book_id)
            WishlistSeeder::class,      // 12. Tạo danh sách yêu thích (cần user_id và book_id)
        ]);
    }
}
