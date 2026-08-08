<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$pembayaran = (string) $reservasi['status_pembayaran'];
$reservasiStatus = (string) $reservasi['status_reservasi'];
$isHomestay = !empty($reservasi['check_in']);
?>
<section class="section container" style="max-width:680px;margin:0 auto">
  <p class="akun-back"><a href="<?= site_url('/') ?>">← Kembali ke Beranda</a></p>

  <div class="status-card">
    <div class="status-card-head">
      <div>
        <div class="legal-kicker">Status Reservasi</div>
        <h1 style="margin:0"><?= esc($reservasi['kode_reservasi']) ?></h1>
      </div>
      <div class="status-badges">
        <span class="badge <?= esc($pembayaran) ?>"><?= esc(label_status_pembayaran($pembayaran)) ?></span>
        <span class="badge badge-reservasi"><?= esc(label_status_reservasi($reservasiStatus)) ?></span>
      </div>
    </div>

    <?php if ($pembayaran === 'pending'): ?>
      <div class="alert alert-info">
        Pembayaran Anda <strong>menunggu pembayaran</strong>. Setelah pembayaran berhasil, status akan diperbarui
        otomatis dalam beberapa saat.
      </div>
    <?php elseif ($pembayaran === 'paid'): ?>
      <div class="alert alert-success">Pembayaran telah <strong>lunas</strong>. Reservasi Anda terkonfirmasi.</div>
    <?php elseif ($pembayaran === 'failed'): ?>
      <div class="alert alert-error">Pembayaran <strong>gagal</strong>. Silakan lakukan reservasi ulang.</div>
    <?php elseif ($pembayaran === 'expired'): ?>
      <div class="alert alert-error">Pembayaran <strong>kedaluwarsa</strong>. Silakan lakukan reservasi ulang.</div>
    <?php endif; ?>

    <div class="status-panel">
      <h2>Detail booking</h2>
      <dl class="akun-dl">
        <div>
          <dt>Paket</dt>
          <dd>
            <?= esc($paket['nama'] ?? '-') ?>
            <?php if (!empty($paket['slug'])): ?>
              <a class="status-link" href="<?= site_url('paket-wisata/' . $paket['slug']) ?>">(lihat paket)</a>
            <?php endif; ?>
          </dd>
        </div>
        <?php if ($isHomestay): ?>
          <div>
            <dt>Check-in</dt>
            <dd><?= esc(format_tanggal($reservasi['check_in'])) ?></dd>
          </div>
          <div>
            <dt>Check-out</dt>
            <dd><?= esc(format_tanggal($reservasi['check_out'] ?? null)) ?></dd>
          </div>
          <div>
            <dt>Jumlah malam</dt>
            <dd><?= (int) ($reservasi['jumlah_malam'] ?? 0) ?> malam</dd>
          </div>
        <?php else: ?>
          <div>
            <dt>Tanggal kegiatan</dt>
            <dd><?= esc(format_tanggal($jadwal['tanggal'] ?? null)) ?></dd>
          </div>
        <?php endif; ?>
        <div>
          <dt>Jumlah tamu</dt>
          <dd><?= (int) $reservasi['jumlah_tamu'] ?> orang</dd>
        </div>
        <div>
          <dt>Atas nama</dt>
          <dd><?= esc($pelanggan['nama'] ?? '-') ?></dd>
        </div>
        <?php if (!empty($reservasi['catatan'])): ?>
          <div>
            <dt>Catatan</dt>
            <dd><?= esc($reservasi['catatan']) ?></dd>
          </div>
        <?php endif; ?>
        <div class="status-total">
          <dt>Total dibayar</dt>
          <dd class="status-total-value"><?= format_rupiah($reservasi['total_harga']) ?></dd>
        </div>
      </dl>
    </div>

    <a href="<?= site_url('/') ?>" class="btn btn-outline">Kembali ke Beranda</a>
  </div>
</section>
<?= $this->endSection() ?>