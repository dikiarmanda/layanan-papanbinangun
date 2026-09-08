<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToPaketWisata extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('deleted_at', 'paket_wisata')) {
            return;
        }

        $this->forge->addColumn('paket_wisata', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        try {
            $this->db->query('ALTER TABLE `paket_wisata` ADD INDEX `idx_paket_wisata_deleted_at` (`deleted_at`)');
        } catch (\Throwable $e) {
            // index sudah ada
        }
    }

    public function down()
    {
        try {
            $this->db->query('ALTER TABLE `paket_wisata` DROP INDEX `idx_paket_wisata_deleted_at`');
        } catch (\Throwable $e) {
            // index belum ada
        }

        if ($this->db->fieldExists('deleted_at', 'paket_wisata')) {
            $this->forge->dropColumn('paket_wisata', 'deleted_at');
        }
    }
}
