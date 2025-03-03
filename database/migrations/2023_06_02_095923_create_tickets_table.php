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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('admins or users table'); //admin or user table
            $table->longText('title'); //admin or user table
            $table->tinyInteger('priority')->default(0)->comment('0 low,1 high');
            $table->tinyInteger('status')->default(0)->comment('0 open,1 closed'); // 0 opening, 1 closed
            // $table->boolean("is_seen")->default(false); // 0 not seen , 1 seen
            $table->integer('is_answer')->nullable()->comment('answered by from admins'); // 0 not seen , 1 seen
            $table->tinyInteger('user_type')->default(0)->comment('0 admins,1 users'); // 0 admin , 1 user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
