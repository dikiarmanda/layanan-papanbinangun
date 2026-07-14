<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservasiModel extends Model
{
    protected $table            = 'reservasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'kode_reservasi', 'paket_wisata_id', 'jadwal_id', 'pelanggan_id',
        'jumlah_tamu', 'catatan', 'total_harga', 'status_pembayaran',
        'status_reservasi', 'midtrans_order_id', 'midtrans_transaction_id',
        'paid_at', 'kuota_locked',
    ];
    protected $useTimestamps = true;

    public function findByKode(string $kode): ?array
    {
        return $this->where('kode_reservasi', $kode)->first();
    }

    public function findByMidtransOrderId(string $orderId): ?array
    {
        return $this->where('midtrans_order_id', $orderId)->first();
    }

    public function withDetails(?int $limit = 20): array
    {
        return $this->select('reservasi.*, pelanggan.nama as pelanggan_nama, pelanggan.no_hp, paket_wisata.nama as paket_nama')
            ->join('pelanggan', 'pelanggan.id = reservasi.pelanggan_id')
            ->join('paket_wisata', 'paket_wisata.id = reservasi.paket_wisata_id')
            ->orderBy('reservasi.created_at', 'DESC')
            ->findAll($limit);
    }
}
