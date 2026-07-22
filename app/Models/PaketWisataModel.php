<?php

namespace App\Models;

use CodeIgniter\Model;

class PaketWisataModel extends Model
{
    protected $table = 'paket_wisata';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'nama',
        'slug',
        'jenis',
        'deskripsi',
        'harga',
        'satuan_harga',
        'kuota_default',
        'gambar_cover',
        'status',
        'admin_id',
    ];
    protected $useTimestamps = true;

    public function findPublished(?int $limit = null, ?string $jenis = null): array
    {
        $builder = $this->where('status', 'publish')->orderBy('created_at', 'DESC');
        if ($jenis !== null && $jenis !== '') {
            $builder->where('jenis', $jenis);
        }
        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }
}
