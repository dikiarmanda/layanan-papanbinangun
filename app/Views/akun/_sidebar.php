<?php
$aktifMenu = $aktifMenu ?? 'dashboard';
$pelanggan = $pelanggan ?? [];
$fiturProduk = fitur_produk_aktif();
$fiturReservasi = fitur_reservasi_aktif();
?>
<aside class="akun-sidebar" aria-label="Menu akun">
  <div class="akun-sidebar-head">
    <p class="akun-sidebar-label">Akun saya</p>
    <strong><?= esc($pelanggan['nama'] ?? '') ?></strong>
    <small><?= esc($pelanggan['email'] ?? '') ?></small>
  </div>
  <nav class="akun-nav">
    <a href="<?= site_url('akun') ?>" class="<?= $aktifMenu === 'dashboard' ? 'is-active' : '' ?>">Ringkasan</a>
    <?php if ($fiturReservasi): ?>
      <a href="<?= site_url('akun/reservasi') ?>"
        class="<?= $aktifMenu === 'reservasi' ? 'is-active' : '' ?>">Reservasi</a>
    <?php endif; ?>
    <?php if ($fiturProduk): ?>
      <a href="<?= site_url('akun/order') ?>" class="<?= $aktifMenu === 'order' ? 'is-active' : '' ?>">Pesanan</a>
    <?php endif; ?>
    <a href="<?= site_url('akun/profil') ?>" class="<?= $aktifMenu === 'profil' ? 'is-active' : '' ?>">Profil</a>
    <a href="<?= site_url('keluar') ?>" class="akun-nav-logout">Keluar</a>
  </nav>
</aside>