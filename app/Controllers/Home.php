<?php

namespace App\Controllers;

use App\Models\PaketWisataModel;
use App\Models\ProdukModel;

class Home extends BaseController
{
    public function index()
    {
        helper('layanan');

        $paketModel = model(PaketWisataModel::class);
        $produkModel = model(ProdukModel::class);

        $paketAll = $paketModel->findPublished(20);
        $produkAll = $produkModel->findPublished(20);

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

        $heroBackgrounds = [
            'wisata'   => unsplash_by_jenis('wisata', 0),
            'menginap' => unsplash_by_jenis('homestay', 0),
            'umkm'     => unsplash_by_jenis('umkm', 0),
            'catering' => unsplash_by_jenis('catering', 0),
        ];

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

        return view('home/index', [
            'title'            => 'Layanan Desa Wisata',
            'paket'            => array_slice($paketAll, 0, 6),
            'produk'           => array_slice($produkAll, 0, 6),
            'heroSlides'       => $heroSlides,
            'heroBackgrounds'  => $heroBackgrounds,
            'estimateWisata'   => $estimateWisata,
            'estimateMenginap' => $estimateMenginap,
        ]);
    }
}
