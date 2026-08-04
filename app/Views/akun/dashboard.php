<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$pelanggan = $pelanggan ?? [];
$reservasi = $reservasi ?? [];
$orders = $orders ?? [];
$fiturProduk = fitur_produk_aktif();
?>
<section class="section container">
  <div class="akun-layout">
    <?= view('akun/_sidebar', ['pelanggan' => $pelanggan, 'aktifMenu' => $aktifMenu ?? 'dashboard']) ?>

    <div class="akun-main">
      <h1 class="section-title" style="text-align:left;margin-bottom:0.5rem">Halo, <?= esc($pelanggan['nama'] ?? '') ?>
      </h1>
      <p class="akun-lead">Ringkasan transaksi terbaru Anda.</p>

      <div class="akun-stats">
        <a class="akun-stat" href="<?= site_url('akun/reservasi') ?>">
          <span class="akun-stat-label">Reservasi</span>
          <strong><?= count($reservasi) ?><?= count($reservasi) >= 5 ? '+' : '' ?></strong>
        </a>
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

      <div class="akun-panel">
        <div class="akun-panel-head">
          <h2>Reservasi terbaru</h2>
          <a href="<?= site_url('akun/reservasi') ?>">Lihat semua</a>
        </div>
        <?php if (empty($reservasi)): ?>
          <p class="akun-empty">Belum ada reservasi. <a href="<?= site_url('paket-wisata') ?>">Jelajahi paket</a></p>
        <?php else: ?>
          <div class="akun-table-wrap">
            <table class="akun-table">
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
                    <td><?= esc($r['kode_reservasi']) ?></td>
                    <td><?= esc($r['paket_nama']) ?></td>
                    <td><?= esc($r['status_pembayaran']) ?></td>
                    <td><?= esc($r['status_reservasi']) ?></td>
                    <td><a href="<?= site_url('akun/reservasi/' . $r['id']) ?>">Detail</a></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

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
              <table class="akun-table">
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
                      <td><?= esc($o['kode_order']) ?></td>
                      <td><?= format_rupiah($o['total_harga']) ?></td>
                      <td><?= esc($o['status_pembayaran']) ?></td>
                      <td><?= esc($o['status_order']) ?></td>
                      <td><a href="<?= site_url('akun/order/' . $o['id']) ?>">Detail</a></td>
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