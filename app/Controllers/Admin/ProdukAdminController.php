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
        $produk = model(ProdukModel::class)
            ->select('produk.*, kategori_produk.nama as kategori_nama')
            ->join('kategori_produk', 'kategori_produk.id = produk.kategori_id', 'left')
            ->orderBy('produk.created_at', 'DESC')
            ->findAll();

        return view('admin/produk/index', [
            'title'  => 'Kelola Produk',
            'produk' => $produk,
        ]);
    }

    public function create()
    {
        return view('admin/produk/form', [
            'title'    => 'Tambah Produk',
            'produk'   => null,
            'kategori' => model(KategoriProdukModel::class)->findAll(),
        ]);
    }

    public function store()
    {
        helper('layanan');
        $nama = (string) $this->request->getPost('nama');
        $img  = upload_image('gambar', 'produk');

        model(ProdukModel::class)->insert([
            'nama'        => $nama,
            'slug'        => slugify($nama) . '-' . substr(uniqid(), -4),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => (int) $this->request->getPost('stok'),
            'berat'       => (int) ($this->request->getPost('berat') ?: 1000),
            'gambar'      => $img,
            'kategori_id' => $this->request->getPost('kategori_id') ?: null,
            'status'      => $this->request->getPost('status') ?: 'draft',
            'admin_id'    => session()->get('admin_id'),
        ]);

        return redirect()->to('/admin/produk')->with('success', 'Produk ditambahkan.');
    }

    public function edit(int $id)
    {
        $produk = model(ProdukModel::class)->find($id);
        if (! $produk) {
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
        $produk = model(ProdukModel::class)->find($id);
        if (! $produk) {
            return redirect()->to('/admin/produk');
        }

        $data = [
            'nama'        => $this->request->getPost('nama'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => (int) $this->request->getPost('stok'),
            'berat'       => (int) ($this->request->getPost('berat') ?: 1000),
            'kategori_id' => $this->request->getPost('kategori_id') ?: null,
            'status'      => $this->request->getPost('status'),
        ];

        $img = upload_image('gambar', 'produk');
        if ($img) {
            $data['gambar'] = $img;
        }

        model(ProdukModel::class)->update($id, $data);

        return redirect()->to('/admin/produk')->with('success', 'Produk diperbarui.');
    }

    public function delete(int $id)
    {
        model(ProdukModel::class)->delete($id);

        return redirect()->to('/admin/produk')->with('success', 'Produk dihapus.');
    }
}
