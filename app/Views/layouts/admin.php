<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'Admin') ?> — Admin <?= esc(pengaturan()['nama_desa'] ?? 'Wisata Binangun') ?></title>
  <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/favicon.ico') ?>">
  <link rel="apple-touch-icon" href="<?= base_url('assets/images/apple-touch-icon.png') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/fonts.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body class="admin-body">
  <div class="admin-layout">
    <aside class="admin-sidebar">
      <div class="admin-brand">
        <img src="<?= brand_logo_url() ?>" alt="" class="admin-brand-logo">
        <span><strong>Panel Admin</strong><small><?= esc(pengaturan()['nama_desa'] ?? 'Wisata Binangun') ?></small></span>
      </div>
      <a href="<?= site_url('admin/dashboard') ?>">Dashboard</a>
      <a href="<?= site_url('admin/paket-wisata') ?>">Paket Wisata</a>
      <a href="<?= site_url('admin/produk') ?>">Produk</a>
      <a href="<?= site_url('admin/reservasi') ?>">Reservasi</a>
      <a href="<?= site_url('admin/order') ?>">Order</a>
      <a href="<?= site_url('admin/pembayaran') ?>">Log Midtrans</a>
      <?php if (session()->get('admin_role') === 'superadmin'): ?>
        <a href="<?= site_url('admin/users') ?>">Users</a>
      <?php endif; ?>
      <a href="<?= site_url('/') ?>" target="_blank">Lihat Situs</a>
      <a href="<?= site_url('admin/logout') ?>">Logout</a>
    </aside>
    <div class="admin-main">
      <div class="admin-topbar">
        <h1 style="margin:0;font-size:1.6rem"><?= esc($title ?? '') ?></h1>
        <span style="color:var(--sepia);font-size:0.9rem"><?= esc(session()->get('admin_nama')) ?> (<?= esc(session()->get('admin_role')) ?>)</span>
      </div>
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <?= $this->renderSection('content') ?>
    </div>
  </div>
</body>
</html>
