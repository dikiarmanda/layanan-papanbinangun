<?php

namespace App\Controllers;

class LegalController extends BaseController
{
    public function privasi()
    {
        helper('layanan');
        $site = pengaturan();

        return view('legal/privasi', [
            'title' => 'Kebijakan Privasi',
            'site' => $site,
        ]);
    }

    public function persyaratan()
    {
        helper('layanan');
        $site = pengaturan();

        return view('legal/persyaratan', [
            'title' => 'Syarat & Ketentuan',
            'site' => $site,
        ]);
    }
}
