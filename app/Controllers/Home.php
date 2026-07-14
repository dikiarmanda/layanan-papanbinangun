<?php

namespace App\Controllers;

use App\Models\PaketWisataModel;
use App\Models\ProdukModel;

class Home extends BaseController
{
    public function index()
    {
        helper('layanan');

        return view('home/index', [
            'title'  => 'Layanan Desa Wisata',
            'paket'  => model(PaketWisataModel::class)->findPublished(6),
            'produk' => model(ProdukModel::class)->findPublished(6),
        ]);
    }
}
