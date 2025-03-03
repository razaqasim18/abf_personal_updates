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
        Schema::create('vendor_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('sub_category', 100);
            $table->unsignedBigInteger('vendor_category_id')->nullable(true);
            $table->foreign('vendor_category_id')->references('id')->on('vendor_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_sub_categories');
    }
};
