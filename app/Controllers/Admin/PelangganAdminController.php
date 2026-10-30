<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\PelangganModel;
use App\Models\ReservasiModel;

class PelangganAdminController extends BaseController
{
    public function index()
    {
        helper('layanan');
        $q = trim((string) $this->request->getGet('q'));

        $builder = model(PelangganModel::class)->builder();
        if ($q !== '') {
            $builder->groupStart()
                ->like('nama', $q)
                ->orLike('email', $q)
                ->orLike('no_hp', $q)
                ->groupEnd();
        }

        $pelanggan = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();

        $db = \Config\Database::connect();
        foreach ($pelanggan as &$p) {
            $p['total_order'] = $db->table('order')->where('pelanggan_id', $p['id'])->countAllResults();
            $p['total_reservasi'] = $db->table('reservasi')->where('pelanggan_id', $p['id'])->countAllResults();
        }
        unset($p);

        return view('admin/pelanggan/index', [
            'title'     => 'Data Pelanggan',
            'pelanggan' => $pelanggan,
            'q'         => $q,
        ]);
    }

    public function show(int $id)
    {
        helper('layanan');
        $pelanggan = model(PelangganModel::class)->find($id);
        if (!$pelanggan) {
            return redirect()->to('/admin/pelanggan')->with('error', 'Pelanggan tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $orders = $db->table('order')
            ->where('pelanggan_id', $id)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        $reservasi = $db->table('reservasi')
            ->select('reservasi.*, paket_wisata.nama as nama_paket')
            ->join('paket_wisata', 'paket_wisata.id = reservasi.paket_wisata_id', 'left')
            ->where('reservasi.pelanggan_id', $id)
            ->orderBy('reservasi.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/pelanggan/show', [
            'title'     => 'Detail Pelanggan — ' . $pelanggan['nama'],
            'pelanggan' => $pelanggan,
            'orders'    => $orders,
            'reservasi' => $reservasi,
        ]);
    }
}
