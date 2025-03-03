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
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('order_no');
            $table->integer('points')->default("0");
            $table->integer('weight')->default("0");
            $table->double('subtotal', 100, 2)->default("0.00");
            $table->double('shippingcharges', 100, 2)->default("0.00");
            $table->double('discount', 100, 2)->default("0.00");
            $table->double('total_bill', 100, 2)->default("0.00");
            $table->boolean('payment_by')->default(0)->comment("O cash,1 wallet,2 gift");
            $table->boolean('status')->default(0)->comment("-2 returned,-1 cancelled,0 pending,1 processing,2 approved,3 delivered,4 reorder,5 reapproved,6 redelivered");
            $table->string('delivery_by')->nullable(true);
            $table->string('delivery_trackingid')->nullable(true);
            $table->integer('order_return')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_orders');
    }
};
