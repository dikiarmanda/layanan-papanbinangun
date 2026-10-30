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
        assert_fitur_produk();

        $kategoriId = $this->request->getGet('kategori');
        $jenis = $this->request->getGet('jenis');
        $q = trim((string) $this->request->getGet('q'));

        if (!in_array($jenis, ['umkm', 'catering'], true)) {
            $jenis = null;
        }

        $produk = model(ProdukModel::class)->findPublished(
            null,
            $kategoriId ? (int) $kategoriId : null,
            $jenis,
            $q !== '' ? $q : null
        );

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'count' => count($produk),
                'html' => view('toko/_grid', [
                    'produk' => $produk,
                    'q' => $q,
                    'activeJenis' => $jenis,
                    'activeKat' => $kategoriId,
                ]),
            ]);
        }

        return view('toko/index', [
            'title' => $jenis === 'catering' ? 'Catering Wisata Binangun' : 'Toko UMKM Wisata Binangun',
            'produk' => $produk,
            'kategori' => model(KategoriProdukModel::class)->findAll(),
            'activeKat' => $kategoriId,
            'activeJenis' => $jenis,
            'q' => $q,
        ]);
    }

    public function show(string $slug)
    {
        helper('layanan');
        assert_fitur_produk();

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
        assert_fitur_produk();

        $cart = session()->get('cart') ?? [];
        $items = [];
        $subtotal = 0;
        $jenis = null;

        foreach ($cart as $cartKey => $qty) {
            [$pid, $var] = array_pad(explode('__', (string) $cartKey, 2), 2, null);
            $pid = (int) $pid;
            $var = $var !== null ? urldecode($var) : null;

            $produk = model(ProdukModel::class)->find($pid);
            if (!$produk || $produk['status'] !== 'publish') {
                continue;
            }
            $pJenis = ($produk['jenis'] ?? 'umkm') === 'catering' ? 'catering' : 'umkm';
            if ($jenis === null) {
                $jenis = $pJenis;
            }

            $harga = harga_produk_akhir($produk['harga']);
            if (!empty($var) && !empty($produk['opsi_varian'])) {
                $vList = json_decode($produk['opsi_varian'], true) ?: [];
                foreach ($vList as $vl) {
                    if ($vl['nama'] === $var && !empty($vl['harga']) && (float) $vl['harga'] > 0) {
                        $harga = (float) $vl['harga'];
                        break;
                    }
                }
            }

            $line = $harga * (int) $qty;
            $subtotal += $line;
            $items[] = [
                'cart_key' => (string) $cartKey,
                'produk' => $produk,
                'varian' => $var,
                'harga_satuan' => $harga,
                'min_beli' => max(1, (int) ($produk['min_beli'] ?? 1)),
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
        helper('layanan');
        assert_fitur_produk();

        $produkId = (int) $this->request->getPost('produk_id');
        $varian = trim((string) $this->request->getPost('varian'));
        $produk = model(ProdukModel::class)->find($produkId);

        if (!$produk || $produk['status'] !== 'publish') {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $minBeli = max(1, (int) ($produk['min_beli'] ?? 1));
        $jumlah = max($minBeli, (int) $this->request->getPost('jumlah'));

        $stokTersedia = (int) $produk['stok'];
        if (!empty($varian) && !empty($produk['opsi_varian'])) {
            $vList = json_decode($produk['opsi_varian'], true) ?: [];
            foreach ($vList as $vl) {
                if ($vl['nama'] === $varian && isset($vl['stok'])) {
                    $stokTersedia = (int) $vl['stok'];
                    break;
                }
            }
        }

        if ($stokTersedia < $jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        $newJenis = ($produk['jenis'] ?? 'umkm') === 'catering' ? 'catering' : 'umkm';
        $cart = session()->get('cart') ?? [];

        foreach ($cart as $existingKey => $_) {
            [$exPid] = explode('__', (string) $existingKey, 2);
            $existing = model(ProdukModel::class)->find((int) $exPid);
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

        $cartKey = $varian !== '' ? ($produkId . '__' . urlencode($varian)) : (string) $produkId;
        $cart[$cartKey] = ($cart[$cartKey] ?? 0) + $jumlah;
        session()->set('cart', $cart);

        return redirect()->to('/keranjang')->with('success', 'Ditambahkan ke keranjang.');
    }

    public function updateCart()
    {
        helper('layanan');
        assert_fitur_produk();

        $cartKey = (string) ($this->request->getPost('cart_key') ?: $this->request->getPost('produk_id'));
        $jumlah = (int) $this->request->getPost('jumlah');
        $cart = session()->get('cart') ?? [];

        [$pid] = explode('__', $cartKey, 2);
        $produk = model(ProdukModel::class)->find((int) $pid);
        $minBeli = max(1, (int) ($produk['min_beli'] ?? 1));

        if ($jumlah <= 0) {
            unset($cart[$cartKey]);
            if (is_numeric($cartKey)) {
                unset($cart[(int) $cartKey]);
            }
        } else {
            if ($jumlah < $minBeli) {
                $jumlah = $minBeli;
            }
            $cart[$cartKey] = $jumlah;
        }

        session()->set('cart', $cart);

        if ($this->request->isAJAX()) {
            return $this->cartJsonResponse($cart);
        }

        return redirect()->to('/keranjang');
    }

    public function removeCart(string $cartKey)
    {
        helper('layanan');
        assert_fitur_produk();

        $cart = session()->get('cart') ?? [];
        unset($cart[$cartKey]);
        if (is_numeric($cartKey)) {
            unset($cart[(int) $cartKey]);
        }
        session()->set('cart', $cart);

        if ($this->request->isAJAX()) {
            return $this->cartJsonResponse($cart);
        }

        return redirect()->to('/keranjang');
    }

    private function cartJsonResponse(array $cart)
    {
        helper('layanan');
        $items = [];
        $subtotal = 0;

        foreach ($cart as $cartKey => $qty) {
            [$pid, $var] = array_pad(explode('__', (string) $cartKey, 2), 2, null);
            $pid = (int) $pid;
            $var = $var !== null ? urldecode($var) : null;

            $produk = model(ProdukModel::class)->find($pid);
            if (!$produk || $produk['status'] !== 'publish') {
                continue;
            }
            $harga = harga_produk_akhir($produk['harga']);
            if (!empty($var) && !empty($produk['opsi_varian'])) {
                $vList = json_decode($produk['opsi_varian'], true) ?: [];
                foreach ($vList as $vl) {
                    if ($vl['nama'] === $var && !empty($vl['harga']) && (float) $vl['harga'] > 0) {
                        $harga = (float) $vl['harga'];
                        break;
                    }
                }
            }

            $line = $harga * (int) $qty;
            $subtotal += $line;
            $items[] = [
                'cart_key' => (string) $cartKey,
                'produk_id' => (int) $pid,
                'varian' => $var,
                'min_beli' => max(1, (int) ($produk['min_beli'] ?? 1)),
                'jumlah' => (int) $qty,
                'harga' => $harga,
                'subtotal' => $line,
                'subtotal_fmt' => format_rupiah($line),
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'items' => $items,
            'subtotal' => $subtotal,
            'subtotal_fmt' => format_rupiah($subtotal),
            'count' => count($items),
            'csrf_token' => csrf_hash(),
            'csrf_hash' => csrf_hash(),
        ]);
    }

    public function provinces()
    {
        helper('layanan');
        assert_fitur_produk();

        return $this->response->setJSON([]);
    }

    public function cities()
    {
        helper('layanan');
        assert_fitur_produk();

        return $this->response->setJSON([]);
    }

    /**
     * GET /api/destinations?q=surabaya
     */
    public function destinations()
    {
        helper('layanan');
        assert_fitur_produk();

        $q = (string) $this->request->getGet('q');
        $svc = new RajaOngkirService();

        return $this->response->setJSON($svc->searchDestinations($q));
    }

    /**
     * GET /api/ongkir?destination=123&weight=1000
     */
    public function ongkir()
    {
        helper('layanan');
        assert_fitur_produk();

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
        helper('layanan');
        assert_fitur_produk();

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
