<?php

namespace App\Controllers;

use App\Libraries\MidtransService;
use App\Models\MidtransLogModel;
use App\Models\OrderModel;
use App\Models\ReservasiModel;

class PaymentController extends BaseController
{
    protected MidtransService $midtrans;

    public function __construct()
    {
        $this->midtrans = new MidtransService();
    }

    /**
     * Get payment status by order/reservation code
     */
    public function status(string $kode)
    {
        $tipe = str_starts_with($kode, 'ORD-') ? 'order' : 'reservasi';
        
        if ($tipe === 'reservasi') {
            $record = model(ReservasiModel::class)->where('kode_reservasi', $kode)->first();
        } else {
            $record = model(OrderModel::class)->where('kode_order', $kode)->first();
        }

        if (!$record) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Record not found']);
        }

        $midtransOrderId = $record['midtrans_order_id'] ?? null;
        $status = [
            'kode' => $kode,
            'tipe' => $tipe,
            'status_pembayaran' => $record['status_pembayaran'],
            'status' => $tipe === 'reservasi' ? $record['status_reservasi'] : $record['status_order'],
            'total_harga' => $record['total_harga'],
            'midtrans_order_id' => $midtransOrderId,
        ];

        // Try to get real-time status from Midtrans if configured
        if ($midtransOrderId && $this->midtrans->isConfigured()) {
            try {
                $trxStatus = $this->midtrans->getStatus($midtransOrderId);
                $status['midtrans_status'] = $trxStatus;
            } catch (\Throwable $e) {
                $status['midtrans_error'] = $e->getMessage();
            }
        }

        return $this->response->setJSON($status);
    }

    /**
     * Create Snap token for existing reservation/order
     */
    public function createToken()
    {
        $kode = $this->request->getPost('kode');
        if (!$kode) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Kode required']);
        }

        $tipe = str_starts_with($kode, 'ORD-') ? 'order' : 'reservasi';
        
        if ($tipe === 'reservasi') {
            $record = model(ReservasiModel::class)->where('kode_reservasi', $kode)->first();
        } else {
            $record = model(OrderModel::class)->where('kode_order', $kode)->first();
        }

        if (!$record) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Record not found']);
        }

        // Only allow re-creating token for pending payments
        if ($record['status_pembayaran'] !== 'pending') {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Payment already processed',
                'status' => $record['status_pembayaran']
            ]);
        }

        if (!$this->midtrans->isConfigured()) {
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Midtrans not configured']);
        }

        try {
            $snapToken = $this->midtrans->createSnapToken([
                'order_id' => $record['midtrans_order_id'],
                'gross_amount' => $record['total_harga'],
                'callbacks' => ['finish' => site_url('status/' . $kode)],
            ]);

            return $this->response->setJSON([
                'snap_token' => $snapToken,
                'client_key' => $this->midtrans->getClientKey(),
                'is_production' => config('Midtrans')->isProduction,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Create token failed: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Failed to create token',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get payment logs for debugging
     */
    public function logs(string $kode)
    {
        $tipe = str_starts_with($kode, 'ORD-') ? 'order' : 'reservasi';
        
        if ($tipe === 'reservasi') {
            $record = model(ReservasiModel::class)->where('kode_reservasi', $kode)->first();
            $dbId = $record['id'] ?? null;
        } else {
            $record = model(OrderModel::class)->where('kode_order', $kode)->first();
            $dbId = $record['id'] ?? null;
        }

        if (!$record) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Record not found']);
        }

        $logs = model(MidtransLogModel::class)
            ->where($tipe === 'reservasi' ? 'reservasi_id' : 'order_id', $dbId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'kode' => $kode,
            'tipe' => $tipe,
            'logs' => $logs,
        ]);
    }

    /**
     * Check if Midtrans is properly configured
     */
    public function checkConfig()
    {
        return $this->response->setJSON([
            'configured' => $this->midtrans->isConfigured(),
            'is_production' => config('Midtrans')->isProduction,
            'has_client_key' => !empty(config('Midtrans')->clientKey),
            'has_server_key' => !empty(config('Midtrans')->serverKey),
        ]);
    }
}
