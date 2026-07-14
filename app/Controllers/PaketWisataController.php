<?php

namespace App\Controllers;

use App\Models\JadwalPaketWisataModel;
use App\Models\PaketWisataModel;

class PaketWisataController extends BaseController
{
    public function index()
    {
        helper('layanan');

        return view('paket-wisata/index', [
            'title' => 'Paket Wisata & Homestay',
            'paket' => model(PaketWisataModel::class)->findPublished(),
        ]);
    }

    public function show(string $slug)
    {
        helper('layanan');
        $paket = model(PaketWisataModel::class)->findBySlug($slug);

        if (! $paket || $paket['status'] !== 'publish') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $jadwal = model(JadwalPaketWisataModel::class)->availableForPaket((int) $paket['id']);

        return view('paket-wisata/show', [
            'title'  => $paket['nama'],
            'paket'  => $paket,
            'jadwal' => $jadwal,
        ]);
    }
}
