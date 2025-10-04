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
            // Xóa cột total_price nếu tồn tại (giữ lại total)
            if (Schema::hasColumn('orders', 'total_price')) {
                $table->dropColumn('total_price');
            }
            
            // Xóa cột shipping_address nếu tồn tại (giữ lại address)
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }
            
            // Đảm bảo các enum đúng
            $table->enum('shipping_method', ['standard', 'express', 'pickup'])->change();
            $table->enum('payment_method', ['cod', 'bank_transfer', 'credit_card', 'momo'])->change();
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Khôi phục cột total_price
            if (!Schema::hasColumn('orders', 'total_price')) {
                $table->decimal('total_price', 10, 2)->after('shipping_fee');
            }
            
            // Khôi phục cột shipping_address
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->string('shipping_address')->after('email');
            }
        });
    }
};
