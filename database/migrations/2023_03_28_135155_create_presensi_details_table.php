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
        Schema::create('presensi_details', function (Blueprint $table) {
            $table->id();
            $table->string('id_presensi', 30);
            $table->foreign('id_presensi')->references('id_presensi')->on('presensis')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->time('jam');
            $table->string('longitude', 20);
            $table->string('latitude', 20);
            $table->string('img_path', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_details');
    }
};