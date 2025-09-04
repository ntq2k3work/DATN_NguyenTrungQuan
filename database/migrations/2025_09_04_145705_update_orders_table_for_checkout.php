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
            // Cho phép user_id có thể null để hỗ trợ đặt hàng không cần đăng nhập
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Thêm các cột mới cho checkout nếu chưa tồn tại
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->after('id');
            }
            if (!Schema::hasColumn('orders', 'full_name')) {
                $table->string('full_name')->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->after('full_name');
            }
            if (!Schema::hasColumn('orders', 'email')) {
                $table->string('email')->after('phone');
            }
            if (!Schema::hasColumn('orders', 'address')) {
                $table->text('address')->after('email');
            }
            if (!Schema::hasColumn('orders', 'shipping_method')) {
                $table->enum('shipping_method', ['standard', 'express'])->after('address');
            }
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->after('shipping_method');
            }
            if (!Schema::hasColumn('orders', 'shipping_fee')) {
                $table->decimal('shipping_fee', 10, 2)->after('subtotal');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('total');
            }
            
            // Đổi tên cột total_price thành total nếu chưa đổi
            if (Schema::hasColumn('orders', 'total_price') && !Schema::hasColumn('orders', 'total')) {
                $table->renameColumn('total_price', 'total');
            }
            
            // Thêm cột total nếu chưa có
            if (!Schema::hasColumn('orders', 'total') && !Schema::hasColumn('orders', 'total_price')) {
                $table->decimal('total', 10, 2)->after('shipping_fee');
            }
            
            // Xóa cột shipping_address nếu tồn tại và chưa có address
            if (Schema::hasColumn('orders', 'shipping_address') && !Schema::hasColumn('orders', 'address')) {
                $table->dropColumn('shipping_address');
            }
            
            // Cập nhật enum status
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->change();
            
            // Cập nhật enum payment_method
            $table->enum('payment_method', ['cash', 'cod', 'bank_transfer'])->change();
            
            // Xóa cột payment_status nếu tồn tại
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Khôi phục user_id không được null
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            
            // Xóa các cột đã thêm
            $table->dropColumn([
                'order_number',
                'full_name', 
                'phone',
                'email',
                'address',
                'shipping_method',
                'subtotal',
                'shipping_fee',
                'notes'
            ]);
            
            // Khôi phục cột cũ
            if (Schema::hasColumn('orders', 'total')) {
                $table->renameColumn('total', 'total_price');
            }
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->string('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status');
            }
        });
    }
};
