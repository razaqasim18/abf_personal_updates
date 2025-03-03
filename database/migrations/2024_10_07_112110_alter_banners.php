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
        Schema::table('banners', function (Blueprint $table) {
            $table->renameColumn('is_front', 'type')->comment("0 : user panel of website, 1: front of the website,2: vendor search page");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('banners', function (Blueprint $table) {
            $table->renameColumn('type', 'is_front');
        });
    }
};
