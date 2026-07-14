<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'Layanan') ?> — Desa Wisata Papanbinangun</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Lora:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
  <header class="site-header">
    <div class="container inner">
      <a href="<?= site_url('/') ?>" class="brand">Layanan Papanbinangun</a>
      <nav class="nav-links">
        <a href="<?= site_url('paket-wisata') ?>">Paket Wisata</a>
        <a href="<?= site_url('toko') ?>">Toko UMKM</a>
        <a href="<?= site_url('keranjang') ?>">Keranjang</a>
        <a href="<?= site_url('/') ?>#cek-status">Cek Status</a>
      </nav>
    </div>
  </header>

  <main>
    <?php if (session()->getFlashdata('success')): ?>
      <div class="container" style="padding-top:1rem"><div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="container" style="padding-top:1rem"><div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
  </main>

  <footer class="site-footer">
    <div class="container">
      &copy; <?= date('Y') ?> Desa Wisata Papanbinangun — Layanan Reservasi &amp; Toko UMKM
    </div>
  </footer>
</body>
</html>
