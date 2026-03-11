<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBeritaTable extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE TABLE `berita` (
                `BERITA_ID`           INT(10)       NOT NULL AUTO_INCREMENT,
                `BERITA_JUDUL`        VARCHAR(255)  NOT NULL COLLATE 'utf8mb4_general_ci',
                `BERITA_SLUG`         VARCHAR(255)  NOT NULL COLLATE 'utf8mb4_general_ci',
                `BERITA_THUMBNAIL`    VARCHAR(255)  NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                `BERITA_KATEGORI`     ENUM('Pengumuman','Kegiatan','Prestasi','Lainnya') NOT NULL DEFAULT 'Lainnya' COLLATE 'utf8mb4_general_ci',
                `BERITA_ISI`          LONGTEXT      NOT NULL COLLATE 'utf8mb4_general_ci',
                `BERITA_STATUS`       ENUM('draft','published') NOT NULL DEFAULT 'draft' COLLATE 'utf8mb4_general_ci',
                `BERITA_PUBLISHED_AT` DATETIME      NULL DEFAULT NULL,
                `BERITA_WAKTU_BUAT`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `BERITA_WAKTU_UBAH`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`BERITA_ID`) USING BTREE,
                UNIQUE KEY `berita_slug_unique` (`BERITA_SLUG`)
            )
            COLLATE='utf8mb4_general_ci'
            ENGINE=InnoDB
        ");
    }

    public function down()
    {
        Schema::dropIfExists('berita');
    }
}