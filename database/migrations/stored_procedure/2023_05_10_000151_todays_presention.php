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
        CREATE DEFINER=`fajar`@`localhost` PROCEDURE `todays_presention`(IN nik_param varchar(30), IN date_param date)
        BEGIN
        
            DECLARE date_p date;
            
            IF (date_param IS NULL) THEN
                SET date_p = current_date();
            END IF;
            SELECT 
                p.id AS id,
                p.id_presensi AS id_presensi,
                p.tanggal AS tanggal,
                u.nik AS nik,
                d.jenis AS jenis,
                d.jam AS jam,
                d.img_path AS img_path
            FROM
                presensi_details d
                JOIN presensis p ON d.id_presensi = p.id_presensi
                JOIN users u ON p.nik = u.nik
            WHERE u.nik COLLATE utf8mb4_general_ci = nik_param COLLATE utf8mb4_general_ci AND p.tanggal = date_param;
            
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
        DB::unprepared('DROP PROCEDURE IF EXISTS `todays_presention`;');
    }
};