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
        $paket = $paketModel->findPublished(8);
        $produk = $produkModel->findPublished(8);

        $heroSlides = [];
        foreach ($paket as $p) {
            if (empty($p['gambar_cover'])) {
                continue;
            }
            $jenis = ($p['jenis'] ?? '') === 'homestay' ? 'homestay' : 'wisata';
            $heroSlides[] = [
                'jenis' => $jenis,
                'nama' => $p['nama'],
                'harga' => (float) $p['harga'],
                'satuan' => $jenis === 'homestay' ? 'malam' : 'orang',
                'url' => site_url('paket-wisata/' . $p['slug']),
                'img' => media_url($p['gambar_cover']),
            ];
        }
        foreach ($produk as $pr) {
            if (empty($pr['gambar'])) {
                continue;
            }
            $jenis = ($pr['jenis'] ?? '') === 'catering' ? 'catering' : 'umkm';
            $heroSlides[] = [
                'jenis' => $jenis,
                'nama' => $pr['nama'],
                'harga' => (float) $pr['harga'],
                'satuan' => null,
                'url' => site_url('toko/' . $pr['slug']),
                'img' => media_url($pr['gambar']),
            ];
        }

        return view('home/index', [
            'title' => 'Layanan Desa Wisata',
            'paket' => array_slice($paket, 0, 6),
            'produk' => array_slice($produk, 0, 6),
            'heroSlides' => $heroSlides,
        ]);
    }
}
