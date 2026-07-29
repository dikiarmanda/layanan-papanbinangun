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

        $reservasiBaru = model(ReservasiModel::class)->withDetails(6);
        $orderBaru = model(OrderModel::class)->withDetails(6);

        $aktivitas = [];
        foreach ($reservasiBaru as $r) {
            $aktivitas[] = [
                'judul'     => $r['kode_reservasi'],
                'deskripsi' => 'Reservasi ' . ($r['paket_nama'] ?? '') . ' · ' . ($r['status_pembayaran'] ?? ''),
                'waktu'     => date('d M Y · H:i', strtotime((string) $r['created_at'])),
                'url'       => site_url('admin/reservasi/' . $r['id']),
                'sort'      => strtotime((string) $r['created_at']),
            ];
        }
        foreach ($orderBaru as $o) {
            $aktivitas[] = [
                'judul'     => $o['kode_order'],
                'deskripsi' => 'Order ' . ($o['pelanggan_nama'] ?? '') . ' · ' . ($o['status_order'] ?? ''),
                'waktu'     => date('d M Y · H:i', strtotime((string) $o['created_at'])),
                'url'       => site_url('admin/order/' . $o['id']),
                'sort'      => strtotime((string) $o['created_at']),
            ];
        }

        usort($aktivitas, static fn ($a, $b) => ($b['sort'] ?? 0) <=> ($a['sort'] ?? 0));
        $aktivitas = array_slice($aktivitas, 0, 10);

        return view('admin/dashboard', [
            'title'         => 'Dashboard',
            'stats'         => $stats,
            'reservasiBaru' => $reservasiBaru,
            'orderBaru'     => $orderBaru,
            'aktivitas'     => $aktivitas,
        ]);
    }
}
