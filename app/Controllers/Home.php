<?php

namespace App\Controllers;

use App\Models\JadwalPaketWisataModel;
use App\Models\PaketWisataModel;
use App\Models\ProdukModel;

class Home extends BaseController
{
    public function index()
    {
        helper('layanan');

        $paketModel = model(PaketWisataModel::class);
        $produkModel = model(ProdukModel::class);

        $paketAll  = fitur_reservasi_aktif() ? $paketModel->findPublished(20) : [];
        $produkAll = fitur_produk_aktif() ? $produkModel->findPublished(20) : [];

        $wisata = array_values(array_filter($paketAll, static fn ($p) => ($p['jenis'] ?? '') === 'wisata'));
        $menginap = array_values(array_filter(
            $paketAll,
            static fn ($p) => in_array($p['jenis'] ?? '', ['homestay', 'camping'], true)
        ));

        $heroSlides = [];
        $i = 0;
        foreach ($paketAll as $p) {
            $jenis = $p['jenis'] ?? 'wisata';
            if (! in_array($jenis, ['wisata', 'homestay', 'camping'], true)) {
                $jenis = 'wisata';
            }
            $group = in_array($jenis, ['homestay', 'camping'], true) ? 'menginap' : $jenis;
            $heroSlides[] = [
                'jenis'  => $jenis,
                'group'  => $group,
                'nama'   => $p['nama'],
                'harga'  => (float) $p['harga'],
                'satuan' => satuan_label($jenis, $p['satuan_harga'] ?? null),
                'url'    => site_url('paket-wisata/' . $p['slug']),
                'img'    => cover_url($p['gambar_cover'] ?? null, $jenis, $i++),
            ];
        }
        if (fitur_produk_aktif()) {
            foreach ($produkAll as $pr) {
                $jenis = ($pr['jenis'] ?? 'umkm') === 'catering' ? 'catering' : 'umkm';
                $heroSlides[] = [
                    'jenis'  => $jenis,
                    'group'  => $jenis,
                    'nama'   => $pr['nama'],
                    'harga'  => (float) $pr['harga'],
                    'satuan' => $jenis === 'catering' ? 'paket' : null,
                    'url'    => site_url('toko/' . $pr['slug']),
                    'img'    => cover_url($pr['gambar'] ?? null, $jenis, $i++),
                ];
            }
        }

        $heroBackgrounds = [];
        if (fitur_reservasi_aktif()) {
            $heroBackgrounds['wisata']   = unsplash_by_jenis('wisata', 0);
            $heroBackgrounds['menginap'] = unsplash_by_jenis('homestay', 0);
        }
        if (fitur_produk_aktif()) {
            $heroBackgrounds['umkm']     = unsplash_by_jenis('umkm', 0);
            $heroBackgrounds['catering'] = unsplash_by_jenis('catering', 0);
        }

        $estimateWisata = array_map(static fn ($p) => [
            'id'     => (int) $p['id'],
            'nama'   => $p['nama'],
            'harga'  => (float) $p['harga'],
            'slug'   => $p['slug'],
            'satuan' => 'per_orang',
            'label'  => 'per orang',
        ], $wisata);

        $estimateMenginap = array_map(static function ($p) {
            $jenis = $p['jenis'] ?? 'homestay';

            return [
                'id'     => (int) $p['id'],
                'nama'   => $p['nama'] . ($jenis === 'camping' ? ' (Camping)' : ' (Homestay)'),
                'harga'  => (float) $p['harga'],
                'slug'   => $p['slug'],
                'satuan' => 'per_rumah',
                'label'  => 'per rumah / malam',
            ];
        }, $menginap);

        $featured = array_slice($paketAll, 0, 6);
        $jadwalModel = model(JadwalPaketWisataModel::class);
        $ketersediaan = [];
        foreach ($featured as $p) {
            $ketersediaan[(int) $p['id']] = $jadwalModel->nextAvailability((int) $p['id']);
        }

        return view('home/index', [
            'title'            => 'Layanan Desa Wisata',
            'paket'            => $featured,
            'produk'           => array_slice($produkAll, 0, 6),
            'heroSlides'       => $heroSlides,
            'heroBackgrounds'  => $heroBackgrounds,
            'estimateWisata'   => $estimateWisata,
            'estimateMenginap' => $estimateMenginap,
            'ketersediaan'     => $ketersediaan,
        ]);
    }
}
