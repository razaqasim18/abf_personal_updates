<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            //
            $table->renameColumn('banner', 'title');
            // Use raw SQL to modify the 'type' column safely
            DB::statement("ALTER TABLE banners MODIFY type VARCHAR(50)");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->renameColumn('title', 'banner');
            DB::statement("ALTER TABLE banners MODIFY type TINYINT(1)");
        });
    }
};
