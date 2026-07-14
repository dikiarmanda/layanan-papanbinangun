<?php

namespace App\Controllers;

use App\Models\JadwalPaketWisataModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaketWisataModel;
use App\Models\PelangganModel;
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

        $items     = model(OrderItemModel::class)->forOrder((int) $order['id']);
        $pelanggan = model(PelangganModel::class)->find($order['pelanggan_id']);

        return view('status/order', [
            'title'     => 'Status Pesanan',
            'order'     => $order,
            'items'     => $items,
            'pelanggan' => $pelanggan,
        ]);
    }
}
