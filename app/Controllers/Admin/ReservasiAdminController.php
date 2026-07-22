<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\WhatsappService;
use App\Models\JadwalPaketWisataModel;
use App\Models\PelangganModel;
use App\Models\ReservasiModel;

class ReservasiAdminController extends BaseController
{
    public function index()
    {
        helper('layanan');

        return view('admin/reservasi/index', [
            'title' => 'Kelola Reservasi',
            'reservasi' => model(ReservasiModel::class)->withDetails(100),
        ]);
    }

    public function show(int $id)
    {
        helper('layanan');
        $row = model(ReservasiModel::class)
            ->select('reservasi.*, pelanggan.nama as pelanggan_nama, pelanggan.no_hp, paket_wisata.nama as paket_nama, paket_wisata.jenis as paket_jenis')
            ->join('pelanggan', 'pelanggan.id = reservasi.pelanggan_id')
            ->join('paket_wisata', 'paket_wisata.id = reservasi.paket_wisata_id')
            ->find($id);

        if (!$row) {
            return redirect()->to('/admin/reservasi');
        }

        return view('admin/reservasi/show', [
            'title' => 'Detail Reservasi',
            'reservasi' => $row,
        ]);
    }

    public function updateStatus(int $id)
    {
        $reservasi = model(ReservasiModel::class)->find($id);
        if (!$reservasi) {
            return redirect()->to('/admin/reservasi');
        }

        $status = (string) $this->request->getPost('status_reservasi');
        $allowed = ['dikonfirmasi', 'selesai', 'dibatalkan'];

        if (!in_array($status, $allowed, true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        model(ReservasiModel::class)->update($id, ['status_reservasi' => $status]);

        if ($status === 'dibatalkan' && (int) $reservasi['kuota_locked'] === 1) {
            $jadwalModel = model(JadwalPaketWisataModel::class);
            if (!empty($reservasi['check_in']) && !empty($reservasi['check_out'])) {
                $jadwalModel->releaseRange(
                    (int) $reservasi['paket_wisata_id'],
                    (string) $reservasi['check_in'],
                    (string) $reservasi['check_out']
                );
            } elseif (!empty($reservasi['jadwal_id'])) {
                $jadwalModel->releaseKuota(
                    (int) $reservasi['jadwal_id'],
                    (int) $reservasi['jumlah_tamu']
                );
            }
            model(ReservasiModel::class)->update($id, ['kuota_locked' => 0]);
        }

        $pelanggan = model(PelangganModel::class)->find($reservasi['pelanggan_id']);
        if ($pelanggan) {
            (new WhatsappService())->notifyReservasi(
                $pelanggan['no_hp'],
                "Update reservasi {$reservasi['kode_reservasi']}: status {$status}.",
                $id
            );
        }

        return redirect()->to('/admin/reservasi/' . $id)->with('success', 'Status diperbarui.');
    }
}
