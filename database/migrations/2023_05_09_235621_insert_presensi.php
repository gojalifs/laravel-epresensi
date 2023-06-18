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
        DB::unprepared("
        CREATE DEFINER=`rudi`@`localhost` PROCEDURE `insert_presensi`(
            IN p_nik BIGINT(20),
            
            IN p_jenis ENUM('masuk', 'pulang'),
            
            IN p_longitude VARCHAR(20),
            IN p_latitude VARCHAR(20),
            IN p_img_path VARCHAR(255)
        )
        BEGIN
            DECLARE presensi_id varchar(10);
            DECLARE count_user int;
            DECLARE count_today int;
            DECLARE p_tanggal DATE;
            DECLARE p_jam TIME;
        
            START TRANSACTION;
            
            -- get id presensi
            SET presensi_id = (SELECT id_presensi FROM presensis WHERE tanggal = DATE(NOW()) AND nik = p_nik);
            SET p_tanggal = date(NOW());
            SET p_jam = time(now());
            
            -- check today data
            SELECT COUNT(*) INTO count_user FROM presensis WHERE tanggal = p_tanggal and nik = p_nik;
            SELECT COUNT(*) INTO count_today FROM presensis WHERE tanggal = p_tanggal;
            IF(count_user = 0) THEN 
            	IF (count_today = 0) THEN
                	SET presensi_id = CONCAT(DATE_FORMAT(NOW(), '%d%m%y'), LPAD('1', 4, '0'));
                ELSE
                	SET presensi_id = CONCAT(DATE_FORMAT(NOW(), '%d%m%y'), LPAD((SELECT COUNT(*) + 1 FROM presensis WHERE tanggal = p_tanggal), 4, '0'));
                END IF;
                INSERT INTO presensis (id_presensi, nik, tanggal) VALUES (presensi_id, p_nik, p_tanggal);
            END IF;
        
            -- check for details if already
            select COUNT(*) INTO count_user FROM presensi_details WHERE id_presensi = presensi_id COLLATE utf8mb4_general_ci AND jenis = p_jenis COLLATE utf8mb4_general_ci;
            IF(count_user = 0) THEN 
                INSERT INTO presensi_details (id_presensi, jenis, jam, longitude, latitude, img_path)
                VALUES (presensi_id, p_jenis, p_jam, p_longitude, p_latitude, p_img_path);
            ELSE
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'You Already Check In/Out';
            END IF;
        
            COMMIT;
        END
        ");
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS `insert_presensi`;');
    }
};