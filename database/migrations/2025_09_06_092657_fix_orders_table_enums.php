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
            // Sửa enum shipping_method
            $table->enum('shipping_method', ['standard', 'express', 'pickup'])->change();
            
            // Sửa enum payment_method
            $table->enum('payment_method', ['cod', 'bank_transfer', 'credit_card', 'momo'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Khôi phục enum cũ
            $table->enum('shipping_method', ['standard', 'express'])->change();
            $table->enum('payment_method', ['cash', 'cod', 'bank_transfer'])->change();
        });
    }
};