<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main.course', function (Blueprint $table) {
            $table->string('id');
            $table->string('name_course');
            $table->string('description');
            $table->string('subject');
            $table->jsonb('image')->nullable();
            $table->timestamp('time_to_learn');
            $table->string('teacher_id');
            $table->double('price');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main.course');
    }
};
