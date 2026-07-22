<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'order';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'kode_order',
        'pelanggan_id',
        'alamat_kirim',
        'tanggal_acara',
        'waktu_acara',
        'metode_pengiriman',
        'zona_antar_id',
        'kota_tujuan_id',
        'kota_tujuan_nama',
        'kurir',
        'layanan_kurir',
        'estimasi_ongkir',
        'ongkos_kirim',
        'total_harga',
        'no_resi',
        'status_pembayaran',
        'status_order',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'paid_at',
        'stok_locked',
    ];
    protected $useTimestamps = true;

    // `order` is reserved — escape via protected $table is enough in CI4 Query Builder

    public function findByKode(string $kode): ?array
    {
        return $this->where('kode_order', $kode)->first();
    }

    public function findByMidtransOrderId(string $orderId): ?array
    {
        return $this->where('midtrans_order_id', $orderId)->first();
    }

    public function withDetails(?int $limit = 20): array
    {
        return $this->select('`order`.*, pelanggan.nama as pelanggan_nama, pelanggan.no_hp')
            ->join('pelanggan', 'pelanggan.id = `order`.pelanggan_id')
            ->orderBy('`order`.created_at', 'DESC')
            ->findAll($limit);
    }
}
