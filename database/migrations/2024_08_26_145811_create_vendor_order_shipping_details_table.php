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
        Schema::create('vendor_order_shipping_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_order_id');
            $table->string('name')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->string('address')->nullable(true);
            $table->string('shipping_address')->nullable(true);
            $table->string('other_information')->nullable(true);
            $table->unsignedBigInteger('city_id');
            $table->string('street')->nullable(true);
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vendor_order_id')->references('id')->on('vendor_orders')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_order_shipping_details');
    }
};
