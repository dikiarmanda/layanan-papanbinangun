<?php

namespace App\Models;

use CodeIgniter\Model;

class ZonaAntarLokalModel extends Model
{
    protected $table = 'zona_antar_lokal';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'nama',
        'deskripsi',
        'ongkir',
        'estimasi',
        'status',
    ];
    protected $useTimestamps = true;

    public function findAktif(): array
    {
        return $this->where('status', 'aktif')
            ->orderBy('ongkir', 'ASC')
            ->findAll();
    }
}
