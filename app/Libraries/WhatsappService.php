<?php

namespace App\Libraries;

use App\Models\WhatsappLogModel;

/**
 * Stub: log saja, belum kirim ke provider WA.
 */
class WhatsappService
{
    protected WhatsappLogModel $logModel;

    public function __construct(?WhatsappLogModel $logModel = null)
    {
        $this->logModel = $logModel ?? model(WhatsappLogModel::class);
    }

    public function notifyReservasi(string $noHp, string $pesan, ?int $reservasiId = null): bool
    {
        return $this->log('reservasi', $noHp, $pesan, $reservasiId, null);
    }

    public function notifyOrder(string $noHp, string $pesan, ?int $orderId = null): bool
    {
        return $this->log('order', $noHp, $pesan, null, $orderId);
    }

    protected function log(string $tipe, string $noHp, string $pesan, ?int $reservasiId, ?int $orderId): bool
    {
        try {
            $this->logModel->insert([
                'tipe'         => $tipe,
                'reservasi_id' => $reservasiId,
                'order_id'     => $orderId,
                'no_hp_tujuan' => $noHp,
                'pesan'        => $pesan,
                'status_kirim' => 'terkirim', // stub dianggap "terkirim" ke log
            ]);

            return true;
        } catch (\Throwable $e) {
            log_message('error', 'Whatsapp stub log failed: ' . $e->getMessage());

            return false;
        }
    }
}
