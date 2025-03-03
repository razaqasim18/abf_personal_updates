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
        Schema::table('points', function (Blueprint $table) {
            $table->unsignedBigInteger('psp_id')->nullable(true);
            $table->foreign('psp_id')
                ->references('id')
                ->on('p_s_p_rewards')
                ->onDelete('RESTRICT') // Use 'RESTRICT', not 'ristrict'
                ->onUpdate('CASCADE');


            // Drop the existing foreign key constraint
            $table->dropForeign(['commission_id']);

            // Add a new foreign key constraint with the updated onDelete action
            $table->foreign('commission_id')
                ->references('id')
                ->on('commissions')
                ->onDelete('RESTRICT') // Change the action to 'RESTRICT'
                ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('points', function (Blueprint $table) {
            //
            $table->dropForeign(['psp_id']); // Assuming 'commission_id' is the column in your table with the foreign key constraint.
            $table->dropColumn('psp_id');
            $table->dropForeign(['commission_id']); // Assuming 'commission_id' is the column in your table with the foreign key constraint.
            $table->foreign('commission_id')->references('id')->on('commissions')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
