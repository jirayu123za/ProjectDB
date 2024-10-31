<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_promotions', function (Blueprint $table) {
            $table->uuid('product_promotion_id')->primary();
            $table->uuid('product_id');
            $table->uuid('promotion_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('promotion_id')->references('promotion_id')->on('promotions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_promotions');
    }
};
