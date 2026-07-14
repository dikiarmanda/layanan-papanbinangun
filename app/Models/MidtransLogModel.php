<?php

namespace App\Models;

use CodeIgniter\Model;

class MidtransLogModel extends Model
{
    protected $table            = 'midtrans_log';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'tipe', 'reservasi_id', 'order_id', 'midtrans_order_id',
        'transaction_status', 'payload', 'signature_valid',
    ];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
}
