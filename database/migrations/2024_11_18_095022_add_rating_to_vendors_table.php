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
        Schema::table('vendors', function (Blueprint $table) {
            //
            $table->float("rating", 10,2)->default("0.00")->after('delivery_charges');
             $table->float("outstanding_amount", 100, 2)->default("0.00")->after('delivery_charges');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
            $table->dropColumn('rating');
            $table->dropColumn('outstanding_amount');
        });
    }
};
