<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriProdukModel;
use App\Models\ProdukModel;

class ProdukAdminController extends BaseController
{
    public function index()
    {
        helper('layanan');
        $q = trim((string) $this->request->getGet('q'));

        $builder = model(ProdukModel::class)
            ->select('produk.*, kategori_produk.nama as kategori_nama')
            ->join('kategori_produk', 'kategori_produk.id = produk.kategori_id', 'left');

        if ($q !== '') {
            $builder->groupStart()
                ->like('produk.nama', $q)
                ->orLike('produk.jenis', $q)
                ->orLike('kategori_produk.nama', $q)
                ->groupEnd();
        }

        $produk = $builder->orderBy('produk.created_at', 'DESC')->findAll();

        return view('admin/produk/index', [
            'title'  => 'Kelola Produk',
            'produk' => $produk,
            'q'      => $q,
        ]);
    }

    public function create()
    {
        return view('admin/produk/form', [
            'title' => 'Tambah Produk',
            'produk' => null,
            'kategori' => model(KategoriProdukModel::class)->findAll(),
        ]);
    }

    public function store()
    {
        helper('layanan');

        try {
            $nama = (string) $this->request->getPost('nama');
            $img = upload_cropped_image('gambar_cropped', 'produk') ?? upload_image('gambar', 'produk');
            $jenis = $this->request->getPost('jenis') === 'catering' ? 'catering' : 'umkm';

            $fotoTambahan = upload_multiple_images('foto_tambahan', 'produk', 4);

            model(ProdukModel::class)->insert([
                'nama'          => $nama,
                'slug'          => slugify($nama) . '-' . substr(uniqid(), -4),
                'jenis'         => $jenis,
                'deskripsi'     => $this->request->getPost('deskripsi'),
                'harga'         => $this->request->getPost('harga'),
                'stok'          => (int) $this->request->getPost('stok'),
                'min_beli'      => max(1, (int) ($this->request->getPost('min_beli') ?: 1)),
                'berat'         => (int) ($this->request->getPost('berat') ?: 1000),
                'opsi_varian'   => $this->parseOpsiVarian(),
                'gambar'        => $img,
                'foto_tambahan' => !empty($fotoTambahan) ? json_encode($fotoTambahan) : null,
                'kategori_id'   => $this->request->getPost('kategori_id') ?: null,
                'status'        => $this->request->getPost('status') ?: 'draft',
                'admin_id'      => session()->get('admin_id'),
            ]);

            return redirect()->to('/admin/produk')->with('success', 'Produk ditambahkan.');
        } catch (\Throwable $e) {
            log_message('error', 'ProdukAdminController::store — ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan produk. Silakan coba lagi.');
        }
    }

    public function edit(int $id)
    {
        $produk = model(ProdukModel::class)->find($id);
        if (!$produk) {
            return redirect()->to('/admin/produk');
        }

        return view('admin/produk/form', [
            'title'    => 'Edit Produk',
            'produk'   => $produk,
            'kategori' => model(KategoriProdukModel::class)->findAll(),
        ]);
    }

    public function update(int $id)
    {
        helper('layanan');

        try {
            $produk = model(ProdukModel::class)->find($id);
            if (!$produk) {
                return redirect()->to('/admin/produk')->with('error', 'Produk tidak ditemukan.');
            }

            $jenis = $this->request->getPost('jenis') === 'catering' ? 'catering' : 'umkm';

            $existingFoto = json_decode((string) ($produk['foto_tambahan'] ?? '[]'), true) ?: [];
            $hapusFoto = (array) ($this->request->getPost('hapus_foto_tambahan') ?? []);
            $kept = array_values(array_filter($existingFoto, static fn($f) => !in_array($f, $hapusFoto, true)));

            $slotAvailable = max(0, 4 - count($kept));
            $newPhotos = $slotAvailable > 0 ? upload_multiple_images('foto_tambahan', 'produk', $slotAvailable) : [];
            $finalPhotos = array_merge($kept, $newPhotos);

            $data = [
                'nama'          => $this->request->getPost('nama'),
                'jenis'         => $jenis,
                'deskripsi'     => $this->request->getPost('deskripsi'),
                'harga'         => $this->request->getPost('harga'),
                'stok'          => (int) $this->request->getPost('stok'),
                'min_beli'      => max(1, (int) ($this->request->getPost('min_beli') ?: 1)),
                'berat'         => (int) ($this->request->getPost('berat') ?: 1000),
                'opsi_varian'   => $this->parseOpsiVarian(),
                'foto_tambahan' => !empty($finalPhotos) ? json_encode($finalPhotos) : null,
                'kategori_id'   => $this->request->getPost('kategori_id') ?: null,
                'status'        => $this->request->getPost('status'),
            ];

            $img = upload_cropped_image('gambar_cropped', 'produk') ?? upload_image('gambar', 'produk');
            if ($img) {
                $data['gambar'] = $img;
            }

            model(ProdukModel::class)->update($id, $data);

            return redirect()->to('/admin/produk')->with('success', 'Produk diperbarui.');
        } catch (\Throwable $e) {
            log_message('error', 'ProdukAdminController::update — ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui produk. Silakan coba lagi.');
        }
    }

    private function parseOpsiVarian(): ?string
    {
        if (!$this->request->getPost('has_varian')) {
            return null;
        }

        $vNama = (array) ($this->request->getPost('varian_nama') ?? []);
        $vHarga = (array) ($this->request->getPost('varian_harga') ?? []);
        $vStok = (array) ($this->request->getPost('varian_stok') ?? []);
        $vBerat = (array) ($this->request->getPost('varian_berat') ?? []);

        $list = [];
        foreach ($vNama as $i => $name) {
            $name = trim((string) $name);
            if ($name === '') {
                continue;
            }
            $list[] = [
                'nama'  => $name,
                'harga' => (float) ($vHarga[$i] ?? 0),
                'stok'  => (int) ($vStok[$i] ?? 0),
                'berat' => (int) ($vBerat[$i] ?? 0),
            ];
        }

        return !empty($list) ? json_encode($list) : null;
    }

    public function delete(int $id)
    {
        try {
            model(ProdukModel::class)->delete($id);

            return redirect()->to('/admin/produk')->with('success', 'Produk dihapus.');
        } catch (\Throwable $e) {
            log_message('error', 'ProdukAdminController::delete — ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return redirect()->to('/admin/produk')->with('error', 'Gagal menghapus produk. Silakan coba lagi.');
        }
    }
}
