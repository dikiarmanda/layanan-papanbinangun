<?php

namespace App\Controllers;

use App\Libraries\MidtransService;
use App\Libraries\RajaOngkirService;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PelangganModel;
use App\Models\ProdukModel;
use App\Models\ZonaAntarLokalModel;

class CheckoutProdukController extends BaseController
{
    public function index()
    {
        helper('layanan');
        $cart = session()->get('cart') ?? [];

        if ($cart === []) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang kosong.');
        }

        $built = $this->buildCartItems($cart);
        if ($built['items'] === []) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang kosong.');
        }

        $jenis = $built['jenis'];
        $raja = new RajaOngkirService();

        return view('checkout/produk', [
            'title' => $jenis === 'catering' ? 'Checkout Catering' : 'Checkout Produk UMKM',
            'items' => $built['items'],
            'subtotal' => $built['subtotal'],
            'weight' => $built['weight'],
            'cartJenis' => $jenis,
            'zona' => $jenis === 'catering' ? model(ZonaAntarLokalModel::class)->findAktif() : [],
            'ongkirReady' => $raja->isConfigured(),
        ]);
    }

    public function process()
    {
        helper('layanan');

        $cart = session()->get('cart') ?? [];
        if ($cart === []) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang kosong.');
        }

        $built = $this->buildCartItems($cart);
        if ($built['items'] === [] || $built['jenis'] === null) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang kosong atau tidak valid.');
        }

        if ($built['jenis'] === 'catering') {
            return $this->processCatering($built);
        }

        return $this->processUmkm($built);
    }

    /**
     * @param array{items:list<array>, subtotal:float, weight:int, jenis:?string, lineItems:list<array>} $built
     */
    protected function processUmkm(array $built): \CodeIgniter\HTTP\RedirectResponse|string
    {
        $rules = [
            'nama' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'no_hp' => 'required|min_length[10]',
            'alamat_kirim' => 'required|min_length[10]',
            'kota_tujuan_id' => 'required|is_natural_no_zero',
            'kota_tujuan_nama' => 'required',
            'kurir' => 'required',
            'layanan_kurir' => 'required',
            'ongkos_kirim' => 'required|numeric',
            'estimasi_ongkir' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $locked = $this->lockLineItems($built['lineItems']);
        if ($locked instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $locked;
        }

        $ongkir = (float) $this->request->getPost('ongkos_kirim');
        $total = $built['subtotal'] + $ongkir;

        return $this->finishOrder([
            'alamat_kirim' => $this->request->getPost('alamat_kirim'),
            'tanggal_acara' => null,
            'waktu_acara' => null,
            'metode_pengiriman' => 'ekspedisi',
            'zona_antar_id' => null,
            'kota_tujuan_id' => (int) $this->request->getPost('kota_tujuan_id'),
            'kota_tujuan_nama' => $this->request->getPost('kota_tujuan_nama'),
            'kurir' => $this->request->getPost('kurir'),
            'layanan_kurir' => $this->request->getPost('layanan_kurir'),
            'estimasi_ongkir' => $this->request->getPost('estimasi_ongkir'),
            'ongkos_kirim' => $ongkir,
            'total_harga' => $total,
        ], $locked, $ongkir);
    }

    /**
     * @param array{items:list<array>, subtotal:float, weight:int, jenis:?string, lineItems:list<array>} $built
     */
    protected function processCatering(array $built): \CodeIgniter\HTTP\RedirectResponse|string
    {
        $metode = (string) $this->request->getPost('metode_pengiriman');
        if (!in_array($metode, ['ambil_di_tempat', 'antar_lokal'], true)) {
            return redirect()->back()->withInput()->with('error', 'Pilih metode pengambilan catering.');
        }

        $rules = [
            'nama' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'no_hp' => 'required|min_length[10]',
            'alamat_kirim' => 'required|min_length[8]',
            'tanggal_acara' => 'required|valid_date[Y-m-d]',
            'waktu_acara' => 'required',
            'metode_pengiriman' => 'required',
        ];

        if ($metode === 'antar_lokal') {
            $rules['zona_antar_id'] = 'required|is_natural_no_zero';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $tanggalAcara = (string) $this->request->getPost('tanggal_acara');
        if ($tanggalAcara < date('Y-m-d')) {
            return redirect()->back()->withInput()->with('error', 'Tanggal acara tidak boleh di masa lalu.');
        }

        $ongkir = 0.0;
        $zonaId = null;
        $kurir = 'ambil';
        $layanan = 'Ambil di tempat';
        $estimasi = '-';
        $kotaNama = 'Ambil di lokasi';

        if ($metode === 'antar_lokal') {
            $zonaId = (int) $this->request->getPost('zona_antar_id');
            $zona = model(ZonaAntarLokalModel::class)->find($zonaId);
            if (!$zona || $zona['status'] !== 'aktif') {
                return redirect()->back()->withInput()->with('error', 'Zona antar tidak valid.');
            }
            $ongkir = (float) $zona['ongkir'];
            $kurir = 'lokal';
            $layanan = 'Antar lokal — ' . $zona['nama'];
            $estimasi = (string) $zona['estimasi'];
            $kotaNama = (string) $zona['nama'];
        }

        $locked = $this->lockLineItems($built['lineItems']);
        if ($locked instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $locked;
        }

        $total = $built['subtotal'] + $ongkir;

        return $this->finishOrder([
            'alamat_kirim' => $this->request->getPost('alamat_kirim'),
            'tanggal_acara' => $tanggalAcara,
            'waktu_acara' => $this->request->getPost('waktu_acara'),
            'metode_pengiriman' => $metode,
            'zona_antar_id' => $zonaId,
            'kota_tujuan_id' => null,
            'kota_tujuan_nama' => $kotaNama,
            'kurir' => $kurir,
            'layanan_kurir' => $layanan,
            'estimasi_ongkir' => $estimasi,
            'ongkos_kirim' => $ongkir,
            'total_harga' => $total,
        ], $locked, $ongkir);
    }

    /**
     * @param array<int, int> $cart
     * @return array{items:list<array>, subtotal:float, weight:int, jenis:?string, lineItems:list<array>}
     */
    protected function buildCartItems(array $cart): array
    {
        $items = [];
        $lineItems = [];
        $subtotal = 0.0;
        $weight = 0;
        $jenis = null;

        foreach ($cart as $produkId => $qty) {
            $produk = model(ProdukModel::class)->find($produkId);
            if (!$produk || $produk['status'] !== 'publish') {
                continue;
            }

            $pJenis = ($produk['jenis'] ?? 'umkm') === 'catering' ? 'catering' : 'umkm';
            if ($jenis === null) {
                $jenis = $pJenis;
            } elseif ($jenis !== $pJenis) {
                continue;
            }

            $line = (float) $produk['harga'] * (int) $qty;
            $subtotal += $line;
            $weight += ((int) ($produk['berat'] ?? 1000)) * (int) $qty;
            $items[] = ['produk' => $produk, 'jumlah' => (int) $qty, 'subtotal' => $line];
            $lineItems[] = [
                'produk_id' => (int) $produkId,
                'nama_produk' => $produk['nama'],
                'harga_satuan' => (float) $produk['harga'],
                'jumlah' => (int) $qty,
                'subtotal' => $line,
            ];
        }

        return compact('items', 'subtotal', 'weight', 'jenis', 'lineItems');
    }

    /**
     * @param list<array<string, mixed>> $lineItems
     * @return list<array<string, mixed>>|\CodeIgniter\HTTP\RedirectResponse
     */
    protected function lockLineItems(array $lineItems)
    {
        $produkModel = model(ProdukModel::class);
        $locked = [];

        foreach ($lineItems as $li) {
            $produk = $produkModel->find($li['produk_id']);
            if (!$produk || $produk['status'] !== 'publish') {
                foreach ($locked as $done) {
                    $produkModel->releaseStok((int) $done['produk_id'], (int) $done['jumlah']);
                }

                return redirect()->to('/keranjang')->with('error', 'Ada produk yang tidak tersedia.');
            }

            if (!$produkModel->lockStok((int) $li['produk_id'], (int) $li['jumlah'])) {
                foreach ($locked as $done) {
                    $produkModel->releaseStok((int) $done['produk_id'], (int) $done['jumlah']);
                }

                return redirect()->to('/keranjang')->with('error', 'Stok "' . $produk['nama'] . '" tidak mencukupi.');
            }

            $locked[] = $li;
        }

        return $locked;
    }

    /**
     * @param array<string, mixed> $orderData
     * @param list<array<string, mixed>> $lineItems
     */
    protected function finishOrder(array $orderData, array $lineItems, float $ongkir): string
    {
        $pelangganId = model(PelangganModel::class)->createGuest([
            'nama' => (string) $this->request->getPost('nama'),
            'email' => (string) $this->request->getPost('email'),
            'no_hp' => (string) $this->request->getPost('no_hp'),
        ]);

        $kode = generate_kode('ORD');
        $midtransOrderId = 'ORD-' . time() . '-' . bin2hex(random_bytes(4));
        $total = (float) $orderData['total_harga'];

        $orderModel = model(OrderModel::class);
        $orderModel->insert(array_merge($orderData, [
            'kode_order' => $kode,
            'pelanggan_id' => $pelangganId,
            'status_pembayaran' => 'pending',
            'status_order' => 'menunggu_pembayaran',
            'midtrans_order_id' => $midtransOrderId,
            'stok_locked' => 1,
        ]));
        $orderId = (int) $orderModel->getInsertID();

        $itemModel = model(OrderItemModel::class);
        foreach ($lineItems as $li) {
            $li['order_id'] = $orderId;
            $itemModel->insert($li);
        }

        session()->remove('cart');

        $midtrans = new MidtransService();
        if (!$midtrans->isConfigured()) {
            return view('checkout/snap', [
                'title' => 'Pembayaran Pesanan',
                'kode' => $kode,
                'snapToken' => null,
                'clientKey' => '',
                'isProduction' => false,
                'error' => 'Midtrans belum dikonfigurasi. Pesanan tersimpan: ' . $kode,
                'statusUrl' => site_url('status/' . $kode),
            ]);
        }

        $snapItems = [];
        foreach ($lineItems as $li) {
            $snapItems[] = [
                'id' => 'prd-' . $li['produk_id'],
                'price' => (int) round($li['harga_satuan']),
                'quantity' => $li['jumlah'],
                'name' => substr($li['nama_produk'], 0, 50),
            ];
        }
        if ($ongkir > 0) {
            $snapItems[] = [
                'id' => 'ongkir',
                'price' => (int) round($ongkir),
                'quantity' => 1,
                'name' => 'Ongkir ' . strtoupper((string) ($orderData['kurir'] ?? 'lokal')),
            ];
        }

        try {
            $snapToken = $midtrans->createSnapToken([
                'order_id' => $midtransOrderId,
                'gross_amount' => $total,
                'customer_details' => [
                    'first_name' => (string) $this->request->getPost('nama'),
                    'email' => (string) $this->request->getPost('email'),
                    'phone' => (string) $this->request->getPost('no_hp'),
                ],
                'item_details' => $snapItems,
                'callbacks' => ['finish' => site_url('status/' . $kode)],
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Snap order gagal: ' . $e->getMessage());

            return view('checkout/snap', [
                'title' => 'Pembayaran Pesanan',
                'kode' => $kode,
                'snapToken' => null,
                'clientKey' => $midtrans->getClientKey(),
                'isProduction' => false,
                'error' => 'Gagal membuat token pembayaran. Kode: ' . $kode,
                'statusUrl' => site_url('status/' . $kode),
            ]);
        }

        return view('checkout/snap', [
            'title' => 'Pembayaran Pesanan',
            'kode' => $kode,
            'snapToken' => $snapToken,
            'clientKey' => $midtrans->getClientKey(),
            'isProduction' => (bool) (config('Midtrans')->isProduction ?? false),
            'error' => null,
            'statusUrl' => site_url('status/' . $kode),
        ]);
    }
}
