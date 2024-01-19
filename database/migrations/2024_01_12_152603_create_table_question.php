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
        Schema::create('main.question', function (Blueprint $table) {
            $table->string("id");
            $table->timestamps();
            $table->string("content");
            $table->jsonb("answer");
            $table->string("type");
            $table->string("teacher_id");
            $table->jsonb("correct_answer");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main.question');
    }
};
