<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MidtransLogModel;

class PembayaranAdminController extends BaseController
{
    public function index()
    {
        helper('layanan');
        $logs = model(MidtransLogModel::class)
            ->orderBy('created_at', 'DESC')
            ->findAll(100);

        return view('admin/pembayaran/index', [
            'title' => 'Log Midtrans',
            'logs'  => $logs,
        ]);
    }
}
