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
        'min_beli',
        'berat',
        'opsi_varian',
        'gambar',
        'foto_tambahan',
        'kategori_id',
        'status',
        'admin_id',
    ];
    protected $useTimestamps = true;

    public function findPublished(?int $limit = null, ?int $kategoriId = null, ?string $jenis = null, ?string $q = null): array
    {
        $builder = $this->select('produk.*, kategori_produk.nama as kategori_nama')
            ->join('kategori_produk', 'kategori_produk.id = produk.kategori_id', 'left')
            ->where('produk.status', 'publish')
            ->orderBy('produk.created_at', 'RAND');

        if ($kategoriId) {
            $builder->where('produk.kategori_id', $kategoriId);
        }
        if ($jenis !== null && $jenis !== '') {
            $builder->where('produk.jenis', $jenis);
        }
        if ($q !== null && $q !== '') {
            $builder->groupStart()
                ->like('produk.nama', $q)
                ->orLike('produk.deskripsi', $q)
                ->groupEnd();
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
