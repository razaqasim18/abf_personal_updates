<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\NullableType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('business_name')->nullable(true);
            $table->longText('description')->nullable(true);
            $table->text('category')->nullable(true);
            $table->text('shop_phone')->nullable(true);
            $table->text('mobile_phone')->nullable(true);
            $table->text('business_logo')->nullable(true);
            $table->text('shop_card')->nullable(true);
            $table->text('business_mail')->nullable(true);
            $table->text('owner_image')->nullable(true);
            $table->text('website_link')->nullable(true);
            $table->text('social_media_link')->nullable(true);
            $table->text('business_address')->nullable(true);
            $table->text('other_data')->nullable(true);
            $table->boolean('is_blocked')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
