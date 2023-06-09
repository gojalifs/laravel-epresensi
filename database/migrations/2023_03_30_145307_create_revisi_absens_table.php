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
            $table->string('yang_direvisi', 20);
            $table->string('alasan', 100);
            $table->string('bukti_path', 255)->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->string('approval', 20)->nullable();
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