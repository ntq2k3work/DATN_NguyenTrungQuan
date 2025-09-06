g<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->index('title');
            $table->index('price');
            $table->index('quantity');
            $table->index('status');
            $table->index('category_id');
            $table->index('author_id');
            $table->index('publisher_id');
            $table->index('created_at');
            $table->index(['status', 'created_at']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('name');
            $table->index('slug');
            $table->index('parent_id');
            $table->index('created_at');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('payment_method');
            $table->index('shipping_method');
            $table->index('created_at');
            $table->index('user_id');
            $table->index(['status', 'created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('name');
            $table->index('role');
            $table->index('created_at');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('publishers', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('book_id');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('cart_id');
            $table->index('book_id');
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('book_id');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['title']);
            $table->dropIndex(['price']);
            $table->dropIndex(['quantity']);
            $table->dropIndex(['status']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['author_id']);
            $table->dropIndex(['publisher_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_method']);
            $table->dropIndex(['shipping_method']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['role']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('publishers', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['book_id']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['cart_id']);
            $table->dropIndex(['book_id']);
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['book_id']);
        });
    }
};
