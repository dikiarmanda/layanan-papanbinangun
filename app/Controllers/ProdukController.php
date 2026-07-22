<?php

namespace App\Controllers;

use App\Libraries\RajaOngkirService;
use App\Models\JadwalPaketWisataModel;
use App\Models\KategoriProdukModel;
use App\Models\ProdukModel;
use App\Models\ZonaAntarLokalModel;

class ProdukController extends BaseController
{
    public function index()
    {
        helper('layanan');
        $kategoriId = $this->request->getGet('kategori');
        $jenis = $this->request->getGet('jenis');
        if (!in_array($jenis, ['umkm', 'catering'], true)) {
            $jenis = null;
        }

        return view('toko/index', [
            'title' => $jenis === 'catering' ? 'Catering Desa' : 'Toko UMKM Desa',
            'produk' => model(ProdukModel::class)->findPublished(
                null,
                $kategoriId ? (int) $kategoriId : null,
                $jenis
            ),
            'kategori' => model(KategoriProdukModel::class)->findAll(),
            'activeKat' => $kategoriId,
            'activeJenis' => $jenis,
        ]);
    }

    public function show(string $slug)
    {
        helper('layanan');
        $produk = model(ProdukModel::class)->findBySlug($slug);

        if (!$produk || $produk['status'] !== 'publish') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('toko/show', [
            'title' => $produk['nama'],
            'produk' => $produk,
        ]);
    }

    public function keranjang()
    {
        helper('layanan');
        $cart = session()->get('cart') ?? [];
        $items = [];
        $subtotal = 0;
        $jenis = null;

        foreach ($cart as $produkId => $qty) {
            $produk = model(ProdukModel::class)->find($produkId);
            if (!$produk || $produk['status'] !== 'publish') {
                continue;
            }
            $pJenis = ($produk['jenis'] ?? 'umkm') === 'catering' ? 'catering' : 'umkm';
            if ($jenis === null) {
                $jenis = $pJenis;
            }
            $line = (float) $produk['harga'] * (int) $qty;
            $subtotal += $line;
            $items[] = [
                'produk' => $produk,
                'jumlah' => (int) $qty,
                'subtotal' => $line,
            ];
        }

        return view('toko/keranjang', [
            'title' => 'Keranjang',
            'items' => $items,
            'subtotal' => $subtotal,
            'cartJenis' => $jenis,
        ]);
    }

    public function addCart()
    {
        $produkId = (int) $this->request->getPost('produk_id');
        $jumlah = max(1, (int) $this->request->getPost('jumlah'));
        $produk = model(ProdukModel::class)->find($produkId);

        if (!$produk || $produk['status'] !== 'publish') {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        if ((int) $produk['stok'] < $jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        $newJenis = ($produk['jenis'] ?? 'umkm') === 'catering' ? 'catering' : 'umkm';
        $cart = session()->get('cart') ?? [];

        foreach ($cart as $existingId => $_) {
            $existing = model(ProdukModel::class)->find($existingId);
            if (!$existing) {
                continue;
            }
            $exJenis = ($existing['jenis'] ?? 'umkm') === 'catering' ? 'catering' : 'umkm';
            if ($exJenis !== $newJenis) {
                return redirect()->back()->with(
                    'error',
                    'Keranjang hanya boleh berisi satu jenis. Kosongkan keranjang terlebih dahulu sebelum menambah '
                    . ($newJenis === 'catering' ? 'catering' : 'produk UMKM') . '.'
                );
            }
            break;
        }

        $cart[$produkId] = ($cart[$produkId] ?? 0) + $jumlah;
        session()->set('cart', $cart);

        return redirect()->to('/keranjang')->with('success', 'Ditambahkan ke keranjang.');
    }

    public function updateCart()
    {
        $produkId = (int) $this->request->getPost('produk_id');
        $jumlah = (int) $this->request->getPost('jumlah');
        $cart = session()->get('cart') ?? [];

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
        $q = (string) $this->request->getGet('q');
        $svc = new RajaOngkirService();

        return $this->response->setJSON($svc->searchDestinations($q));
    }

    /**
     * GET /api/ongkir?destination=123&weight=1000
     */
    public function ongkir()
    {
        $destination = (int) $this->request->getGet('destination');
        $weight = max(1, (int) $this->request->getGet('weight'));
        $svc = new RajaOngkirService();

        return $this->response->setJSON($svc->getCost($destination, $weight));
    }

    /**
     * GET /api/zona-antar
     */
    public function zonaAntar()
    {
        $list = model(ZonaAntarLokalModel::class)->findAktif();
        $out = array_map(static function (array $z): array {
            return [
                'id' => (int) $z['id'],
                'nama' => $z['nama'],
                'deskripsi' => $z['deskripsi'],
                'ongkir' => (float) $z['ongkir'],
                'estimasi' => $z['estimasi'],
            ];
        }, $list);

        return $this->response->setJSON($out);
    }

    /**
     * GET /api/homestay-availability?paket_id=1&check_in=2026-08-01&check_out=2026-08-03
     */
    public function homestayAvailability()
    {
        $paketId = (int) $this->request->getGet('paket_id');
        $checkIn = (string) $this->request->getGet('check_in');
        $checkOut = (string) $this->request->getGet('check_out');

        if ($paketId < 1 || $checkIn === '' || $checkOut === '') {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false, 'message' => 'Parameter tidak lengkap']);
        }

        $avail = model(JadwalPaketWisataModel::class)->availableNights($paketId, $checkIn, $checkOut);

        return $this->response->setJSON([
            'ok' => $avail['ok'],
            'nights' => count($avail['nights']),
            'missing' => $avail['missing'],
            'full' => $avail['full'],
            'message' => $avail['ok']
                ? 'Tersedia untuk ' . count($avail['nights']) . ' malam'
                : 'Tidak tersedia untuk rentang tanggal tersebut',
        ]);
    }
}
