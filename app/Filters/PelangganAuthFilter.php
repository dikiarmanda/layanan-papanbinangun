<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PelangganAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('pelanggan_id')) {
            session()->setTempdata('redirect_after_login', current_url(), 600);

            return redirect()->to('/masuk')->with('error', 'Silakan masuk untuk membuka akun Anda.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
