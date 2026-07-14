<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ReservasiModel;

class DashboardController extends BaseController
{
    public function index()
    {
        helper('layanan');

        $db = db_connect();

        $stats = [
            'reservasi_pending' => $db->table('reservasi')->where('status_pembayaran', 'pending')->countAllResults(),
            'reservasi_paid'    => $db->table('reservasi')->where('status_pembayaran', 'paid')->countAllResults(),
            'order_pending'     => $db->table('order')->where('status_pembayaran', 'pending')->countAllResults(),
            'order_paid'        => $db->table('order')->where('status_pembayaran', 'paid')->countAllResults(),
            'order_proses'      => $db->table('order')->where('status_order', 'diproses')->countAllResults(),
        ];

        return view('admin/dashboard', [
            'title'          => 'Dashboard',
            'stats'          => $stats,
            'reservasiBaru'  => model(ReservasiModel::class)->withDetails(8),
            'orderBaru'      => model(OrderModel::class)->withDetails(8),
        ]);
    }
}
