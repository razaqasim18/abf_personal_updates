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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->string('product', 100);
            $table->double('price', 100, 2)->nullable(true);
            $table->double('purchase_price', 100, 2)->nullable(true);
            $table->longText('description')->nullable(true);
            $table->integer('points')->default("0")->nullable(true);
            $table->integer('stock')->default("0");
            $table->double('weight', 100, 3)->default("0.00");
            $table->string('image')->nullable(true);
            $table->double('discount', 100, 2)->nullable(true);
            $table->boolean('is_discount')->default("0")->comment("0 not, 1 active");
            $table->boolean('is_active')->default("1")->comment("0 not, 1 active");
            $table->boolean('in_stock')->default("1")->comment("0 not, 1 stock");
            $table->boolean('is_other')->default("1")->comment("0 not, 1 other");
            $table->boolean('is_feature')->default("0")->comment("0 not, 1 feature, 2 customized");
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
