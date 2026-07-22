<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ZonaAntarLokalModel;

class ZonaAntarAdminController extends BaseController
{
    public function index()
    {
        helper('layanan');

        return view('admin/zona-antar/index', [
            'title' => 'Zona Antar Lokal (Catering)',
            'zona' => model(ZonaAntarLokalModel::class)->orderBy('ongkir', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('admin/zona-antar/form', [
            'title' => 'Tambah Zona Antar',
            'zona' => null,
        ]);
    }

    public function store()
    {
        model(ZonaAntarLokalModel::class)->insert([
            'nama' => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'ongkir' => (float) $this->request->getPost('ongkir'),
            'estimasi' => $this->request->getPost('estimasi') ?: '1-2 jam',
            'status' => $this->request->getPost('status') ?: 'aktif',
        ]);

        return redirect()->to('/admin/zona-antar')->with('success', 'Zona ditambahkan.');
    }

    public function edit(int $id)
    {
        $zona = model(ZonaAntarLokalModel::class)->find($id);
        if (!$zona) {
            return redirect()->to('/admin/zona-antar');
        }

        return view('admin/zona-antar/form', [
            'title' => 'Edit Zona Antar',
            'zona' => $zona,
        ]);
    }

    public function update(int $id)
    {
        $zona = model(ZonaAntarLokalModel::class)->find($id);
        if (!$zona) {
            return redirect()->to('/admin/zona-antar');
        }

        model(ZonaAntarLokalModel::class)->update($id, [
            'nama' => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'ongkir' => (float) $this->request->getPost('ongkir'),
            'estimasi' => $this->request->getPost('estimasi') ?: '1-2 jam',
            'status' => $this->request->getPost('status') ?: 'aktif',
        ]);

        return redirect()->to('/admin/zona-antar')->with('success', 'Zona diperbarui.');
    }

    public function delete(int $id)
    {
        model(ZonaAntarLokalModel::class)->delete($id);

        return redirect()->to('/admin/zona-antar')->with('success', 'Zona dihapus.');
    }
}
