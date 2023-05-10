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
        DB::statement("
            CREATE ALGORITHM = UNDEFINED 
            DEFINER = `rudi`@`localhost` 
            SQL SECURITY DEFINER
            VIEW `todays_presention_view` AS
                SELECT 
                    `d`.`id` AS `id`,
                    `p`.`id_presensi` AS `id_presensi`,
                    `u`.`nik` AS `nik`,
                    `d`.`jenis` AS `jenis`,
                    `d`.`jam` AS `jam`
                FROM
                    ((`presensi_details` `d`
                    JOIN `presensis` `p` ON ((`d`.`id_presensi` = `p`.`id_presensi`)))
                    JOIN `users` `u` ON ((`p`.`nik` = `u`.`nik`)))
                WHERE
                    (`p`.`tanggal` = CURDATE())
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS todays_presention_view");
    }
};