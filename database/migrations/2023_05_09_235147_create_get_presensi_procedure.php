<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migration.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE DEFINER=`rudi`@`localhost` PROCEDURE `get_presensi`(IN tanggal DATE)
            BEGIN
                SELECT ROW_NUMBER() OVER () AS id, u.nama, u.nik, u.nipns, p.id_presensi, p.tanggal, pd.jenis, pd.jam, pd.longitude, pd.latitude, pd.img_path
                FROM users u
                LEFT JOIN presensis p ON u.nik = p.nik AND p.tanggal = tanggal
                LEFT JOIN presensi_details pd ON p.id_presensi = pd.id_presensi
                ORDER BY p.id_presensi IS NULL DESC, u.nik;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS `get_presensi`;');
    }
};