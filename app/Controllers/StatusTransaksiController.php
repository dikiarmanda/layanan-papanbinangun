<?php

namespace App\Controllers;

use App\Libraries\MidtransService;
use App\Models\JadwalPaketWisataModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaketWisataModel;
use App\Models\PelangganModel;
use App\Models\ProdukModel;
use App\Models\ReservasiModel;

class StatusTransaksiController extends BaseController
{
    public function show(string $kode)
    {
        helper('layanan');
        $kode = strtoupper(trim($kode));

        if (str_starts_with($kode, 'RSV-')) {
            return $this->showReservasi($kode);
        }

        if (str_starts_with($kode, 'ORD-')) {
            return $this->showOrder($kode);
        }

        return view('status/not_found', [
            'title' => 'Transaksi Tidak Ditemukan',
            'kode'  => $kode,
        ]);
    }

    protected function showReservasi(string $kode)
    {
        $reservasi = model(ReservasiModel::class)->findByKode($kode);
        if (! $reservasi) {
            return view('status/not_found', ['title' => 'Tidak Ditemukan', 'kode' => $kode]);
        }

        if ($reservasi['status_pembayaran'] === 'pending' && !empty($reservasi['midtrans_order_id'])) {
            $reservasi = $this->syncReservasiStatus($reservasi);
        }

        $paket     = model(PaketWisataModel::class)->find($reservasi['paket_wisata_id']);
        $pelanggan = model(PelangganModel::class)->find($reservasi['pelanggan_id']);
        $jadwal    = $reservasi['jadwal_id']
            ? model(JadwalPaketWisataModel::class)->find($reservasi['jadwal_id'])
            : null;

        return view('status/reservasi', [
            'title'     => 'Status Reservasi',
            'reservasi' => $reservasi,
            'paket'     => $paket,
            'pelanggan' => $pelanggan,
            'jadwal'    => $jadwal,
        ]);
    }

    protected function showOrder(string $kode)
    {
        $order = model(OrderModel::class)->findByKode($kode);
        if (! $order) {
            return view('status/not_found', ['title' => 'Tidak Ditemukan', 'kode' => $kode]);
        }

        if ($order['status_pembayaran'] === 'pending' && !empty($order['midtrans_order_id'])) {
            $order = $this->syncOrderStatus($order);
        }

        $items     = model(OrderItemModel::class)->forOrder((int) $order['id']);
        $pelanggan = model(PelangganModel::class)->find($order['pelanggan_id']);

        return view('status/order', [
            'title'     => 'Status Pesanan',
            'order'     => $order,
            'items'     => $items,
            'pelanggan' => $pelanggan,
        ]);
    }

    protected function syncOrderStatus(array $order): array
    {
        $midtrans = new MidtransService();
        if (!$midtrans->isConfigured()) {
            return $order;
        }

        try {
            $status = $midtrans->getStatus($order['midtrans_order_id']);
        } catch (\Throwable $e) {
            log_message('warning', 'Midtrans status check failed for order {id}: {msg}', [
                'id' => $order['midtrans_order_id'],
                'msg' => $e->getMessage(),
            ]);

            return $order;
        }

        $trxStatus = (string) ($status->transaction_status ?? '');
        $fraudStatus = (string) ($status->fraud_status ?? '');
        $trxId = (string) ($status->transaction_id ?? '');

        $isPaid = in_array($trxStatus, ['capture', 'settlement'], true)
            && ($trxStatus !== 'capture' || $fraudStatus === '' || $fraudStatus === 'accept');
        $isFailed = in_array($trxStatus, ['deny', 'cancel'], true);
        $isExpire = $trxStatus === 'expire';

        $model = model(OrderModel::class);

        if ($isPaid) {
            $model->update($order['id'], [
                'status_pembayaran' => 'paid',
                'status_order' => 'diproses',
                'midtrans_transaction_id' => $trxId,
                'paid_at' => date('Y-m-d H:i:s'),
            ]);

            return $model->find($order['id']);
        }

        if ($isFailed || $isExpire) {
            $model->update($order['id'], [
                'status_pembayaran' => $isExpire ? 'expired' : 'failed',
                'status_order' => 'dibatalkan',
            ]);

            if ((int) $order['stok_locked'] === 1) {
                $items = model(OrderItemModel::class)->forOrder((int) $order['id']);
                $produkModel = model(ProdukModel::class);
                foreach ($items as $item) {
                    $produkModel->releaseStok((int) $item['produk_id'], (int) $item['jumlah']);
                }
                $model->update($order['id'], ['stok_locked' => 0]);
            }

            return $model->find($order['id']);
        }

        return $order;
    }

    protected function syncReservasiStatus(array $reservasi): array
    {
        $midtrans = new MidtransService();
        if (!$midtrans->isConfigured()) {
            return $reservasi;
        }

        try {
            $status = $midtrans->getStatus($reservasi['midtrans_order_id']);
        } catch (\Throwable $e) {
            log_message('warning', 'Midtrans status check failed for reservasi {id}: {msg}', [
                'id' => $reservasi['midtrans_order_id'],
                'msg' => $e->getMessage(),
            ]);

            return $reservasi;
        }

        $trxStatus = (string) ($status->transaction_status ?? '');
        $fraudStatus = (string) ($status->fraud_status ?? '');
        $trxId = (string) ($status->transaction_id ?? '');

        $isPaid = in_array($trxStatus, ['capture', 'settlement'], true)
            && ($trxStatus !== 'capture' || $fraudStatus === '' || $fraudStatus === 'accept');
        $isFailed = in_array($trxStatus, ['deny', 'cancel'], true);
        $isExpire = $trxStatus === 'expire';

        $model = model(ReservasiModel::class);

        if ($isPaid) {
            $model->update($reservasi['id'], [
                'status_pembayaran' => 'paid',
                'status_reservasi' => 'dikonfirmasi',
                'midtrans_transaction_id' => $trxId,
                'paid_at' => date('Y-m-d H:i:s'),
            ]);

            return $model->find($reservasi['id']);
        }

        if ($isFailed || $isExpire) {
            $model->update($reservasi['id'], [
                'status_pembayaran' => $isExpire ? 'expired' : 'failed',
                'status_reservasi' => 'dibatalkan',
            ]);

            if ((int) $reservasi['kuota_locked'] === 1) {
                $jadwalModel = model(JadwalPaketWisataModel::class);
                if (!empty($reservasi['check_in']) && !empty($reservasi['check_out'])) {
                    $jadwalModel->releaseRange(
                        (int) $reservasi['paket_wisata_id'],
                        (string) $reservasi['check_in'],
                        (string) $reservasi['check_out']
                    );
                } elseif (!empty($reservasi['jadwal_id'])) {
                    $jadwalModel->releaseKuota(
                        (int) $reservasi['jadwal_id'],
                        (int) $reservasi['jumlah_tamu']
                    );
                }
                $model->update($reservasi['id'], ['kuota_locked' => 0]);
            }

            return $model->find($reservasi['id']);
        }

        return $reservasi;
    }
}
