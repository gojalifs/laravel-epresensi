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
        Schema::create('revisi_absens', function (Blueprint $table) {
            $table->id();
            $table->string('user_nik', 20);
            $table->foreign('user_nik')->references('nik')->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam');
            $table->string('alasan', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisi_absens');
    }
};