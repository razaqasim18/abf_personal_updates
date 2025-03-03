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
        Schema::table('order_details', function (Blueprint $table) {
            $table->boolean('product_type')->default('0')->comment("0 mean products id of shop or other brand and 1 is mean its of customized");
            $table->boolean('product_is_coupon')->default('0');
            $table->boolean('product_is_coupon_used')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            //
            $table->dropColumn('product_type');
            $table->dropColumn('product_is_coupon');
            $table->dropColumn('product_is_coupon_used');
        });
    }
};
