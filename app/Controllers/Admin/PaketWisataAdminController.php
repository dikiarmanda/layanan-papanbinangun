<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalPaketWisataModel;
use App\Models\PaketWisataModel;

class PaketWisataAdminController extends BaseController
{
    public function index()
    {
        helper('layanan');

        return view('admin/paket-wisata/index', [
            'title' => 'Kelola Paket Wisata',
            'paket' => model(PaketWisataModel::class)->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('admin/paket-wisata/form', [
            'title' => 'Tambah Paket',
            'paket' => null,
        ]);
    }

    public function store()
    {
        helper('layanan');
        $nama = (string) $this->request->getPost('nama');
        $slug = slugify($nama);
        $img  = upload_image('gambar_cover', 'paket');

        model(PaketWisataModel::class)->insert([
            'nama'          => $nama,
            'slug'          => $slug . '-' . substr(uniqid(), -4),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'harga'         => $this->request->getPost('harga'),
            'satuan_harga'  => $this->request->getPost('satuan_harga') ?: 'per_orang',
            'kuota_default' => $this->request->getPost('kuota_default') ?: null,
            'gambar_cover'  => $img,
            'status'        => $this->request->getPost('status') ?: 'draft',
            'admin_id'      => session()->get('admin_id'),
        ]);

        return redirect()->to('/admin/paket-wisata')->with('success', 'Paket ditambahkan.');
    }

    public function edit(int $id)
    {
        $paket = model(PaketWisataModel::class)->find($id);
        if (! $paket) {
            return redirect()->to('/admin/paket-wisata')->with('error', 'Tidak ditemukan.');
        }

        return view('admin/paket-wisata/form', [
            'title' => 'Edit Paket',
            'paket' => $paket,
        ]);
    }

    public function update(int $id)
    {
        helper('layanan');
        $paket = model(PaketWisataModel::class)->find($id);
        if (! $paket) {
            return redirect()->to('/admin/paket-wisata');
        }

        $data = [
            'nama'          => $this->request->getPost('nama'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'harga'         => $this->request->getPost('harga'),
            'satuan_harga'  => $this->request->getPost('satuan_harga'),
            'kuota_default' => $this->request->getPost('kuota_default') ?: null,
            'status'        => $this->request->getPost('status'),
        ];

        $img = upload_image('gambar_cover', 'paket');
        if ($img) {
            $data['gambar_cover'] = $img;
        }

        model(PaketWisataModel::class)->update($id, $data);

        return redirect()->to('/admin/paket-wisata')->with('success', 'Paket diperbarui.');
    }

    public function delete(int $id)
    {
        model(PaketWisataModel::class)->delete($id);

        return redirect()->to('/admin/paket-wisata')->with('success', 'Paket dihapus.');
    }

    public function jadwal(int $id)
    {
        helper('layanan');
        $paket = model(PaketWisataModel::class)->find($id);
        if (! $paket) {
            return redirect()->to('/admin/paket-wisata');
        }

        return view('admin/paket-wisata/jadwal', [
            'title'  => 'Jadwal: ' . $paket['nama'],
            'paket'  => $paket,
            'jadwal' => model(JadwalPaketWisataModel::class)
                ->where('paket_wisata_id', $id)
                ->orderBy('tanggal', 'ASC')
                ->findAll(),
        ]);
    }

    public function storeJadwal(int $id)
    {
        $paket = model(PaketWisataModel::class)->find($id);
        if (! $paket) {
            return redirect()->to('/admin/paket-wisata');
        }

        $kuota = (int) ($this->request->getPost('kuota') ?: $paket['kuota_default'] ?: 10);

        try {
            model(JadwalPaketWisataModel::class)->insert([
                'paket_wisata_id' => $id,
                'tanggal'         => $this->request->getPost('tanggal'),
                'kuota'           => $kuota,
                'kuota_terpakai'  => 0,
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Jadwal untuk tanggal itu sudah ada.');
        }

        return redirect()->to('/admin/paket-wisata/' . $id . '/jadwal')->with('success', 'Jadwal ditambahkan.');
    }

    public function deleteJadwal(int $jadwalId)
    {
        $jadwal = model(JadwalPaketWisataModel::class)->find($jadwalId);
        if ($jadwal) {
            model(JadwalPaketWisataModel::class)->delete($jadwalId);

            return redirect()->to('/admin/paket-wisata/' . $jadwal['paket_wisata_id'] . '/jadwal')
                ->with('success', 'Jadwal dihapus.');
        }

        return redirect()->to('/admin/paket-wisata');
    }
}
