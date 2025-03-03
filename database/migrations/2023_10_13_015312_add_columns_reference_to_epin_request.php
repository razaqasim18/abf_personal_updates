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
        Schema::table('epin_requests', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('bank_id')->nullable(true)->default(null)->change();
            $table->string('proof')->nullable(true)->default(null)->change();
            $table->string('transectionid')->nullable(true)->default(null)->change();
            $table->date('transectiondate')->nullable(true)->default(null)->change();
            // $table->unsignedBigInteger('referred_by')->nullable(true)->default(null)->after('allotted_to_user_id');
            // $table->foreign('referred_by')
            //    ->references('id')->on('users')
            //    ->onDelete('cascade')->onUpdate('cascade');
            // $table->tinyInteger('referred_payed_by')
            //     ->nullable(true)
            //     ->default(null)
            //     ->comment('0 by wallet,1 by reward')
            //     ->after('referred_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('epin_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id')->nullable(false)->change();
            $table->string('proof')->nullable(false)->change();
            $table->string('transectionid')->nullable(false)->change();
            $table->date('transectiondate')->nullable(false)->change();
            $table->dropForeign(['referred_by']);
            $table->dropColumn('referred_by');
            $table->dropColumn('referred_payed_by');
        });
    }
};
