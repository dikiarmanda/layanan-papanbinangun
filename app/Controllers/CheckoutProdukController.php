<?php

namespace App\Controllers;

use App\Libraries\MidtransService;
use App\Libraries\RajaOngkirService;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PelangganModel;
use App\Models\ProdukModel;

class CheckoutProdukController extends BaseController
{
    public function index()
    {
        helper('layanan');
        $cart = session()->get('cart') ?? [];

        if ($cart === []) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang kosong.');
        }

        $items    = [];
        $subtotal = 0;
        $weight   = 0;

        foreach ($cart as $produkId => $qty) {
            $produk = model(ProdukModel::class)->find($produkId);
            if (! $produk || $produk['status'] !== 'publish') {
                continue;
            }
            $line = (float) $produk['harga'] * (int) $qty;
            $subtotal += $line;
            $weight += ((int) ($produk['berat'] ?? 1000)) * (int) $qty;
            $items[] = ['produk' => $produk, 'jumlah' => (int) $qty, 'subtotal' => $line];
        }

        if ($items === []) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang kosong.');
        }

        $raja = new RajaOngkirService();

        return view('checkout/produk', [
            'title'       => 'Checkout Produk',
            'items'       => $items,
            'subtotal'    => $subtotal,
            'weight'      => $weight,
            'ongkirReady' => $raja->isConfigured(),
        ]);
    }

    public function process()
    {
        helper('layanan');

        $rules = [
            'nama'            => 'required|min_length[3]',
            'email'           => 'required|valid_email',
            'no_hp'           => 'required|min_length[10]',
            'alamat_kirim'    => 'required|min_length[10]',
            'kota_tujuan_id'  => 'required|is_natural_no_zero',
            'kota_tujuan_nama'=> 'required',
            'kurir'           => 'required',
            'layanan_kurir'   => 'required',
            'ongkos_kirim'    => 'required|numeric',
            'estimasi_ongkir' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $cart = session()->get('cart') ?? [];
        if ($cart === []) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang kosong.');
        }

        $produkModel = model(ProdukModel::class);
        $lineItems   = [];
        $subtotal    = 0;

        // Validasi & lock stok
        foreach ($cart as $produkId => $qty) {
            $produk = $produkModel->find($produkId);
            if (! $produk || $produk['status'] !== 'publish') {
                return redirect()->to('/keranjang')->with('error', 'Ada produk yang tidak tersedia.');
            }

            if (! $produkModel->lockStok((int) $produkId, (int) $qty)) {
                // Release yang sudah ter-lock di batch ini
                foreach ($lineItems as $li) {
                    $produkModel->releaseStok((int) $li['produk_id'], (int) $li['jumlah']);
                }

                return redirect()->to('/keranjang')->with('error', 'Stok "' . $produk['nama'] . '" tidak mencukupi.');
            }

            $line = (float) $produk['harga'] * (int) $qty;
            $subtotal += $line;
            $lineItems[] = [
                'produk_id'    => (int) $produkId,
                'nama_produk'  => $produk['nama'],
                'harga_satuan' => (float) $produk['harga'],
                'jumlah'       => (int) $qty,
                'subtotal'     => $line,
            ];
        }

        $ongkir = (float) $this->request->getPost('ongkos_kirim');
        $total  = $subtotal + $ongkir;

        $pelangganId = model(PelangganModel::class)->createGuest([
            'nama'  => (string) $this->request->getPost('nama'),
            'email' => (string) $this->request->getPost('email'),
            'no_hp' => (string) $this->request->getPost('no_hp'),
        ]);

        $kode            = generate_kode('ORD');
        $midtransOrderId = 'ORD-' . time() . '-' . bin2hex(random_bytes(4));

        $orderModel = model(OrderModel::class);
        $orderModel->insert([
            'kode_order'         => $kode,
            'pelanggan_id'       => $pelangganId,
            'alamat_kirim'       => $this->request->getPost('alamat_kirim'),
            'kota_tujuan_id'     => (int) $this->request->getPost('kota_tujuan_id'),
            'kota_tujuan_nama'   => $this->request->getPost('kota_tujuan_nama'),
            'kurir'              => $this->request->getPost('kurir'),
            'layanan_kurir'      => $this->request->getPost('layanan_kurir'),
            'estimasi_ongkir'    => $this->request->getPost('estimasi_ongkir'),
            'ongkos_kirim'       => $ongkir,
            'total_harga'        => $total,
            'status_pembayaran'  => 'pending',
            'status_order'       => 'menunggu_pembayaran',
            'midtrans_order_id'  => $midtransOrderId,
            'stok_locked'        => 1,
        ]);
        $orderId = (int) $orderModel->getInsertID();

        $itemModel = model(OrderItemModel::class);
        foreach ($lineItems as $li) {
            $li['order_id'] = $orderId;
            $itemModel->insert($li);
        }

        session()->remove('cart');

        $midtrans = new MidtransService();
        if (! $midtrans->isConfigured()) {
            return view('checkout/snap', [
                'title'        => 'Pembayaran Pesanan',
                'kode'         => $kode,
                'snapToken'    => null,
                'clientKey'    => '',
                'isProduction' => false,
                'error'        => 'Midtrans belum dikonfigurasi. Pesanan tersimpan: ' . $kode,
                'statusUrl'    => site_url('status/' . $kode),
            ]);
        }

        $snapItems = [];
        foreach ($lineItems as $li) {
            $snapItems[] = [
                'id'       => 'prd-' . $li['produk_id'],
                'price'    => (int) round($li['harga_satuan']),
                'quantity' => $li['jumlah'],
                'name'     => substr($li['nama_produk'], 0, 50),
            ];
        }
        if ($ongkir > 0) {
            $snapItems[] = [
                'id'       => 'ongkir',
                'price'    => (int) round($ongkir),
                'quantity' => 1,
                'name'     => 'Ongkir ' . strtoupper((string) $this->request->getPost('kurir')),
            ];
        }

        try {
            $snapToken = $midtrans->createSnapToken([
                'order_id'     => $midtransOrderId,
                'gross_amount' => $total,
                'customer_details' => [
                    'first_name' => (string) $this->request->getPost('nama'),
                    'email'      => (string) $this->request->getPost('email'),
                    'phone'      => (string) $this->request->getPost('no_hp'),
                ],
                'item_details' => $snapItems,
                'callbacks'    => ['finish' => site_url('status/' . $kode)],
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Snap order gagal: ' . $e->getMessage());

            return view('checkout/snap', [
                'title'        => 'Pembayaran Pesanan',
                'kode'         => $kode,
                'snapToken'    => null,
                'clientKey'    => $midtrans->getClientKey(),
                'isProduction' => false,
                'error'        => 'Gagal membuat token pembayaran. Kode: ' . $kode,
                'statusUrl'    => site_url('status/' . $kode),
            ]);
        }

        return view('checkout/snap', [
            'title'        => 'Pembayaran Pesanan',
            'kode'         => $kode,
            'snapToken'    => $snapToken,
            'clientKey'    => $midtrans->getClientKey(),
            'isProduction' => (bool) (config('Midtrans')->isProduction ?? false),
            'error'        => null,
            'statusUrl'    => site_url('status/' . $kode),
        ]);
    }
}
