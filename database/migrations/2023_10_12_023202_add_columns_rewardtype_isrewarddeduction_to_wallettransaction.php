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
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->integer('reward_type')
                ->default("0")
                ->comment('0 no reward,1 register,2 20% reward,3 team reward,4 PSP reward,5 wallet to reward')
                ->after('is_gift');
            $table->boolean('is_reward_deducted')
                ->default("0")
                ->comment('0 normal deduction,1 wallet to reward deduction')->after('is_gift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            //
            $table->dropColumn('reward_type');

            $table->dropColumn('is_reward_deducted');
        });
    }
};
