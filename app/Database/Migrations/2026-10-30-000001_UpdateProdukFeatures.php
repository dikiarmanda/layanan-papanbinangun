<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProdukFeatures extends Migration
{
    public function up()
    {
        $fields = [];

        if (!$this->db->fieldExists('foto_tambahan', 'produk')) {
            $fields['foto_tambahan'] = [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'gambar',
            ];
        }

        if (!$this->db->fieldExists('opsi_varian', 'produk')) {
            $fields['opsi_varian'] = [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'berat',
            ];
        }

        if (!$this->db->fieldExists('min_beli', 'produk')) {
            $fields['min_beli'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'after'      => 'stok',
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('produk', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('foto_tambahan', 'produk')) {
            $this->forge->dropColumn('produk', 'foto_tambahan');
        }
        if ($this->db->fieldExists('opsi_varian', 'produk')) {
            $this->forge->dropColumn('produk', 'opsi_varian');
        }
        if ($this->db->fieldExists('min_beli', 'produk')) {
            $this->forge->dropColumn('produk', 'min_beli');
        }
    }
}
