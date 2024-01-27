<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'pgsql_main';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main.accounts', function (Blueprint $table) {
            $table->string('id');
            $table->string('username')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('tel_number')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->jsonb('social_network')->nullable();
            $table->jsonb('avatar');
            $table->integer('role');
            $table->integer('status');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main.users');
    }
};
