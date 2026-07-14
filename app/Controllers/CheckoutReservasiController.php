<?php

namespace App\Controllers;

use App\Libraries\MidtransService;
use App\Libraries\WhatsappService;
use App\Models\JadwalPaketWisataModel;
use App\Models\PaketWisataModel;
use App\Models\PelangganModel;
use App\Models\ReservasiModel;

class CheckoutReservasiController extends BaseController
{
    public function create()
    {
        helper('layanan');

        $rules = [
            'paket_wisata_id' => 'required|is_natural_no_zero',
            'jadwal_id'       => 'required|is_natural_no_zero',
            'jumlah_tamu'     => 'required|is_natural_no_zero',
            'nama'            => 'required|min_length[3]|max_length[100]',
            'email'           => 'required|valid_email',
            'no_hp'           => 'required|min_length[10]|max_length[20]',
            'catatan'         => 'permit_empty|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $paketId  = (int) $this->request->getPost('paket_wisata_id');
        $jadwalId = (int) $this->request->getPost('jadwal_id');
        $jumlah   = (int) $this->request->getPost('jumlah_tamu');

        $paket  = model(PaketWisataModel::class)->find($paketId);
        $jadwal = model(JadwalPaketWisataModel::class)->find($jadwalId);

        if (! $paket || $paket['status'] !== 'publish' || ! $jadwal || (int) $jadwal['paket_wisata_id'] !== $paketId) {
            return redirect()->back()->withInput()->with('error', 'Paket atau jadwal tidak valid.');
        }

        $jadwalModel = model(JadwalPaketWisataModel::class);
        if (! $jadwalModel->lockKuota($jadwalId, $jumlah)) {
            return redirect()->back()->withInput()->with('error', 'Kuota tidak mencukupi untuk tanggal tersebut.');
        }

        $hargaSatuan = (float) $paket['harga'];
        $total       = $paket['satuan_harga'] === 'per_orang'
            ? $hargaSatuan * $jumlah
            : $hargaSatuan;

        $pelangganId = model(PelangganModel::class)->createGuest([
            'nama'  => (string) $this->request->getPost('nama'),
            'email' => (string) $this->request->getPost('email'),
            'no_hp' => (string) $this->request->getPost('no_hp'),
        ]);

        $kode           = generate_kode('RSV');
        $midtransOrderId = 'RSV-' . time() . '-' . bin2hex(random_bytes(4));

        $reservasiModel = model(ReservasiModel::class);
        $reservasiModel->insert([
            'kode_reservasi'    => $kode,
            'paket_wisata_id'   => $paketId,
            'jadwal_id'         => $jadwalId,
            'pelanggan_id'      => $pelangganId,
            'jumlah_tamu'       => $jumlah,
            'catatan'           => $this->request->getPost('catatan'),
            'total_harga'       => $total,
            'status_pembayaran' => 'pending',
            'status_reservasi'  => 'menunggu_pembayaran',
            'midtrans_order_id' => $midtransOrderId,
            'kuota_locked'      => 1,
        ]);
        $reservasiId = (int) $reservasiModel->getInsertID();

        $midtrans = new MidtransService();
        if (! $midtrans->isConfigured()) {
            return view('checkout/snap', [
                'title'           => 'Pembayaran Reservasi',
                'kode'            => $kode,
                'snapToken'       => null,
                'clientKey'       => '',
                'isProduction'    => false,
                'error'           => 'Midtrans belum dikonfigurasi. Reservasi tersimpan dengan kode ' . $kode,
                'statusUrl'       => site_url('status/' . $kode),
            ]);
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
                'item_details' => [[
                    'id'       => 'paket-' . $paketId,
                    'price'    => (int) round($total),
                    'quantity' => 1,
                    'name'     => substr($paket['nama'], 0, 50),
                ]],
                'callbacks' => [
                    'finish' => site_url('status/' . $kode),
                ],
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Snap reservasi gagal: ' . $e->getMessage());

            return view('checkout/snap', [
                'title'        => 'Pembayaran Reservasi',
                'kode'         => $kode,
                'snapToken'    => null,
                'clientKey'    => $midtrans->getClientKey(),
                'isProduction' => false,
                'error'        => 'Gagal membuat token pembayaran. Coba lagi atau cek status: ' . $kode,
                'statusUrl'    => site_url('status/' . $kode),
            ]);
        }

        return view('checkout/snap', [
            'title'        => 'Pembayaran Reservasi',
            'kode'         => $kode,
            'snapToken'    => $snapToken,
            'clientKey'    => $midtrans->getClientKey(),
            'isProduction' => (bool) (config('Midtrans')->isProduction ?? false),
            'error'        => null,
            'statusUrl'    => site_url('status/' . $kode),
            'reservasiId'  => $reservasiId,
        ]);
    }
}
