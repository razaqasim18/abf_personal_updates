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
        Schema::create('p_s_p_rewards', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(true);
            $table->integer('points')->nullable(true);
            $table->integer('reward')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_s_p_rewards');
    }
};
