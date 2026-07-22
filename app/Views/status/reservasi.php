<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container" style="max-width:640px;margin:0 auto">
  <h1>Status Reservasi</h1>
  <p>Kode: <strong><?= esc($reservasi['kode_reservasi']) ?></strong></p>
  <p>Pembayaran: <?= badge_status($reservasi['status_pembayaran']) ?></p>
  <p>Reservasi: <?= badge_status($reservasi['status_reservasi']) ?></p>
  <hr style="border-color:var(--cream-dark);margin:1.5rem 0">
  <p><strong>Paket:</strong> <?= esc($paket['nama'] ?? '-') ?>
    <?php if (!empty($paket['jenis'])): ?>
      <small>(<?= esc($paket['jenis']) ?>)</small>
    <?php endif; ?>
  </p>
  <?php if (!empty($reservasi['check_in'])): ?>
    <p><strong>Check-in:</strong> <?= esc($reservasi['check_in']) ?></p>
    <p><strong>Check-out:</strong> <?= esc($reservasi['check_out']) ?></p>
    <p><strong>Jumlah malam:</strong> <?= (int) ($reservasi['jumlah_malam'] ?? 0) ?></p>
  <?php else: ?>
    <p><strong>Tanggal:</strong> <?= esc($jadwal['tanggal'] ?? '-') ?></p>
  <?php endif; ?>
  <p><strong>Jumlah tamu:</strong> <?= (int) $reservasi['jumlah_tamu'] ?></p>
  <p><strong>Atas nama:</strong> <?= esc($pelanggan['nama'] ?? '-') ?></p>
  <p><strong>Total:</strong> <?= format_rupiah($reservasi['total_harga']) ?></p>
  <?php if ($reservasi['status_pembayaran'] === 'pending'): ?>
    <div class="alert alert-info">Menunggu pembayaran. Jika sudah bayar, status akan berubah otomatis dalam beberapa saat.
    </div>
  <?php endif; ?>
  <a href="<?= site_url('/') ?>" class="btn btn-outline">Kembali ke Beranda</a>
</section>
<?= $this->endSection() ?>