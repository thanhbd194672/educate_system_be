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
        Schema::create('main.topic', function (Blueprint $table) {
            $table->string("id");
            $table->string("name");
            $table->text("description")->nullable();
            $table->integer("is_free");
            $table->string("id_course");
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main.topic');
    }
};
