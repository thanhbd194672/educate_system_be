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
        Schema::create('main.exam', function (Blueprint $table) {
            $table->string("id");
            $table->timestamps();
            $table->string("id_topic");
            $table->integer("is_final");
            $table->timestamp("time_to_do");
            $table->integer("status");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main.exam');
    }
};
