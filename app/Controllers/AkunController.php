<?php

namespace App\Controllers;

use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaketWisataModel;
use App\Models\PelangganModel;
use App\Models\ReservasiModel;

class AkunController extends BaseController
{
    public function index()
    {
        $pelangganId = (int) session()->get('pelanggan_id');

        $reservasi = model(ReservasiModel::class)
            ->select('reservasi.*, paket_wisata.nama as paket_nama, paket_wisata.jenis as paket_jenis')
            ->join('paket_wisata', 'paket_wisata.id = reservasi.paket_wisata_id')
            ->where('reservasi.pelanggan_id', $pelangganId)
            ->orderBy('reservasi.created_at', 'DESC')
            ->findAll(5);

        $orders = [];
        if (fitur_produk_aktif()) {
            $orders = model(OrderModel::class)
                ->where('pelanggan_id', $pelangganId)
                ->orderBy('created_at', 'DESC')
                ->findAll(5);
        }

        return view('akun/dashboard', [
            'title' => 'Akun Saya',
            'pelanggan' => $this->currentPelanggan(),
            'reservasi' => $reservasi,
            'orders' => $orders,
            'aktifMenu' => 'dashboard',
        ]);
    }

    public function reservasi()
    {
        $pelangganId = (int) session()->get('pelanggan_id');

        $list = model(ReservasiModel::class)
            ->select('reservasi.*, paket_wisata.nama as paket_nama, paket_wisata.jenis as paket_jenis, paket_wisata.slug as paket_slug')
            ->join('paket_wisata', 'paket_wisata.id = reservasi.paket_wisata_id')
            ->where('reservasi.pelanggan_id', $pelangganId)
            ->orderBy('reservasi.created_at', 'DESC')
            ->findAll();

        return view('akun/reservasi', [
            'title' => 'Reservasi Saya',
            'pelanggan' => $this->currentPelanggan(),
            'list' => $list,
            'aktifMenu' => 'reservasi',
        ]);
    }

    public function showReservasi(int $id)
    {
        $pelangganId = (int) session()->get('pelanggan_id');
        $row = model(ReservasiModel::class)->find($id);

        if (!$row || (int) $row['pelanggan_id'] !== $pelangganId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $paket = model(PaketWisataModel::class)->find((int) $row['paket_wisata_id']);

        return view('akun/reservasi_detail', [
            'title' => $row['kode_reservasi'],
            'pelanggan' => $this->currentPelanggan(),
            'reservasi' => $row,
            'paket' => $paket,
            'aktifMenu' => 'reservasi',
        ]);
    }

    public function order()
    {
        assert_fitur_produk();
        $pelangganId = (int) session()->get('pelanggan_id');

        $list = model(OrderModel::class)
            ->where('pelanggan_id', $pelangganId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('akun/order', [
            'title' => 'Pesanan Saya',
            'pelanggan' => $this->currentPelanggan(),
            'list' => $list,
            'aktifMenu' => 'order',
        ]);
    }

    public function showOrder(int $id)
    {
        assert_fitur_produk();
        $pelangganId = (int) session()->get('pelanggan_id');
        $row = model(OrderModel::class)->find($id);

        if (!$row || (int) $row['pelanggan_id'] !== $pelangganId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $items = model(OrderItemModel::class)->where('order_id', $id)->findAll();

        return view('akun/order_detail', [
            'title' => $row['kode_order'],
            'pelanggan' => $this->currentPelanggan(),
            'order' => $row,
            'items' => $items,
            'aktifMenu' => 'order',
        ]);
    }

    public function profil()
    {
        return view('akun/profil', [
            'title' => 'Profil',
            'pelanggan' => $this->currentPelanggan(),
            'aktifMenu' => 'profil',
        ]);
    }

    public function updateProfil()
    {
        $pelangganId = (int) session()->get('pelanggan_id');
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]',
            'no_hp' => 'required|min_length[10]|max_length[20]',
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $rules['password'] = 'min_length[6]';
            $rules['password_confirm'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $dup = model(PelangganModel::class)
            ->where('email', $email)
            ->where('id !=', $pelangganId)
            ->first();

        if ($dup && !empty($dup['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email sudah dipakai akun lain.');
        }

        $payload = [
            'nama' => trim((string) $this->request->getPost('nama')),
            'email' => $email,
            'no_hp' => trim((string) $this->request->getPost('no_hp')),
        ];

        if ($password !== '') {
            $payload['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        try {
            model(PelangganModel::class)->update($pelangganId, $payload);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal update profil pelanggan: {msg}', ['msg' => $e->getMessage()]);

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan profil.');
        }

        session()->set([
            'pelanggan_nama' => $payload['nama'],
            'pelanggan_email' => $payload['email'],
        ]);

        return redirect()->to('/akun/profil')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function currentPelanggan(): array
    {
        $id = (int) session()->get('pelanggan_id');
        $row = model(PelangganModel::class)->find($id);

        if (!$row) {
            session()->remove(['pelanggan_id', 'pelanggan_nama', 'pelanggan_email']);
            session()->setFlashdata('error', 'Sesi tidak valid. Silakan masuk lagi.');

            throw new \CodeIgniter\HTTP\Exceptions\RedirectException('/masuk');
        }

        unset($row['password']);

        return $row;
    }
}
