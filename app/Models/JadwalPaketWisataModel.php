<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalPaketWisataModel extends Model
{
    protected $table            = 'jadwal_paket_wisata';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'paket_wisata_id', 'tanggal', 'kuota', 'kuota_terpakai',
    ];
    protected $useTimestamps = false;

    public function availableForPaket(int $paketId): array
    {
        return $this->where('paket_wisata_id', $paketId)
            ->where('tanggal >=', date('Y-m-d'))
            ->orderBy('tanggal', 'ASC')
            ->findAll();
    }

    /**
     * Lock kuota dalam transaksi DB. Return false jika penuh.
     */
    public function lockKuota(int $jadwalId, int $jumlah): bool
    {
        $db = $this->db;
        $db->transStart();

        $jadwal = $db->query(
            'SELECT * FROM jadwal_paket_wisata WHERE id = ? FOR UPDATE',
            [$jadwalId]
        )->getRowArray();

        if (! $jadwal) {
            $db->transRollback();

            return false;
        }

        if ((int) $jadwal['kuota_terpakai'] + $jumlah > (int) $jadwal['kuota']) {
            $db->transRollback();

            return false;
        }

        $db->table('jadwal_paket_wisata')
            ->where('id', $jadwalId)
            ->set('kuota_terpakai', 'kuota_terpakai + ' . (int) $jumlah, false)
            ->update();

        $db->transComplete();

        return $db->transStatus();
    }

    public function releaseKuota(int $jadwalId, int $jumlah): bool
    {
        return (bool) $this->db->table('jadwal_paket_wisata')
            ->where('id', $jadwalId)
            ->where('kuota_terpakai >=', $jumlah)
            ->set('kuota_terpakai', 'kuota_terpakai - ' . (int) $jumlah, false)
            ->update();
    }
}
