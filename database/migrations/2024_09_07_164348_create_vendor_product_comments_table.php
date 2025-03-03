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
        Schema::create('vendor_product_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('vendor_product_id');
            $table->foreign('vendor_product_id')->references('id')->on('vendor_products')->onDelete('cascade')->onUpdate('cascade');

            // Self-referential foreign key for replies
            $table->unsignedBigInteger('parent_id')->nullable();  // Points to another comment
            $table->foreign('parent_id')->references('id')->on('vendor_product_comments')->onDelete('cascade');

            // The content of the comment
             $table->float("rating", 10, 2)->default("0.00");
            $table->text('content')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_product_comments');
    }
};
