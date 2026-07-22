<?php

namespace App\Controllers;

use App\Libraries\MidtransService;
use App\Models\JadwalPaketWisataModel;
use App\Models\PaketWisataModel;
use App\Models\PelangganModel;
use App\Models\ReservasiModel;

class CheckoutReservasiController extends BaseController
{
    public function create()
    {
        helper('layanan');

        $paketId = (int) $this->request->getPost('paket_wisata_id');
        $paket = model(PaketWisataModel::class)->find($paketId);

        if (!$paket || $paket['status'] !== 'publish') {
            return redirect()->back()->withInput()->with('error', 'Paket tidak valid.');
        }

        $jenis = ($paket['jenis'] ?? 'wisata') === 'homestay' ? 'homestay' : 'wisata';

        if ($jenis === 'homestay') {
            return $this->createHomestay($paket);
        }

        return $this->createWisata($paket);
    }

    /**
     * @param array<string, mixed> $paket
     */
    protected function createWisata(array $paket): \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\ResponseInterface|string
    {
        $rules = [
            'paket_wisata_id' => 'required|is_natural_no_zero',
            'jadwal_id' => 'required|is_natural_no_zero',
            'jumlah_tamu' => 'required|is_natural_no_zero',
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'no_hp' => 'required|min_length[10]|max_length[20]',
            'catatan' => 'permit_empty|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $paketId = (int) $paket['id'];
        $jadwalId = (int) $this->request->getPost('jadwal_id');
        $jumlah = (int) $this->request->getPost('jumlah_tamu');
        $jadwal = model(JadwalPaketWisataModel::class)->find($jadwalId);

        if (!$jadwal || (int) $jadwal['paket_wisata_id'] !== $paketId) {
            return redirect()->back()->withInput()->with('error', 'Jadwal tidak valid.');
        }

        if (!model(JadwalPaketWisataModel::class)->lockKuota($jadwalId, $jumlah)) {
            return redirect()->back()->withInput()->with('error', 'Kuota tidak mencukupi untuk tanggal tersebut.');
        }

        $total = (float) $paket['harga'] * $jumlah;

        return $this->finishReservasi($paket, [
            'jadwal_id' => $jadwalId,
            'jumlah_tamu' => $jumlah,
            'check_in' => null,
            'check_out' => null,
            'jumlah_malam' => null,
            'total_harga' => $total,
        ]);
    }

    /**
     * @param array<string, mixed> $paket
     */
    protected function createHomestay(array $paket): \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\ResponseInterface|string
    {
        $rules = [
            'paket_wisata_id' => 'required|is_natural_no_zero',
            'check_in' => 'required|valid_date[Y-m-d]',
            'check_out' => 'required|valid_date[Y-m-d]',
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'no_hp' => 'required|min_length[10]|max_length[20]',
            'catatan' => 'permit_empty|max_length[1000]',
            'jumlah_tamu' => 'permit_empty|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $checkIn = (string) $this->request->getPost('check_in');
        $checkOut = (string) $this->request->getPost('check_out');
        $today = date('Y-m-d');

        if ($checkIn < $today) {
            return redirect()->back()->withInput()->with('error', 'Tanggal check-in tidak boleh di masa lalu.');
        }

        $jadwalModel = model(JadwalPaketWisataModel::class);
        $nights = $jadwalModel->nightsBetween($checkIn, $checkOut);

        if ($nights === []) {
            return redirect()->back()->withInput()->with('error', 'Check-out harus setelah check-in (minimal 1 malam).');
        }

        $avail = $jadwalModel->availableNights((int) $paket['id'], $checkIn, $checkOut);
        if (!$avail['ok']) {
            $msg = 'Homestay tidak tersedia untuk rentang tanggal tersebut.';
            if ($avail['missing'] !== []) {
                $msg .= ' Tanggal tanpa jadwal: ' . implode(', ', $avail['missing']) . '.';
            }
            if ($avail['full'] !== []) {
                $msg .= ' Tanggal penuh: ' . implode(', ', $avail['full']) . '.';
            }

            return redirect()->back()->withInput()->with('error', $msg);
        }

        if (!$jadwalModel->lockRange((int) $paket['id'], $checkIn, $checkOut)) {
            return redirect()->back()->withInput()->with('error', 'Ketersediaan berubah. Silakan pilih tanggal lain.');
        }

        $jumlahMalam = count($nights);
        $total = (float) $paket['harga'] * $jumlahMalam;
        $jumlahTamu = max(1, (int) ($this->request->getPost('jumlah_tamu') ?: 1));

        return $this->finishReservasi($paket, [
            'jadwal_id' => null,
            'jumlah_tamu' => $jumlahTamu,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'jumlah_malam' => $jumlahMalam,
            'total_harga' => $total,
        ]);
    }

    /**
     * @param array<string, mixed> $paket
     * @param array<string, mixed> $booking
     */
    protected function finishReservasi(array $paket, array $booking): string
    {
        $pelangganId = model(PelangganModel::class)->createGuest([
            'nama' => (string) $this->request->getPost('nama'),
            'email' => (string) $this->request->getPost('email'),
            'no_hp' => (string) $this->request->getPost('no_hp'),
        ]);

        $kode = generate_kode('RSV');
        $midtransOrderId = 'RSV-' . time() . '-' . bin2hex(random_bytes(4));
        $total = (float) $booking['total_harga'];

        $reservasiModel = model(ReservasiModel::class);
        $reservasiModel->insert([
            'kode_reservasi' => $kode,
            'paket_wisata_id' => (int) $paket['id'],
            'jadwal_id' => $booking['jadwal_id'],
            'pelanggan_id' => $pelangganId,
            'jumlah_tamu' => (int) $booking['jumlah_tamu'],
            'check_in' => $booking['check_in'],
            'check_out' => $booking['check_out'],
            'jumlah_malam' => $booking['jumlah_malam'],
            'catatan' => $this->request->getPost('catatan'),
            'total_harga' => $total,
            'status_pembayaran' => 'pending',
            'status_reservasi' => 'menunggu_pembayaran',
            'midtrans_order_id' => $midtransOrderId,
            'kuota_locked' => 1,
        ]);
        $reservasiId = (int) $reservasiModel->getInsertID();

        $midtrans = new MidtransService();
        if (!$midtrans->isConfigured()) {
            return view('checkout/snap', [
                'title' => 'Pembayaran Reservasi',
                'kode' => $kode,
                'snapToken' => null,
                'clientKey' => '',
                'isProduction' => false,
                'error' => 'Midtrans belum dikonfigurasi. Reservasi tersimpan dengan kode ' . $kode,
                'statusUrl' => site_url('status/' . $kode),
            ]);
        }

        $itemName = substr((string) $paket['nama'], 0, 50);
        if (!empty($booking['jumlah_malam'])) {
            $itemName = substr($paket['nama'] . ' (' . $booking['jumlah_malam'] . ' malam)', 0, 50);
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
                'item_details' => [
                    [
                        'id' => 'paket-' . $paket['id'],
                        'price' => (int) round($total),
                        'quantity' => 1,
                        'name' => $itemName,
                    ]
                ],
                'callbacks' => [
                    'finish' => site_url('status/' . $kode),
                ],
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Snap reservasi gagal: ' . $e->getMessage());

            return view('checkout/snap', [
                'title' => 'Pembayaran Reservasi',
                'kode' => $kode,
                'snapToken' => null,
                'clientKey' => $midtrans->getClientKey(),
                'isProduction' => false,
                'error' => 'Gagal membuat token pembayaran. Coba lagi atau cek status: ' . $kode,
                'statusUrl' => site_url('status/' . $kode),
            ]);
        }

        return view('checkout/snap', [
            'title' => 'Pembayaran Reservasi',
            'kode' => $kode,
            'snapToken' => $snapToken,
            'clientKey' => $midtrans->getClientKey(),
            'isProduction' => (bool) (config('Midtrans')->isProduction ?? false),
            'error' => null,
            'statusUrl' => site_url('status/' . $kode),
            'reservasiId' => $reservasiId,
        ]);
    }
}
