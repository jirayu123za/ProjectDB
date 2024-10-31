<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->uuid('cart_item_id')->primary();
            $table->uuid('cart_id');
            $table->uuid('product_id');
            $table->integer('quantity');
            $table->integer('unit_price');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cart_id')->references('cart_id')->on('carts');
            $table->foreign('product_id')->references('product_id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
