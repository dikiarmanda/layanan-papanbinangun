<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\WhatsappService;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PelangganModel;
use App\Models\ProdukModel;

class OrderAdminController extends BaseController
{
    public function index()
    {
        helper('layanan');

        return view('admin/order/index', [
            'title' => 'Kelola Order',
            'orders'=> model(OrderModel::class)->withDetails(100),
        ]);
    }

    public function show(int $id)
    {
        helper('layanan');
        $order = model(OrderModel::class)
            ->select('`order`.*, pelanggan.nama as pelanggan_nama, pelanggan.no_hp')
            ->join('pelanggan', 'pelanggan.id = `order`.pelanggan_id')
            ->find($id);

        if (! $order) {
            return redirect()->to('/admin/order');
        }

        return view('admin/order/show', [
            'title' => 'Detail Order',
            'order' => $order,
            'items' => model(OrderItemModel::class)->forOrder($id),
        ]);
    }

    public function updateStatus(int $id)
    {
        $order = model(OrderModel::class)->find($id);
        if (! $order) {
            return redirect()->to('/admin/order');
        }

        $status = (string) $this->request->getPost('status_order');
        $allowed = ['diproses', 'dikirim', 'selesai', 'dibatalkan'];

        if (! in_array($status, $allowed, true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $data = ['status_order' => $status];
        if ($status === 'dikirim') {
            $data['no_resi'] = $this->request->getPost('no_resi');
        }

        model(OrderModel::class)->update($id, $data);

        if ($status === 'dibatalkan' && (int) $order['stok_locked'] === 1) {
            $items = model(OrderItemModel::class)->forOrder($id);
            foreach ($items as $item) {
                model(ProdukModel::class)->releaseStok((int) $item['produk_id'], (int) $item['jumlah']);
            }
            model(OrderModel::class)->update($id, ['stok_locked' => 0]);
        }

        $pelanggan = model(PelangganModel::class)->find($order['pelanggan_id']);
        if ($pelanggan) {
            $msg = "Update pesanan {$order['kode_order']}: status {$status}.";
            if (! empty($data['no_resi'])) {
                $msg .= ' Resi: ' . $data['no_resi'];
            }
            (new WhatsappService())->notifyOrder($pelanggan['no_hp'], $msg, $id);
        }

        return redirect()->to('/admin/order/' . $id)->with('success', 'Status diperbarui.');
    }
}
