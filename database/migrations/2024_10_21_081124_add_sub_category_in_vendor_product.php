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
        Schema::table('vendor_products', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('vendor_sub_category_id')->after("vendor_category_id")->nullable(true);
            $table->foreign('vendor_sub_category_id')->references('id')->on('vendor_sub_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            //
            $table->dropForeign('vendor_products_vendor_sub_category_id_foreign');
            $table->dropColumn('vendor_sub_category_id');
        });
    }
};
