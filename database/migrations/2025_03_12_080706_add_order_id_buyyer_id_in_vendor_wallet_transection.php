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
        Schema::table('vendor_wallet_transactions', function (Blueprint $table) {
            //
            // $table->id();
            $table->unsignedBigInteger('vendor_order_id')->nullable(true);
            $table->unsignedBigInteger('buyyer_id')->nullable(true);

            $table->foreign('vendor_order_id')->references('id')->on('vendor_orders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('buyyer_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_wallet_transactions', function (Blueprint $table) {
            //
            $table->dropForeign('vendor_wallet_transactions_vendor_order_id_foreign');
            $table->dropColumn('vendor_order_id');
            $table->dropForeign('vendor_wallet_transactions_buyyer_id_foreign');
            $table->dropColumn('buyyer_id');
        });
    }
};
