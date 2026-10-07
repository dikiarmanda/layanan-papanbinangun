<?php

namespace App\Controllers;

use App\Models\JadwalPaketWisataModel;
use App\Models\PaketWisataModel;

class PaketWisataController extends BaseController
{
    public function index()
    {
        helper('layanan');
        assert_fitur_reservasi();
        $jenis = $this->request->getGet('jenis');
        if (! in_array($jenis, ['wisata', 'homestay', 'camping', 'menginap'], true)) {
            $jenis = null;
        }

        // homestay/camping lama → gabungan menginap
        if (in_array($jenis, ['homestay', 'camping'], true)) {
            $jenis = 'menginap';
        }

        $q = trim((string) $this->request->getGet('q'));
        $paket = model(PaketWisataModel::class)->findPublished(null, $jenis, $q !== '' ? $q : null);

        $title = match ($jenis) {
            'menginap' => 'Homestay & Camping',
            'wisata' => 'Paket Wisata',
            default => 'Wisata, Homestay & Camping',
        };

        return view('paket-wisata/index', [
            'title' => $title,
            'paket' => $paket,
            'activeJenis' => $jenis,
            'q' => $q,
            'ketersediaan' => $this->collectAvailability($paket),
        ]);
    }

    public function show(string $slug)
    {
        helper('layanan');
        assert_fitur_reservasi();
        $paket = model(PaketWisataModel::class)->findBySlug($slug);

        if (!$paket || $paket['status'] !== 'publish') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $paketId = (int) $paket['id'];
        $jadwalModel = model(JadwalPaketWisataModel::class);
        $jadwal = $jadwalModel->availableForPaket($paketId);

        $today = date('Y-m-d');
        $to = date('Y-m-d', strtotime('+62 days'));

        return view('paket-wisata/show', [
            'title' => $paket['nama'],
            'paket' => $paket,
            'jadwal' => $jadwal,
            'availabilityMap' => $jadwalModel->availabilityMap($paketId, $today, $to),
            'jadwalByDate' => $this->jadwalByDate($jadwal),
        ]);
    }

    /**
     * Peta tanggal => jadwal_id untuk interaksi kalender dengan form wisata.
     *
     * @param list<array> $jadwal
     *
     * @return array<string, int>
     */
    protected function jadwalByDate(array $jadwal): array
    {
        $map = [];
        foreach ($jadwal as $j) {
            $map[$j['tanggal']] = (int) $j['id'];
        }

        return $map;
    }

    /**
     * @param list<array> $paket
     *
     * @return array<int, array{total:int,available:int,remaining:?string}>
     */
    protected function collectAvailability(array $paket): array
    {
        $jadwalModel = model(JadwalPaketWisataModel::class);
        $result = [];
        foreach ($paket as $p) {
            $result[(int) $p['id']] = $jadwalModel->nextAvailability((int) $p['id']);
        }

        return $result;
    }
}
