<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('
            CREATE DEFINER=`fajar`@`localhost` PROCEDURE `sp_laporan_bulanan`(IN p_nik VARCHAR(255), IN p_bulan INT, IN p_tahun INT)
            BEGIN
                SELECT p.id_presensi, u.nik, u.nama, p.tanggal, pd.jenis, pd.jam, pd.longitude, pd.latitude
                FROM presensis p
                INNER JOIN users u ON p.nik = u.nik
                INNER JOIN presensi_details pd ON p.id_presensi = pd.id_presensi
                WHERE p.nik COLLATE utf8mb4_general_ci = p_nik COLLATE utf8mb4_general_ci 
                AND MONTH(p.tanggal) = p_bulan AND YEAR(p.tanggal) = p_tahun;
            END
        ');
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS `sp_laporan_bulanan`;');
    }
};