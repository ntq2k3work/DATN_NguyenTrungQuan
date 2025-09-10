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
            // Add 'confirmed' status to the status ENUM
            $table->enum('status', ['pending', 'processing', 'confirmed', 'shipped', 'delivered', 'cancelled', 'returned'])->change();
            
            // Also ensure 'vnpay' is included in payment_method ENUM
            $table->enum('payment_method', ['cod', 'bank_transfer', 'credit_card', 'momo', 'vnpay'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert to original ENUM values
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->change();
            $table->enum('payment_method', ['cod', 'bank_transfer', 'credit_card', 'momo'])->change();
        });
    }
};
