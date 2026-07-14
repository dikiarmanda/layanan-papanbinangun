<?php

namespace App\Models;

use CodeIgniter\Model;

class WhatsappLogModel extends Model
{
    protected $table            = 'whatsapp_log';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'tipe', 'reservasi_id', 'order_id', 'no_hp_tujuan', 'pesan', 'status_kirim',
    ];
    protected $useTimestamps = false;
}
