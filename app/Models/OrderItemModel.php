<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'order_id', 'produk_id', 'nama_produk', 'harga_satuan', 'jumlah', 'subtotal',
    ];
    protected $useTimestamps = false;

    public function forOrder(int $orderId): array
    {
        return $this->where('order_id', $orderId)->findAll();
    }
}
