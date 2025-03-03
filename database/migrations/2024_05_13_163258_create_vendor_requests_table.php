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
        Schema::create('vendor_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('bank_id')->nullable(true);
            $table->string('transectionid', 100)->nullable(true);
            $table->date('transectiondate', 10)->nullable(true);
            $table->double('amount', 100, 2)->nullable(true);
            $table->string('proof', 100)->nullable(true);
            $table->text('remarks')->nullable();
            $table->timestamp('payment_approved_at')->nullable(true);
            $table->tinyInteger('status')->default("1")->comment("-1 payment rejected,0 application rejected, 1 initiated request, 2 application approved,3 payment made,4 payment accepted");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade')->onUpdate('cascade');
            $table->text('vendor_data')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_requests');
    }
};
