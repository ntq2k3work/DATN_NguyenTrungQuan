<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kiểm tra và sửa cột total_price thành total
            if (Schema::hasColumn('orders', 'total_price') && !Schema::hasColumn('orders', 'total')) {
                $table->renameColumn('total_price', 'total');
            }
            
            // Nếu không có cột total, tạo mới
            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 10, 2)->after('shipping_fee');
            }
            
            // Đảm bảo cột total không null
            $table->decimal('total', 10, 2)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Khôi phục cột cũ - chỉ rename nếu total_price chưa tồn tại
            if (Schema::hasColumn('orders', 'total') && !Schema::hasColumn('orders', 'total_price')) {
                $table->renameColumn('total', 'total_price');
            }
        });
    }
};