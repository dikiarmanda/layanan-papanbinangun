<?php

namespace App\Controllers;

use App\Libraries\MidtransService;
use App\Libraries\WhatsappService;
use App\Models\JadwalPaketWisataModel;
use App\Models\MidtransLogModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PelangganModel;
use App\Models\ProdukModel;
use App\Models\ReservasiModel;

class MidtransWebhookController extends BaseController
{
    public function notify()
    {
        $payload = $this->request->getJSON(true);
        if (!is_array($payload)) {
            $raw = $this->request->getBody();
            $payload = json_decode($raw, true) ?? [];
        }

        if ($payload === []) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'empty payload']);
        }

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature = (string) ($payload['signature_key'] ?? '');
        $trxStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');
        $trxId = (string) ($payload['transaction_id'] ?? '');

        $midtrans = new MidtransService();
        $valid = $midtrans->verifySignature($orderId, $statusCode, $grossAmount, $signature);

        $tipe = str_starts_with($orderId, 'ORD-') ? 'order' : 'reservasi';
        $reservasi = null;
        $order = null;
        $reservasiId = null;
        $orderDbId = null;

        if ($tipe === 'reservasi') {
            $reservasi = model(ReservasiModel::class)->findByMidtransOrderId($orderId);
            $reservasiId = $reservasi['id'] ?? null;
        } else {
            $order = model(OrderModel::class)->findByMidtransOrderId($orderId);
            $orderDbId = $order['id'] ?? null;
        }

        model(MidtransLogModel::class)->insert([
            'tipe' => $tipe,
            'reservasi_id' => $reservasiId,
            'order_id' => $orderDbId,
            'midtrans_order_id' => $orderId,
            'transaction_status' => $trxStatus,
            'payload' => json_encode($payload),
            'signature_valid' => $valid ? 1 : 0,
        ]);

        if (!$valid) {
            return $this->response->setStatusCode(403)->setJSON(['message' => 'invalid signature']);
        }

        $paidStatuses = ['capture', 'settlement'];
        $failStatuses = ['deny', 'cancel'];
        $expireStatuses = ['expire'];

        $isPaid = in_array($trxStatus, $paidStatuses, true)
            && ($trxStatus !== 'capture' || $fraudStatus === '' || $fraudStatus === 'accept');
        $isFailed = in_array($trxStatus, $failStatuses, true);
        $isExpire = in_array($trxStatus, $expireStatuses, true);

        if ($tipe === 'reservasi' && $reservasi) {
            $this->handleReservasi($reservasi, $isPaid, $isFailed, $isExpire, $trxId);
        } elseif ($tipe === 'order' && $order) {
            $this->handleOrder($order, $isPaid, $isFailed, $isExpire, $trxId);
        }

        return $this->response->setJSON(['message' => 'ok']);
    }

    /**
     * @param array<string, mixed> $reservasi
     */
    protected function handleReservasi(array $reservasi, bool $isPaid, bool $isFailed, bool $isExpire, string $trxId): void
    {
        // Idempotensi: sudah final
        if (in_array($reservasi['status_pembayaran'], ['paid', 'failed', 'expired'], true)) {
            return;
        }

        $model = model(ReservasiModel::class);
        $pelanggan = model(PelangganModel::class)->find($reservasi['pelanggan_id']);
        $wa = new WhatsappService();

        if ($isPaid) {
            $model->update($reservasi['id'], [
                'status_pembayaran' => 'paid',
                'status_reservasi' => 'dikonfirmasi',
                'midtrans_transaction_id' => $trxId,
                'paid_at' => date('Y-m-d H:i:s'),
            ]);

            if ($pelanggan) {
                $wa->notifyReservasi(
                    $pelanggan['no_hp'],
                    "Reservasi {$reservasi['kode_reservasi']} dikonfirmasi. Pembayaran berhasil. Terima kasih!",
                    (int) $reservasi['id']
                );
            }

            return;
        }

        if ($isFailed || $isExpire) {
            $model->update($reservasi['id'], [
                'status_pembayaran' => $isExpire ? 'expired' : 'failed',
                'status_reservasi' => 'dibatalkan',
            ]);

            if ((int) $reservasi['kuota_locked'] === 1) {
                $this->releaseReservasiKuota($reservasi);
                $model->update($reservasi['id'], ['kuota_locked' => 0]);
            }

            if ($pelanggan) {
                $wa->notifyReservasi(
                    $pelanggan['no_hp'],
                    "Reservasi {$reservasi['kode_reservasi']} dibatalkan karena pembayaran gagal/kedaluwarsa.",
                    (int) $reservasi['id']
                );
            }
        }
    }

    /**
     * @param array<string, mixed> $order
     */
    protected function handleOrder(array $order, bool $isPaid, bool $isFailed, bool $isExpire, string $trxId): void
    {
        if (in_array($order['status_pembayaran'], ['paid', 'failed', 'expired'], true)) {
            return;
        }

        $model = model(OrderModel::class);
        $pelanggan = model(PelangganModel::class)->find($order['pelanggan_id']);
        $wa = new WhatsappService();

        if ($isPaid) {
            $model->update($order['id'], [
                'status_pembayaran' => 'paid',
                'status_order' => 'diproses',
                'midtrans_transaction_id' => $trxId,
                'paid_at' => date('Y-m-d H:i:s'),
            ]);

            if ($pelanggan) {
                $wa->notifyOrder(
                    $pelanggan['no_hp'],
                    "Pesanan {$order['kode_order']} berhasil dibayar dan sedang diproses.",
                    (int) $order['id']
                );
            }

            return;
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

            if ($pelanggan) {
                $wa->notifyOrder(
                    $pelanggan['no_hp'],
                    "Pesanan {$order['kode_order']} dibatalkan karena pembayaran gagal/kedaluwarsa.",
                    (int) $order['id']
                );
            }
        }
    }

    /**
     * @param array<string, mixed> $reservasi
     */
    protected function releaseReservasiKuota(array $reservasi): void
    {
        $jadwalModel = model(JadwalPaketWisataModel::class);

        if (!empty($reservasi['check_in']) && !empty($reservasi['check_out'])) {
            $jadwalModel->releaseRange(
                (int) $reservasi['paket_wisata_id'],
                (string) $reservasi['check_in'],
                (string) $reservasi['check_out']
            );

            return;
        }

        if (!empty($reservasi['jadwal_id'])) {
            $jadwalModel->releaseKuota(
                (int) $reservasi['jadwal_id'],
                (int) $reservasi['jumlah_tamu']
            );
        }
    }
}
