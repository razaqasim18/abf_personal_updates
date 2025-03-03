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
        Schema::create('vendor_withdraws', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('transectionid', 100)->nullable(true);
            $table->date('transectiondate', 10)->nullable(true);
            $table->double('transectioncharges', 100, 2)->nullable(true);
            $table->double('requested_amount', 100, 2);
            $table->double('cashout_amount', 100, 2)->nullable();
            $table->string('proof', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->default("0")->comment("0 pending, 1 accepted");
            $table->unsignedBigInteger('approved_id')->nullable(true);
            $table->timestamp('approved_at')->nullable(true);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('approved_id')->references('id')->on('admins')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_withdraws');
    }
};
