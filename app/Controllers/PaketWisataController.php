<?php

namespace App\Controllers;

use App\Models\JadwalPaketWisataModel;
use App\Models\PaketWisataModel;

class PaketWisataController extends BaseController
{
    public function index()
    {
        helper('layanan');
        $jenis = $this->request->getGet('jenis');
        if (!in_array($jenis, ['wisata', 'homestay'], true)) {
            $jenis = null;
        }

        $title = match ($jenis) {
            'homestay' => 'Homestay',
            'wisata' => 'Paket Wisata',
            default => 'Wisata & Homestay',
        };

        return view('paket-wisata/index', [
            'title' => $title,
            'paket' => model(PaketWisataModel::class)->findPublished(null, $jenis),
            'activeJenis' => $jenis,
        ]);
    }

    public function show(string $slug)
    {
        helper('layanan');
        $paket = model(PaketWisataModel::class)->findBySlug($slug);

        if (!$paket || $paket['status'] !== 'publish') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $jadwal = model(JadwalPaketWisataModel::class)->availableForPaket((int) $paket['id']);

        return view('paket-wisata/show', [
            'title' => $paket['nama'],
            'paket' => $paket,
            'jadwal' => $jadwal,
        ]);
    }
}
