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
        Schema::create('izin_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('user_nik', 20);
            $table->foreign('user_nik')->references('nik')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_keluar')->nullable();
            $table->time('jam_kembali')->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->string('approval', 20)->nullable();
            $table->foreign('approval')->references('nik')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_keluars');
    }
};