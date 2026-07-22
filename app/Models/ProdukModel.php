<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'nama',
        'slug',
        'jenis',
        'deskripsi',
        'harga',
        'stok',
        'berat',
        'gambar',
        'kategori_id',
        'status',
        'admin_id',
    ];
    protected $useTimestamps = true;

    public function findPublished(?int $limit = null, ?int $kategoriId = null, ?string $jenis = null): array
    {
        $builder = $this->where('status', 'publish')->orderBy('created_at', 'DESC');
        if ($kategoriId) {
            $builder->where('kategori_id', $kategoriId);
        }
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

    public function lockStok(int $produkId, int $jumlah): bool
    {
        $db = $this->db;
        $db->transStart();

        $produk = $db->query(
            'SELECT * FROM produk WHERE id = ? FOR UPDATE',
            [$produkId]
        )->getRowArray();

        if (!$produk || (int) $produk['stok'] < $jumlah) {
            $db->transRollback();

            return false;
        }

        $db->table('produk')
            ->where('id', $produkId)
            ->set('stok', 'stok - ' . (int) $jumlah, false)
            ->update();

        $db->transComplete();

        return $db->transStatus();
    }

    public function releaseStok(int $produkId, int $jumlah): bool
    {
        return (bool) $this->db->table('produk')
            ->where('id', $produkId)
            ->set('stok', 'stok + ' . (int) $jumlah, false)
            ->update();
    }
}
