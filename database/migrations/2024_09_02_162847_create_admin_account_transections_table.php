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
        Schema::create('admin_account_transections', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 100, 2)->default("0.00")->nullable(true);
            $table->boolean('is_credit')->default(0)->comment("0 debit,1 credit");
            $table->text('description')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_account_transections');
    }
};
