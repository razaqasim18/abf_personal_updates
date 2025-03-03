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
        Schema::table('vendor_orders', function (Blueprint $table) {
            $table->double('vendor_amount', 100, 2)->default("0.00")->after('total_bill');
            $table->double('commission', 100, 2)->default("0.00")->after('total_bill');
            $table->double('commission_amount', 100, 2)->default("0.00")->after('total_bill');
            $table->boolean('is_order_handle_by_admin')->default(0)->after('status');
            $table->timestamp('delivery_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_orders', function (Blueprint $table) {
            $table->dropColumn('vendor_amount');
            $table->dropColumn('commission');
            $table->dropColumn('commission_amount');
            $table->dropColumn('is_order_handle_by_admin');
            $table->dropColumn('delivery_at');
            $table->dropSoftDeletes();
        });
    }
};
