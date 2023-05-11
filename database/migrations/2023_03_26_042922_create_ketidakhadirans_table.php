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
        Schema::create('ketidakhadirans', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20);
            $table->foreign('nik')->references('nik')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->date('tanggal');
            $table->date('tanggal_selesai')->nullable();
            $table->string('alasan', 100);
            $table->String('potong_cuti')->default('tidak');
            $table->tinyInteger('status')->default(0);
            $table->string('approval_id', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketidakhadirans');
    }
};