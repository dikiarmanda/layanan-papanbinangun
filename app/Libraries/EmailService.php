<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class EmailService
{
    protected Email $email;

    public function __construct()
    {
        $this->email = service('email');
    }

    public function sendOrderReceipt(string $toEmail, string $toName, array $order, array $items): bool
    {
        helper('layanan');

        $subject = 'Bukti Pembayaran Pesanan ' . $order['kode_order'];
        $body = view('emails/order_receipt', [
            'order'    => $order,
            'items'    => $items,
            'nama'     => $toName,
        ]);

        return $this->send($toEmail, $subject, $body);
    }

    public function sendReservasiReceipt(string $toEmail, string $toName, array $reservasi, string $paketNama): bool
    {
        helper('layanan');

        $subject = 'Bukti Pembayaran Reservasi ' . $reservasi['kode_reservasi'];
        $body = view('emails/reservasi_receipt', [
            'reservasi' => $reservasi,
            'paketNama' => $paketNama,
            'nama'      => $toName,
        ]);

        return $this->send($toEmail, $subject, $body);
    }

    protected function send(string $to, string $subject, string $body): bool
    {
        try {
            $this->email->clear();
            $this->email->setTo($to);
            $this->email->setSubject($subject);
            $this->email->setMessage($body);

            if (!$this->email->send(false)) {
                log_message('error', 'Email gagal dikirim ke ' . $to . ': ' . $this->email->printDebugger(['headers']));
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            log_message('error', 'Email exception: ' . $e->getMessage());
            return false;
        }
    }
}
