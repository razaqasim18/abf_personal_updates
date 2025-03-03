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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->startingValue(999);
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('cnic');
            $table->string('sponserid')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->string('dob')->nullable(true);
            $table->string('image')->nullable(true);
            $table->string('gender')->nullable(true)->comment("0 for female , 1 for male");
            $table->string('cnic_image_front')->nullable(true);
            $table->string('cnic_image_back')->nullable(true);
            $table->boolean('is_blocked')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
