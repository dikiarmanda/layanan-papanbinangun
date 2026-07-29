<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'Admin') ?> — Admin <?= esc(pengaturan()['nama_desa'] ?? 'Wisata Binangun') ?></title>
  <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/favicon.ico') ?>">
  <link rel="apple-touch-icon" href="<?= base_url('assets/images/apple-touch-icon.png') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/fonts.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/flatpickr/flatpickr.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/select2/css/select2.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/dropify/css/dropify.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/sweetalert2/css/sweetalert2.min.css') ?>">
  <?= $this->renderSection('styles') ?>
</head>

<body class="admin-body">
  <div class="admin-overlay" id="adminOverlay" hidden></div>

  <div class="admin-wrap">
    <aside class="admin-sidebar" id="adminSidebar">
      <div class="sidebar-brand">
        <strong><i class="fa-solid fa-compass"></i> Panel Admin</strong>
        <small><?= esc(pengaturan()['nama_desa'] ?? 'Wisata Binangun') ?></small>
      </div>

      <?php
        $uri = uri_string();
        $navActive = static function (string $prefix) use ($uri): string {
            if ($prefix === 'admin/dashboard') {
                return ($uri === 'admin/dashboard' || $uri === 'admin') ? 'active' : '';
            }

            return str_starts_with($uri, $prefix) ? 'active' : '';
        };
      ?>

      <nav class="sidebar-nav" id="adminNav">
        <a class="<?= $navActive('admin/dashboard') ?>" href="<?= site_url('admin/dashboard') ?>">
          <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>
        <a class="<?= $navActive('admin/paket-wisata') ?>" href="<?= site_url('admin/paket-wisata') ?>">
          <i class="fa-solid fa-mountain-sun"></i> Wisata &amp; Homestay
        </a>
        <a class="<?= $navActive('admin/produk') ?>" href="<?= site_url('admin/produk') ?>">
          <i class="fa-solid fa-store"></i> Produk &amp; Catering
        </a>
        <a class="<?= $navActive('admin/zona-antar') ?>" href="<?= site_url('admin/zona-antar') ?>">
          <i class="fa-solid fa-map-location-dot"></i> Zona Antar Lokal
        </a>
        <a class="<?= $navActive('admin/reservasi') ?>" href="<?= site_url('admin/reservasi') ?>">
          <i class="fa-solid fa-calendar-check"></i> Reservasi
        </a>
        <a class="<?= $navActive('admin/order') ?>" href="<?= site_url('admin/order') ?>">
          <i class="fa-solid fa-bag-shopping"></i> Order
        </a>
        <a class="<?= $navActive('admin/pembayaran') ?>" href="<?= site_url('admin/pembayaran') ?>">
          <i class="fa-solid fa-credit-card"></i> Log Midtrans
        </a>
        <?php if (session()->get('admin_role') === 'superadmin'): ?>
          <a class="<?= $navActive('admin/users') ?>" href="<?= site_url('admin/users') ?>">
            <i class="fa-solid fa-users-gear"></i> Akun Admin
          </a>
        <?php endif; ?>
      </nav>

      <div class="sidebar-footer">
        <div class="admin-user"><i class="fa-solid fa-user"></i> <?= esc(session()->get('admin_nama')) ?></div>
        <div class="admin-role"><?= esc(session()->get('admin_role')) ?></div>
        <a href="<?= site_url('admin/logout') ?>" class="btn-logout">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
        <a href="<?= site_url('/') ?>" class="btn-site" target="_blank" rel="noopener">
          <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Situs
        </a>
      </div>
    </aside>

    <main class="admin-main">
      <div class="admin-topbar-mobile">
        <button type="button" class="admin-nav-toggle" id="adminNavToggle" aria-label="Buka menu"
          aria-expanded="false" aria-controls="adminSidebar">
          <i class="fa-solid fa-bars"></i>
        </button>
        <strong><?= esc(pengaturan()['nama_desa'] ?? 'Wisata Binangun') ?></strong>
      </div>

      <header class="admin-header">
        <div>
          <p class="admin-breadcrumb">Panel Admin</p>
          <h1><?= esc($title ?? 'Dashboard') ?></h1>
        </div>
        <div class="admin-header-actions">
          <a href="<?= site_url('/') ?>" class="btn btn-outline btn-sm" target="_blank" rel="noopener">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Situs
          </a>
        </div>
      </header>

      <?php if (session()->getFlashdata('success')): ?>
        <div class="swal-flash" data-type="success" data-message="<?= esc(session()->getFlashdata('success'), 'attr') ?>" hidden></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="swal-flash" data-type="error" data-message="<?= esc(session()->getFlashdata('error'), 'attr') ?>" hidden></div>
      <?php endif; ?>

      <?= $this->renderSection('content') ?>
    </main>
  </div>

  <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/select2/js/select2.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/select2/js/i18n/id.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/dropify/js/dropify.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/sweetalert2/js/sweetalert2.all.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/flatpickr/flatpickr.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/flatpickr/l10n/id.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/lexical/lexical-editor.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/swal-helper.js') ?>"></script>
  <?= $this->renderSection('scripts') ?>
  <script src="<?= base_url('assets/js/datepicker.js') ?>"></script>
  <script src="<?= base_url('assets/js/vendor-init.js') ?>"></script>
  <script src="<?= base_url('assets/js/admin.js') ?>"></script>
</body>

</html>
