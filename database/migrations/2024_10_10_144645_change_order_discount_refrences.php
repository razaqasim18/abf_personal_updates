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
        Schema::table('order_discounts', function (Blueprint $table) {

            $table->dropForeign('order_discounts_order_id_foreign');
            $table->dropColumn('order_id');
            $table->dropForeign('order_discounts_product_id_foreign');
            $table->dropColumn('product_id');
            // Polymorphic relationship for orders
            $table->unsignedBigInteger('orderable_id');
            $table->string('orderable_type'); // Can be 'App\Models\Order' or any other model

            // Polymorphic relationship for products
            $table->unsignedBigInteger('productable_id');
            $table->string('productable_type'); // Can be 'App\Models\Product' or any other model

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_discounts', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
