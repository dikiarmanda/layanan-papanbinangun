<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
    $site = pengaturan();
    $brandName = $site['nama_desa'] ?: 'Wisata Binangun';
    $pageTitle = ($title ?? 'Layanan Reservasi & Toko UMKM') . ' — ' . $brandName;
    $description = $meta_description
        ?? (strip_tags((string) ($site['deskripsi_singkat'] ?: $site['tagline'])) ?: 'Pesan paket wisata, homestay, dan produk UMKM secara mudah dan aman.');
    $ogImage = base_url('assets/images/og-image.png');
    $uri = uri_string();
  ?>
  <title><?= esc($pageTitle) ?></title>
  <meta name="description" content="<?= esc($description) ?>">
  <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/favicon.ico') ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/favicon-32x32.png') ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/images/favicon-16x16.png') ?>">
  <link rel="apple-touch-icon" href="<?= base_url('assets/images/apple-touch-icon.png') ?>">
  <meta property="og:title" content="<?= esc($pageTitle) ?>">
  <meta property="og:description" content="<?= esc($description) ?>">
  <meta property="og:image" content="<?= esc($ogImage) ?>">
  <meta property="og:type" content="website">
  <meta name="twitter:card" content="summary_large_image">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/fonts.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a href="<?= site_url('/') ?>" class="brand" aria-label="<?= esc($brandName) ?>">
        <img src="<?= brand_logo_url() ?>" alt="" class="brand-logo">
        <span class="brand-text">
          <strong><?= esc($brandName) ?></strong>
          <small class="brand-tagline">Layanan Reservasi &amp; Toko UMKM</small>
        </span>
      </a>
      <button class="nav-toggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="siteNav" id="navToggle">☰</button>
      <nav class="site-nav" id="siteNav">
        <a href="<?= site_url('/') ?>" class="<?= $uri === '' ? 'active' : '' ?>">Beranda</a>
        <a href="<?= site_url('paket-wisata') ?>" class="<?= str_starts_with($uri, 'paket-wisata') ? 'active' : '' ?>">Paket Wisata</a>
        <a href="<?= site_url('toko') ?>" class="<?= str_starts_with($uri, 'toko') ? 'active' : '' ?>">Toko UMKM</a>
        <a href="<?= site_url('keranjang') ?>" class="<?= str_starts_with($uri, 'keranjang') || str_starts_with($uri, 'checkout-produk') ? 'active' : '' ?>">Keranjang</a>
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
    <div class="container footer-grid">
      <div>
        <h3 class="footer-title"><?= esc($brandName) ?></h3>
        <p><?= esc(strip_tags((string) ($site['deskripsi_singkat'] ?: $site['tagline']))) ?></p>
      </div>
      <div>
        <h4>Navigasi</h4>
        <ul class="footer-links">
          <li><a href="<?= site_url('paket-wisata') ?>">Paket Wisata &amp; Homestay</a></li>
          <li><a href="<?= site_url('toko') ?>">Produk UMKM</a></li>
          <li><a href="<?= site_url('keranjang') ?>">Keranjang Belanja</a></li>
          <li><a href="<?= site_url('/') ?>#cek-status">Cek Status Transaksi</a></li>
        </ul>
      </div>
      <div>
        <h4>Kontak</h4>
        <?php if (! empty($site['alamat'])): ?>
          <p><?= esc($site['alamat']) ?></p>
        <?php endif; ?>
        <?php if (! empty($site['email_kontak'])): ?>
          <p><a href="mailto:<?= esc($site['email_kontak']) ?>"><?= esc($site['email_kontak']) ?></a></p>
        <?php endif; ?>
        <?php if (! empty($site['no_whatsapp'])): ?>
          <p>
            <a href="<?= wa_link($site['no_whatsapp'], 'Halo, saya ingin bertanya tentang layanan ' . $brandName) ?>" target="_blank" rel="noopener">
              WhatsApp <?= esc($site['no_whatsapp']) ?>
            </a>
          </p>
        <?php endif; ?>
        <div class="social-links">
          <?php if (! empty($site['instagram_url'])): ?>
            <a href="<?= esc($site['instagram_url']) ?>" target="_blank" rel="noopener">Instagram</a>
          <?php endif; ?>
          <?php if (! empty($site['facebook_url'])): ?>
            <a href="<?= esc($site['facebook_url']) ?>" target="_blank" rel="noopener">Facebook</a>
          <?php endif; ?>
          <?php if (! empty($site['tiktok_url'])): ?>
            <a href="<?= esc($site['tiktok_url']) ?>" target="_blank" rel="noopener">TikTok</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p>&copy; <?= date('Y') ?> <?= esc($brandName) ?>. Semua hak dilindungi.</p>
      </div>
    </div>
  </footer>

  <?php if (! empty($site['no_whatsapp'])): ?>
    <a href="<?= wa_link($site['no_whatsapp'], 'Halo, saya ingin bertanya tentang layanan ' . $brandName) ?>"
       class="wa-float" target="_blank" rel="noopener" aria-label="Chat WhatsApp">WA</a>
  <?php endif; ?>

  <script>
    (() => {
      const toggle = document.getElementById('navToggle');
      const nav = document.getElementById('siteNav');
      if (!toggle || !nav) return;
      toggle.addEventListener('click', () => {
        const open = nav.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', String(open));
        toggle.textContent = open ? '×' : '☰';
      });
    })();
  </script>
  <?= $this->renderSection('scripts') ?>
</body>
</html>
