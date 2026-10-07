<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$pelanggan = $pelanggan ?? [];
$reservasi = $reservasi ?? [];
$orders = $orders ?? [];
$fiturProduk = fitur_produk_aktif();
$fiturReservasi = fitur_reservasi_aktif();
?>
<section class="section container">
  <div class="akun-layout">
    <?= view('akun/_sidebar', ['pelanggan' => $pelanggan, 'aktifMenu' => $aktifMenu ?? 'dashboard']) ?>

    <div class="akun-main">
      <h1 class="section-title" style="text-align:left;margin-bottom:0.5rem">Halo, <?= esc($pelanggan['nama'] ?? '') ?>
      </h1>
      <p class="akun-lead">Ringkasan transaksi terbaru Anda.</p>

      <div class="akun-stats">
        <?php if ($fiturReservasi): ?>
          <a class="akun-stat" href="<?= site_url('akun/reservasi') ?>">
            <span class="akun-stat-label">Reservasi</span>
            <strong><?= count($reservasi) ?><?= count($reservasi) >= 5 ? '+' : '' ?></strong>
          </a>
        <?php endif; ?>
        <?php if ($fiturProduk): ?>
          <a class="akun-stat" href="<?= site_url('akun/order') ?>">
            <span class="akun-stat-label">Pesanan</span>
            <strong><?= count($orders) ?><?= count($orders) >= 5 ? '+' : '' ?></strong>
          </a>
        <?php endif; ?>
        <a class="akun-stat" href="<?= site_url('akun/profil') ?>">
          <span class="akun-stat-label">Profil</span>
          <strong>Edit</strong>
        </a>
      </div>

      <?php if ($fiturReservasi): ?>
        <div class="akun-panel">
          <div class="akun-panel-head">
            <h2>Reservasi terbaru</h2>
            <a href="<?= site_url('akun/reservasi') ?>">Lihat semua</a>
          </div>
          <?php if (empty($reservasi)): ?>
            <p class="akun-empty">Belum ada reservasi. <a href="<?= site_url('paket-wisata') ?>">Jelajahi paket</a></p>
          <?php else: ?>
            <div class="akun-table-wrap">
              <table class="akun-table akun-table--cards">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Paket</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($reservasi as $r): ?>
                    <tr>
                      <td data-label="Kode"><?= esc($r['kode_reservasi']) ?></td>
                      <td data-label="Paket"><?= esc($r['paket_nama']) ?></td>
                      <td data-label="Pembayaran"><span
                          class="akun-badge"><?= esc(label_status_pembayaran((string) $r['status_pembayaran'])) ?></span></td>
                      <td data-label="Status"><?= esc(label_status_reservasi((string) $r['status_reservasi'])) ?></td>
                      <td data-label=""><a href="<?= site_url('akun/reservasi/' . $r['id']) ?>">Detail</a></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if ($fiturProduk): ?>
        <div class="akun-panel">
          <div class="akun-panel-head">
            <h2>Pesanan terbaru</h2>
            <a href="<?= site_url('akun/order') ?>">Lihat semua</a>
          </div>
          <?php if (empty($orders)): ?>
            <p class="akun-empty">Belum ada pesanan. <a href="<?= site_url('toko') ?>">Belanja di toko</a></p>
          <?php else: ?>
            <div class="akun-table-wrap">
              <table class="akun-table akun-table--cards">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($orders as $o): ?>
                    <tr>
                      <td data-label="Kode"><?= esc($o['kode_order']) ?></td>
                      <td data-label="Total"><?= format_rupiah($o['total_harga']) ?></td>
                      <td data-label="Pembayaran"><?= esc($o['status_pembayaran']) ?></td>
                      <td data-label="Status"><?= esc($o['status_order']) ?></td>
                      <td data-label=""><a href="<?= site_url('akun/order/' . $o['id']) ?>">Detail</a></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?= $this->endSection() ?>