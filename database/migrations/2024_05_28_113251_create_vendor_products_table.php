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
        Schema::create('vendor_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_category_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('user_id');
            $table->string('product', 100);
            $table->double('price', 8, 2)->nullable(true);
            $table->double('purchase_price', 8, 2)->nullable(true);
            $table->longText('description')->nullable(true);
            $table->integer('points')->default(0)->nullable(true);
            $table->integer('stock')->default(0);
            $table->double('weight', 8, 3)->default(0.00);
            $table->string('image')->nullable(true);
            $table->double('discount', 8, 2)->nullable(true);
            $table->boolean('is_discount')->default(0)->comment("0 not, 1 active");
            $table->boolean('is_active')->default(0)->comment("0 not, 1 active");
            $table->boolean('in_stock')->default(1)->comment("0 not, 1 stock");
            $table->boolean('is_approved')->default(0)->comment("-1 not approved ,0 pending, 1 approved");
            $table->longText('remarks')->nullable(true);

            // Foreign key constraints
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vendor_category_id')->references('id')->on('vendor_categories')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_products');
    }
};
