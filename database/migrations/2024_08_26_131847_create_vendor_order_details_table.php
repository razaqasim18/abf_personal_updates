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
        Schema::create('vendor_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_order_id');
            $table->unsignedBigInteger('vendor_product_id');
            $table->string('product', 100);
            $table->double('weight', 100, 2)->default("0.00");
            $table->integer('quantity')->default("0");
            $table->integer('points')->default("0")->nullable(true);
            $table->double('price', 100, 2)->nullable(true);
            $table->foreign('vendor_order_id')->references('id')->on('vendor_orders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vendor_product_id')->references('id')->on('vendor_products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_order_details');
    }
};
