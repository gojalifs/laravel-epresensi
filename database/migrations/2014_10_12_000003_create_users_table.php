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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20)->unique();
            $table->string('nama', 255);
            $table->string('nipns')->unique()->nullable();
            $table->string('email')->unique();
            $table->enum('gender', ['L', 'P']);
            $table->string('telp', 18)->unique();
            $table->tinyInteger('is_admin')->default(0);
            $table->string('ava_path', 50)->default('-');
            $table->string('password', 255)->nullable();
            $table->string('token', 128)->nullable();
            $table->dateTime('token_expiry')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
