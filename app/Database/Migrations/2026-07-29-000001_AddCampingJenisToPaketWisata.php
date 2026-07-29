<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCampingJenisToPaketWisata extends Migration
{
    public function up()
    {
        // ENUM MySQL: ubah lewat raw SQL agar aman di shared hosting.
        $this->db->query("ALTER TABLE `paket_wisata`
            MODIFY `jenis` ENUM('wisata','homestay','camping') NOT NULL DEFAULT 'wisata'");

        // Index opsional — abaikan jika sudah ada.
        try {
            $this->db->query('ALTER TABLE `paket_wisata` ADD INDEX `idx_paket_wisata_jenis` (`jenis`)');
        } catch (\Throwable $e) {
            // index sudah ada
        }
    }

    public function down()
    {
        $this->db->query('UPDATE `paket_wisata` SET `jenis` = \'wisata\' WHERE `jenis` = \'camping\'');

        try {
            $this->db->query('ALTER TABLE `paket_wisata` DROP INDEX `idx_paket_wisata_jenis`');
        } catch (\Throwable $e) {
            // index belum ada
        }

        $this->db->query("ALTER TABLE `paket_wisata`
            MODIFY `jenis` ENUM('wisata','homestay') NOT NULL DEFAULT 'wisata'");
    }
}
