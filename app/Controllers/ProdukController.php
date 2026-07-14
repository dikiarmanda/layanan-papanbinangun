<?php

namespace App\Controllers;

use App\Libraries\RajaOngkirService;
use App\Models\KategoriProdukModel;
use App\Models\ProdukModel;

class ProdukController extends BaseController
{
    public function index()
    {
        helper('layanan');
        $kategoriId = $this->request->getGet('kategori');

        return view('toko/index', [
            'title'     => 'Toko UMKM Desa',
            'produk'    => model(ProdukModel::class)->findPublished(null, $kategoriId ? (int) $kategoriId : null),
            'kategori'  => model(KategoriProdukModel::class)->findAll(),
            'activeKat' => $kategoriId,
        ]);
    }

    public function show(string $slug)
    {
        helper('layanan');
        $produk = model(ProdukModel::class)->findBySlug($slug);

        if (! $produk || $produk['status'] !== 'publish') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('toko/show', [
            'title'  => $produk['nama'],
            'produk' => $produk,
        ]);
    }

    public function keranjang()
    {
        helper('layanan');
        $cart    = session()->get('cart') ?? [];
        $items   = [];
        $subtotal = 0;

        foreach ($cart as $produkId => $qty) {
            $produk = model(ProdukModel::class)->find($produkId);
            if (! $produk || $produk['status'] !== 'publish') {
                continue;
            }
            $line = (float) $produk['harga'] * (int) $qty;
            $subtotal += $line;
            $items[] = [
                'produk'   => $produk,
                'jumlah'   => (int) $qty,
                'subtotal' => $line,
            ];
        }

        return view('toko/keranjang', [
            'title'    => 'Keranjang',
            'items'    => $items,
            'subtotal' => $subtotal,
        ]);
    }

    public function addCart()
    {
        $produkId = (int) $this->request->getPost('produk_id');
        $jumlah   = max(1, (int) $this->request->getPost('jumlah'));
        $produk   = model(ProdukModel::class)->find($produkId);

        if (! $produk || $produk['status'] !== 'publish') {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        if ((int) $produk['stok'] < $jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart = session()->get('cart') ?? [];
        $cart[$produkId] = ($cart[$produkId] ?? 0) + $jumlah;
        session()->set('cart', $cart);

        return redirect()->to('/keranjang')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function updateCart()
    {
        $produkId = (int) $this->request->getPost('produk_id');
        $jumlah   = (int) $this->request->getPost('jumlah');
        $cart     = session()->get('cart') ?? [];

        if ($jumlah <= 0) {
            unset($cart[$produkId]);
        } else {
            $cart[$produkId] = $jumlah;
        }

        session()->set('cart', $cart);

        return redirect()->to('/keranjang');
    }

    public function removeCart(int $produkId)
    {
        $cart = session()->get('cart') ?? [];
        unset($cart[$produkId]);
        session()->set('cart', $cart);

        return redirect()->to('/keranjang');
    }

    public function provinces()
    {
        // Legacy path — API baru memakai pencarian destinasi
        return $this->response->setJSON([]);
    }

    public function cities()
    {
        return $this->response->setJSON([]);
    }

    /**
     * GET /api/destinations?q=surabaya
     */
    public function destinations()
    {
        $q   = (string) $this->request->getGet('q');
        $svc = new RajaOngkirService();

        return $this->response->setJSON($svc->searchDestinations($q));
    }

    /**
     * GET /api/ongkir?destination=123&weight=1000
     */
    public function ongkir()
    {
        $destination = (int) $this->request->getGet('destination');
        $weight      = max(1, (int) $this->request->getGet('weight'));
        $svc         = new RajaOngkirService();

        return $this->response->setJSON($svc->getCost($destination, $weight));
    }
}