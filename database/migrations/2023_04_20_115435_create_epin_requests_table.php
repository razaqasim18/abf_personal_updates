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
        Schema::create('epin_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id');
            $table->string('epin', 50)->nullable(true);
            $table->string('transectionid', 100);
            $table->string('email')->unique();
            $table->string('phone');
            $table->double('amount', 100, 2);
            $table->string('proof', 100);
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('approved_at')->nullable(true);
            $table->date('transectiondate', 10);
            $table->unsignedBigInteger('allotted_to_user_id')->nullable(true);
            $table->tinyInteger('status')->default("0")->comment("0 pending, 1 accepted,2 rejected");
            $table->foreign('allotted_to_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epin_requests');
    }
};
