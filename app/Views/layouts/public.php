<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="theme-color" content="#4a3222">
  <?php
  $site = pengaturan();
  $brandName = $site['nama_desa'] ?: 'Wisata Binangun';
  $fiturReservasi = fitur_reservasi_aktif();
  $fiturProduk = fitur_produk_aktif();
  $subTagline = match (true) {
    $fiturReservasi && $fiturProduk => 'Layanan Reservasi & Toko UMKM',
    $fiturProduk => 'Toko UMKM & Catering',
    $fiturReservasi => 'Layanan Wisata & Homestay',
    default => 'Layanan Desa',
  };
  $pageTitle = ($title ?? $subTagline) . ' — ' . $brandName;
  $description = $meta_description
    ?? (strip_tags((string) ($site['deskripsi_singkat'] ?: $site['tagline'])) ?: (
      $fiturProduk && $fiturReservasi
      ? 'Pesan paket wisata, homestay, dan produk UMKM secara mudah dan aman.'
      : ($fiturProduk ? 'Belanja produk UMKM dan catering desa secara mudah dan aman.' : 'Pesan paket wisata dan homestay desa secara mudah dan aman.')
    ));
  $ogImage = base_url('assets/images/og-image.png');
  $uri = uri_string();

  // Session & Auth state
  $cart = session()->get('cart') ?? [];
  $cartCount = is_array($cart) ? count($cart) : 0;
  $pLogin = pelanggan_aktif();
  $isLoggedIn = pelanggan_logged_in();
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
  <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/flatpickr/flatpickr.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/select2/css/select2.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/dropify/css/dropify.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/sweetalert2/css/sweetalert2.min.css') ?>">

  <style>
    /* ==========================================================================
       VINTAGE AESTHETIC REFINEMENT — Papanbinangun Layanan Desa
       ========================================================================== */
    :root {
      --header-h: 70px;
      --bottom-bar-h: 62px;
    }

    /* Top Utility Bar */
    .site-topbar {
      background: var(--deep, #4a3222);
      color: var(--cream, #f4e9d8);
      font-size: 0.8rem;
      padding: 0.35rem 0;
      border-bottom: 1px solid rgba(201, 166, 107, 0.25);
    }

    .topbar-inner {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
    }

    .topbar-left,
    .topbar-right {
      display: flex;
      align-items: center;
      gap: 1.25rem;
    }

    .topbar-item {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      color: rgba(244, 233, 216, 0.85);
    }

    .topbar-item i {
      color: var(--gold, #c9a66b);
      font-size: 0.82rem;
    }

    .topbar-link {
      color: var(--cream, #f4e9d8);
      transition: color .2s;
    }

    .topbar-link:hover {
      color: var(--gold, #c9a66b);
    }

    /* Main Header */
    .site-header {
      background: rgba(255, 251, 245, 0.94);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--cream-dark, #e8d9c0);
      position: sticky;
      top: 0;
      z-index: 60;
      transition: box-shadow .25s ease;
    }

    .site-header.is-scrolled {
      box-shadow: 0 4px 18px var(--shadow, rgba(74, 50, 34, 0.08));
    }

    .header-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: var(--header-h);
      gap: 1.25rem;
    }

    /* Brand Logo */
    .brand {
      display: flex;
      align-items: center;
      gap: 0.85rem;
      text-decoration: none;
      color: var(--deep, #4a3222);
    }

    .brand-logo-frame {
      position: relative;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      padding: 2px;
      background: linear-gradient(135deg, var(--gold, #c9a66b), var(--terracotta, #c1502e));
      box-shadow: 0 2px 8px rgba(74, 50, 34, 0.15);
      flex-shrink: 0;
    }

    .brand-logo-frame img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
      background: var(--white, #fffbf5);
    }

    .brand-text {
      display: flex;
      flex-direction: column;
      line-height: 1.15;
    }

    .brand-name {
      font-family: var(--font-heading, Georgia, serif);
      font-size: 1.35rem;
      font-weight: 700;
      color: var(--deep, #4a3222);
      letter-spacing: -0.01em;
    }

    .brand-tagline {
      font-family: var(--font-body, serif);
      font-size: 0.74rem;
      color: var(--sepia, #8b5e3c);
      letter-spacing: 0.02em;
    }

    /* Desktop Navigation */
    .desktop-nav {
      display: flex;
      align-items: center;
      gap: 0.35rem;
    }

    .nav-item {
      color: var(--deep, #4a3222);
      font-size: 0.94rem;
      font-weight: 500;
      padding: 0.55rem 0.85rem;
      border-radius: 6px;
      position: relative;
      transition: color .2s, background .2s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
    }

    .nav-item:hover {
      color: var(--terracotta, #c1502e);
      background: rgba(193, 80, 46, 0.05);
    }

    .nav-item.active {
      color: var(--terracotta, #c1502e);
      font-weight: 600;
    }

    .nav-item.active::after {
      content: "";
      position: absolute;
      bottom: -4px;
      left: 0.85rem;
      right: 0.85rem;
      height: 2px;
      background: var(--terracotta, #c1502e);
      border-radius: 2px;
    }

    /* Header Actions: Cart & Auth */
    .header-actions {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .nav-cart-btn {
      position: relative;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 0.95rem;
      border-radius: 25px;
      background: rgba(244, 233, 216, 0.6);
      border: 1px solid var(--cream-dark, #e8d9c0);
      color: var(--deep, #4a3222);
      font-size: 0.88rem;
      font-weight: 600;
      text-decoration: none;
      transition: all .2s ease;
    }

    .nav-cart-btn:hover {
      background: var(--white, #fff);
      border-color: var(--gold, #c9a66b);
      color: var(--terracotta, #c1502e);
      box-shadow: 0 2px 8px var(--shadow, rgba(74, 50, 34, 0.08));
    }

    .nav-cart-btn.active {
      border-color: var(--terracotta, #c1502e);
      color: var(--terracotta, #c1502e);
    }

    .cart-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 18px;
      height: 18px;
      padding: 0 5px;
      font-size: 0.7rem;
      font-weight: 700;
      color: #ffffff;
      background: var(--terracotta, #c1502e);
      border-radius: 10px;
      line-height: 1;
    }

    .nav-auth-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.48rem 1.05rem;
      border-radius: 25px;
      background: var(--terracotta, #c1502e);
      color: #ffffff !important;
      font-size: 0.88rem;
      font-weight: 600;
      text-decoration: none;
      transition: background .2s, transform .2s, box-shadow .2s;
      box-shadow: 0 2px 6px rgba(193, 80, 46, 0.25);
    }

    .nav-auth-btn:hover {
      background: #a94122;
      transform: translateY(-1px);
      box-shadow: 0 4px 10px rgba(193, 80, 46, 0.35);
    }

    .nav-user-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.45rem 0.95rem;
      border-radius: 25px;
      background: rgba(122, 132, 80, 0.12);
      border: 1px solid rgba(122, 132, 80, 0.3);
      color: var(--deep, #4a3222);
      font-size: 0.88rem;
      font-weight: 600;
      text-decoration: none;
      transition: all .2s ease;
    }

    .nav-user-pill:hover {
      background: rgba(122, 132, 80, 0.2);
      border-color: var(--olive, #7a8450);
      color: var(--olive, #7a8450);
    }

    .nav-user-pill.active {
      background: var(--olive, #7a8450);
      color: #ffffff !important;
    }

    /* Mobile Quick Cart & Hamburger */
    .mobile-header-actions {
      display: none;
      align-items: center;
      gap: 0.6rem;
    }

    .mobile-cart-icon {
      position: relative;
      width: 40px;
      height: 40px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: var(--cream, #f4e9d8);
      color: var(--deep, #4a3222);
      text-decoration: none;
      font-size: 1.05rem;
      border: 1px solid var(--cream-dark, #e8d9c0);
    }

    .mobile-cart-badge {
      position: absolute;
      top: -2px;
      right: -2px;
      min-width: 17px;
      height: 17px;
      padding: 0 4px;
      font-size: 0.65rem;
      font-weight: 700;
      color: #ffffff;
      background: var(--terracotta, #c1502e);
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid var(--white, #fffbf5);
    }

    .mobile-menu-toggle {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      border: 1px solid var(--cream-dark, #e8d9c0);
      background: var(--white, #fffbf5);
      color: var(--deep, #4a3222);
      display: inline-flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 5px;
      cursor: pointer;
      padding: 0;
      transition: all .2s ease;
    }

    .mobile-menu-toggle span {
      width: 20px;
      height: 2px;
      background: var(--deep, #4a3222);
      border-radius: 2px;
      transition: transform .25s ease, opacity .2s ease;
    }

    .mobile-menu-toggle.is-active span:nth-child(1) {
      transform: translateY(7px) rotate(45deg);
    }

    .mobile-menu-toggle.is-active span:nth-child(2) {
      opacity: 0;
    }

    .mobile-menu-toggle.is-active span:nth-child(3) {
      transform: translateY(-7px) rotate(-45deg);
    }

    /* Off-Canvas Mobile Drawer */
    .mobile-drawer-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(43, 33, 24, 0.55);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      z-index: 90;
      opacity: 0;
      visibility: hidden;
      transition: opacity .3s ease, visibility .3s ease;
    }

    .mobile-drawer-backdrop.is-open {
      opacity: 1;
      visibility: visible;
    }

    .mobile-drawer {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      width: 82%;
      max-width: 340px;
      background: var(--white, #fffbf5);
      z-index: 100;
      box-shadow: -4px 0 25px rgba(43, 33, 24, 0.25);
      transform: translateX(100%);
      transition: transform .32s cubic-bezier(0.16, 1, 0.3, 1);
      display: flex;
      flex-direction: column;
      overflow-y: auto;
    }

    .mobile-drawer.is-open {
      transform: translateX(0);
    }

    .drawer-header {
      padding: 1.25rem 1.25rem 1rem;
      border-bottom: 1px solid var(--cream-dark, #e8d9c0);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: var(--cream, #f4e9d8);
    }

    .drawer-brand {
      display: flex;
      align-items: center;
      gap: 0.65rem;
    }

    .drawer-close {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: var(--white, #fffbf5);
      border: 1px solid var(--cream-dark, #e8d9c0);
      color: var(--deep, #4a3222);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 1rem;
    }

    .drawer-user-card {
      margin: 1rem 1.25rem 0.5rem;
      padding: 1rem;
      background: var(--cream, #f4e9d8);
      border: 1px solid var(--cream-dark, #e8d9c0);
      border-radius: 10px;
    }

    .drawer-user-info {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 0.75rem;
    }

    .drawer-user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--gold, #c9a66b);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      flex-shrink: 0;
    }

    .drawer-user-name {
      font-weight: 700;
      color: var(--deep, #4a3222);
      font-size: 0.95rem;
      line-height: 1.2;
    }

    .drawer-user-email {
      font-size: 0.75rem;
      color: var(--sepia, #8b5e3c);
    }

    .drawer-user-actions {
      display: flex;
      gap: 0.5rem;
    }

    .drawer-guest-card {
      margin: 1rem 1.25rem 0.5rem;
      padding: 1.15rem 1rem;
      background: rgba(193, 80, 46, 0.05);
      border: 1px dashed rgba(193, 80, 46, 0.3);
      border-radius: 10px;
      text-align: center;
    }

    .drawer-guest-card p {
      font-size: 0.85rem;
      color: var(--sepia, #8b5e3c);
      margin: 0 0 0.85rem;
    }

    .drawer-nav-list {
      padding: 0.75rem 1rem;
      display: flex;
      flex-direction: column;
      gap: 0.3rem;
      flex: 1;
    }

    .drawer-link {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.75rem 0.85rem;
      border-radius: 8px;
      color: var(--deep, #4a3222);
      text-decoration: none;
      font-size: 0.96rem;
      font-weight: 500;
      transition: background .2s, color .2s;
    }

    .drawer-link-left {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .drawer-link-left i {
      width: 20px;
      text-align: center;
      color: var(--sepia, #8b5e3c);
      font-size: 1rem;
    }

    .drawer-link:hover,
    .drawer-link.active {
      background: rgba(193, 80, 46, 0.08);
      color: var(--terracotta, #c1502e);
    }

    .drawer-link.active .drawer-link-left i {
      color: var(--terracotta, #c1502e);
    }

    .drawer-footer {
      padding: 1rem 1.25rem 1.5rem;
      border-top: 1px solid var(--cream-dark, #e8d9c0);
      background: #faf6ee;
    }

    .drawer-wa-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      width: 100%;
      padding: 0.65rem;
      border-radius: 8px;
      background: #25d366;
      color: #ffffff !important;
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      box-shadow: 0 2px 8px rgba(37, 211, 102, 0.25);
    }

    /* Mobile Bottom Navigation Dock */
    .mobile-bottom-dock {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: var(--bottom-bar-h);
      background: rgba(255, 251, 245, 0.96);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-top: 1px solid var(--cream-dark, #e8d9c0);
      box-shadow: 0 -3px 16px rgba(74, 50, 34, 0.08);
      z-index: 55;
      padding-bottom: env(safe-area-inset-bottom, 0px);
    }

    .bottom-dock-inner {
      display: flex;
      align-items: center;
      justify-content: space-around;
      height: 100%;
      max-width: 500px;
      margin: 0 auto;
    }

    .dock-tab {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 3px;
      color: var(--sepia, #8b5e3c);
      text-decoration: none;
      font-size: 0.68rem;
      font-weight: 500;
      position: relative;
      flex: 1;
      height: 100%;
      transition: color .2s;
    }

    .dock-tab i {
      font-size: 1.15rem;
      transition: transform .2s ease;
    }

    .dock-tab.active {
      color: var(--terracotta, #c1502e);
      font-weight: 700;
    }

    .dock-tab.active i {
      transform: translateY(-2px);
    }

    .dock-badge {
      position: absolute;
      top: 6px;
      right: calc(50% - 18px);
      min-width: 16px;
      height: 16px;
      padding: 0 4px;
      font-size: 0.62rem;
      font-weight: 700;
      background: var(--terracotta, #c1502e);
      color: #fff;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      line-height: 1;
    }

    /* Floating WhatsApp Button (Desktop & Mobile) */
    .wa-float-btn {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      width: 54px;
      height: 54px;
      border-radius: 50%;
      background: #25d366;
      color: #ffffff !important;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      box-shadow: 0 4px 16px rgba(37, 211, 102, 0.4);
      z-index: 45;
      text-decoration: none;
      transition: transform .25s ease, box-shadow .25s ease;
    }

    .wa-float-btn:hover {
      transform: scale(1.08) translateY(-2px);
      box-shadow: 0 6px 20px rgba(37, 211, 102, 0.55);
    }

    .wa-float-tooltip {
      position: absolute;
      right: 64px;
      background: var(--deep, #4a3222);
      color: #fff;
      font-size: 0.78rem;
      font-weight: 500;
      padding: 0.35rem 0.75rem;
      border-radius: 6px;
      white-space: nowrap;
      pointer-events: none;
      opacity: 0;
      transform: translateX(8px);
      transition: all .2s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .wa-float-tooltip::after {
      content: "";
      position: absolute;
      top: 50%;
      right: -5px;
      transform: translateY(-50%);
      border-width: 5px 0 5px 5px;
      border-style: solid;
      border-color: transparent transparent transparent var(--deep, #4a3222);
    }

    .wa-float-btn:hover .wa-float-tooltip {
      opacity: 1;
      transform: translateX(0);
    }

    /* ==========================================================================
       FOOTER REFINEMENT
       ========================================================================== */
    .site-footer {
      margin-top: 5rem;
      background: var(--deep, #4a3222);
      color: var(--cream, #f4e9d8);
      font-size: 0.92rem;
      border-top: 3px solid var(--gold, #c9a66b);
      position: relative;
    }

    .site-footer::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: rgba(255, 255, 255, 0.1);
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 1.6fr 1fr 1.2fr;
      gap: 2.5rem;
      padding: 3.5rem 0 2.5rem;
    }

    .footer-brand-title {
      font-family: var(--font-heading, Georgia, serif);
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--gold, #c9a66b);
      margin-bottom: 0.65rem;
    }

    .footer-desc {
      color: rgba(244, 233, 216, 0.85);
      line-height: 1.65;
      font-size: 0.92rem;
      max-width: 380px;
    }

    .footer-heading {
      font-family: var(--font-heading, Georgia, serif);
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--gold, #c9a66b);
      margin-bottom: 1.15rem;
      position: relative;
      display: inline-block;
    }

    .footer-heading::after {
      content: "";
      position: absolute;
      bottom: -4px;
      left: 0;
      width: 24px;
      height: 2px;
      background: var(--terracotta, #c1502e);
    }

    .footer-links-list {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap: 0.55rem;
    }

    .footer-links-list a {
      color: rgba(244, 233, 216, 0.88);
      text-decoration: none;
      transition: color .2s, transform .2s;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
    }

    .footer-links-list a:hover {
      color: var(--gold, #c9a66b);
      transform: translateX(3px);
    }

    .footer-contact-item {
      display: flex;
      gap: 0.65rem;
      margin-bottom: 0.75rem;
      color: rgba(244, 233, 216, 0.88);
      line-height: 1.5;
    }

    .footer-contact-item i {
      color: var(--gold, #c9a66b);
      margin-top: 0.25rem;
      font-size: 0.95rem;
      flex-shrink: 0;
    }

    .footer-social-wrap {
      display: flex;
      gap: 0.6rem;
      margin-top: 1.25rem;
    }

    .footer-social-link {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(201, 166, 107, 0.15);
      border: 1px solid rgba(201, 166, 107, 0.3);
      color: var(--cream, #f4e9d8) !important;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
      transition: all .2s ease;
      text-decoration: none;
    }

    .footer-social-link:hover {
      background: var(--gold, #c9a66b);
      color: var(--deep, #4a3222) !important;
      transform: translateY(-2px);
    }

    .footer-bottom {
      border-top: 1px solid rgba(201, 166, 107, 0.2);
      padding: 1.15rem 0;
      font-size: 0.84rem;
      color: rgba(244, 233, 216, 0.7);
    }

    .footer-bottom-inner {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 0.75rem;
    }

    .footer-bottom-inner p {
      margin: 0;
    }

    .footer-bottom-links a {
      color: rgba(244, 233, 216, 0.7);
      text-decoration: none;
      transition: color .2s;
    }

    .footer-bottom-links a:hover {
      color: var(--gold, #c9a66b);
    }

    /* ==========================================================================
       RESPONSIVE BREAKPOINTS
       ========================================================================== */
    @media (max-width: 992px) {
      .footer-grid {
        grid-template-columns: 1fr 1fr;
      }

      .footer-desc {
        max-width: none;
      }
    }

    @media (max-width: 768px) {
      .site-topbar {
        display: none;
      }

      .desktop-nav,
      .header-actions {
        display: none;
      }

      .mobile-header-actions {
        display: flex;
      }

      .footer-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
        padding: 2.5rem 0 2rem;
      }

      .footer-bottom-inner {
        flex-direction: column;
        text-align: center;
      }

      .wa-float-btn {
        bottom: calc(var(--bottom-bar-h) + 1.25rem);
        right: 1.25rem;
        width: 48px;
        height: 48px;
        font-size: 1.55rem;
      }

      .wa-float-tooltip {
        display: none;
      }

      .mobile-bottom-dock {
        display: block;
      }

      /* Prevent mobile content overlapping bottom dock */
      body {
        padding-bottom: var(--bottom-bar-h);
      }
    }
  </style>

  <?= $this->renderSection('styles') ?>
</head>

<body>
  <!-- MAIN HEADER -->
  <header class="site-header" id="siteHeader">
    <div class="container header-inner">
      <!-- BRAND -->
      <a href="<?= site_url('/') ?>" class="brand" aria-label="<?= esc($brandName) ?>">
        <div class="brand-logo-frame">
          <img src="<?= brand_logo_url() ?>" alt="<?= esc($brandName) ?>">
        </div>
        <div class="brand-text">
          <span class="brand-name"><?= esc($brandName) ?></span>
          <span class="brand-tagline"><?= esc($subTagline) ?></span>
        </div>
      </a>

      <!-- DESKTOP NAVIGATION -->
      <nav class="desktop-nav" aria-label="Navigasi Utama">
        <a href="<?= site_url('/') ?>" class="nav-item <?= $uri === '' ? 'active' : '' ?>">Beranda</a>
        <?php if ($fiturReservasi): ?>
          <a href="<?= site_url('paket-wisata') ?>"
            class="nav-item <?= str_starts_with($uri, 'paket-wisata') ? 'active' : '' ?>">
            Wisata &amp; Homestay
          </a>
        <?php endif; ?>
        <?php if ($fiturProduk): ?>
          <a href="<?= site_url('toko') ?>" class="nav-item <?= str_starts_with($uri, 'toko') ? 'active' : '' ?>">
            Toko &amp; Catering
          </a>
        <?php endif; ?>
        <a href="<?= site_url('/') ?>#cek-status" class="nav-item">Cek Status</a>
      </nav>

      <!-- DESKTOP ACTIONS -->
      <div class="header-actions">
        <?php if ($fiturProduk): ?>
          <a href="<?= site_url('keranjang') ?>"
            class="nav-cart-btn <?= str_starts_with($uri, 'keranjang') || str_starts_with($uri, 'checkout-produk') ? 'active' : '' ?>"
            title="Keranjang Belanja">
            <i class="fa-solid fa-bag-shopping"></i>
            <span>Keranjang</span>
            <?php if ($cartCount > 0): ?>
              <span class="cart-badge" id="headerCartCount"><?= $cartCount ?></span>
            <?php endif; ?>
          </a>
        <?php endif; ?>

        <?php if ($isLoggedIn && $pLogin): ?>
          <a href="<?= site_url('akun') ?>" class="nav-user-pill <?= str_starts_with($uri, 'akun') ? 'active' : '' ?>"
            title="Akun Pelanggan">
            <i class="fa-solid fa-circle-user"></i>
            <span>Halo, <?= esc(explode(' ', trim($pLogin['nama']))[0]) ?></span>
          </a>
        <?php else: ?>
          <a href="<?= site_url('masuk') ?>"
            class="nav-auth-btn <?= in_array($uri, ['masuk', 'daftar'], true) ? 'active' : '' ?>">
            <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk
          </a>
        <?php endif; ?>
      </div>

      <!-- MOBILE ACTIONS (QUICK CART + HAMBURGER) -->
      <div class="mobile-header-actions">
        <?php if ($fiturProduk): ?>
          <a href="<?= site_url('keranjang') ?>" class="mobile-cart-icon" aria-label="Keranjang Belanja">
            <i class="fa-solid fa-bag-shopping"></i>
            <?php if ($cartCount > 0): ?>
              <span class="mobile-cart-badge"><?= $cartCount ?></span>
            <?php endif; ?>
          </a>
        <?php endif; ?>

        <button type="button" class="mobile-menu-toggle" id="menuToggle" aria-label="Buka Menu Navigasi"
          aria-expanded="false">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>
  </header>

  <!-- MOBILE OFF-CANVAS DRAWER -->
  <div class="mobile-drawer-backdrop" id="drawerBackdrop"></div>
  <aside class="mobile-drawer" id="mobileDrawer" aria-hidden="true">
    <div class="drawer-header">
      <div class="drawer-brand">
        <div class="brand-logo-frame" style="width:36px;height:36px">
          <img src="<?= brand_logo_url() ?>" alt="">
        </div>
        <strong><?= esc($brandName) ?></strong>
      </div>
      <button type="button" class="drawer-close" id="drawerClose" aria-label="Tutup Menu">
        <i class="fa-solid fa-times"></i>
      </button>
    </div>

    <?php if ($isLoggedIn && $pLogin): ?>
      <div class="drawer-user-card">
        <div class="drawer-user-info">
          <div class="drawer-user-avatar">
            <i class="fa-solid fa-user"></i>
          </div>
          <div>
            <div class="drawer-user-name"><?= esc($pLogin['nama']) ?></div>
            <div class="drawer-user-email"><?= esc($pLogin['email']) ?></div>
          </div>
        </div>
        <div class="drawer-user-actions">
          <a href="<?= site_url('akun') ?>" class="btn btn-sm btn-primary" style="flex:1;text-align:center">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
          </a>
          <a href="<?= site_url('keluar') ?>" class="btn btn-sm btn-outline" style="text-align:center" title="Keluar">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
          </a>
        </div>
      </div>
    <?php else: ?>
      <div class="drawer-guest-card">
        <p>Masuk untuk kemudahan checkout &amp; riwayat pesanan Anda.</p>
        <div style="display:flex;gap:0.5rem">
          <a href="<?= site_url('masuk') ?>" class="btn btn-sm btn-primary" style="flex:1;text-align:center">Masuk</a>
          <a href="<?= site_url('daftar') ?>" class="btn btn-sm btn-outline" style="flex:1;text-align:center">Daftar</a>
        </div>
      </div>
    <?php endif; ?>

    <nav class="drawer-nav-list">
      <a href="<?= site_url('/') ?>" class="drawer-link <?= $uri === '' ? 'active' : '' ?>">
        <span class="drawer-link-left"><i class="fa-solid fa-house"></i> Beranda</span>
        <i class="fa-solid fa-chevron-right" style="font-size:0.75rem;opacity:0.4"></i>
      </a>

      <?php if ($fiturReservasi): ?>
        <a href="<?= site_url('paket-wisata') ?>"
          class="drawer-link <?= str_starts_with($uri, 'paket-wisata') ? 'active' : '' ?>">
          <span class="drawer-link-left"><i class="fa-solid fa-mountain-sun"></i> Wisata &amp; Homestay</span>
          <i class="fa-solid fa-chevron-right" style="font-size:0.75rem;opacity:0.4"></i>
        </a>
      <?php endif; ?>

      <?php if ($fiturProduk): ?>
        <a href="<?= site_url('toko') ?>" class="drawer-link <?= str_starts_with($uri, 'toko') ? 'active' : '' ?>">
          <span class="drawer-link-left"><i class="fa-solid fa-store"></i> Toko &amp; Catering UMKM</span>
          <i class="fa-solid fa-chevron-right" style="font-size:0.75rem;opacity:0.4"></i>
        </a>

        <a href="<?= site_url('keranjang') ?>"
          class="drawer-link <?= str_starts_with($uri, 'keranjang') || str_starts_with($uri, 'checkout-produk') ? 'active' : '' ?>">
          <span class="drawer-link-left">
            <i class="fa-solid fa-bag-shopping"></i> Keranjang Belanja
          </span>
          <?php if ($cartCount > 0): ?>
            <span class="cart-badge"><?= $cartCount ?></span>
          <?php else: ?>
            <i class="fa-solid fa-chevron-right" style="font-size:0.75rem;opacity:0.4"></i>
          <?php endif; ?>
        </a>
      <?php endif; ?>

      <a href="<?= site_url('/') ?>#cek-status" class="drawer-link">
        <span class="drawer-link-left"><i class="fa-solid fa-receipt"></i> Cek Status Transaksi</span>
        <i class="fa-solid fa-chevron-right" style="font-size:0.75rem;opacity:0.4"></i>
      </a>
    </nav>

    <?php if (!empty($site['no_whatsapp'])): ?>
      <div class="drawer-footer">
        <a href="<?= wa_link($site['no_whatsapp'], 'Halo, saya ingin bertanya tentang layanan ' . $brandName) ?>"
          target="_blank" rel="noopener" class="drawer-wa-btn">
          <i class="fa-brands fa-whatsapp"></i> Chat WhatsApp Bantuan
        </a>
      </div>
    <?php endif; ?>
  </aside>

  <!-- MOBILE BOTTOM DOCK (NAVIGASI ERGONOMIS MOBILE) -->
  <nav class="mobile-bottom-dock" aria-label="Navigasi Bawah">
    <div class="bottom-dock-inner">
      <a href="<?= site_url('/') ?>" class="dock-tab <?= $uri === '' ? 'active' : '' ?>">
        <i class="fa-solid fa-house"></i>
        <span>Beranda</span>
      </a>

      <?php if ($fiturProduk): ?>
        <a href="<?= site_url('toko') ?>" class="dock-tab <?= str_starts_with($uri, 'toko') ? 'active' : '' ?>">
          <i class="fa-solid fa-store"></i>
          <span>Toko</span>
        </a>
      <?php elseif ($fiturReservasi): ?>
        <a href="<?= site_url('paket-wisata') ?>"
          class="dock-tab <?= str_starts_with($uri, 'paket-wisata') ? 'active' : '' ?>">
          <i class="fa-solid fa-mountain-sun"></i>
          <span>Wisata</span>
        </a>
      <?php endif; ?>

      <?php if ($fiturProduk): ?>
        <a href="<?= site_url('keranjang') ?>"
          class="dock-tab <?= str_starts_with($uri, 'keranjang') || str_starts_with($uri, 'checkout-produk') ? 'active' : '' ?>">
          <i class="fa-solid fa-bag-shopping"></i>
          <span>Keranjang</span>
          <?php if ($cartCount > 0): ?>
            <span class="dock-badge"><?= $cartCount ?></span>
          <?php endif; ?>
        </a>
      <?php endif; ?>

      <?php if ($isLoggedIn): ?>
        <a href="<?= site_url('akun') ?>" class="dock-tab <?= str_starts_with($uri, 'akun') ? 'active' : '' ?>">
          <i class="fa-solid fa-user-circle"></i>
          <span>Akun</span>
        </a>
      <?php else: ?>
        <a href="<?= site_url('masuk') ?>"
          class="dock-tab <?= in_array($uri, ['masuk', 'daftar'], true) ? 'active' : '' ?>">
          <i class="fa-solid fa-arrow-right-to-bracket"></i>
          <span>Masuk</span>
        </a>
      <?php endif; ?>

      <?php if (!empty($site['no_whatsapp'])): ?>
        <a href="<?= wa_link($site['no_whatsapp'], 'Halo, saya ingin bertanya tentang layanan ' . $brandName) ?>"
          target="_blank" rel="noopener" class="dock-tab">
          <i class="fa-brands fa-whatsapp" style="color:#25d366"></i>
          <span>Bantuan</span>
        </a>
      <?php endif; ?>
    </div>
  </nav>

  <!-- MAIN CONTENT CONTAINER -->
  <main id="mainContent">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="swal-flash" data-type="success" data-message="<?= esc(session()->getFlashdata('success'), 'attr') ?>"
        hidden></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="swal-flash" data-type="error" data-message="<?= esc(session()->getFlashdata('error'), 'attr') ?>"
        hidden></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
  </main>

  <!-- REFINED VINTAGE FOOTER -->
  <footer class="site-footer">
    <div class="container footer-grid">
      <div>
        <h3 class="footer-brand-title"><?= esc($brandName) ?></h3>
        <p class="footer-desc">
          <?= esc(strip_tags((string) ($site['deskripsi_singkat'] ?: $site['tagline']))) ?>
        </p>
        <div class="footer-social-wrap">
          <?php if (!empty($site['instagram_url'])): ?>
            <a href="<?= esc($site['instagram_url']) ?>" target="_blank" rel="noopener" class="footer-social-link"
              title="Instagram">
              <i class="fa-brands fa-instagram"></i>
            </a>
          <?php endif; ?>
          <?php if (!empty($site['facebook_url'])): ?>
            <a href="<?= esc($site['facebook_url']) ?>" target="_blank" rel="noopener" class="footer-social-link"
              title="Facebook">
              <i class="fa-brands fa-facebook-f"></i>
            </a>
          <?php endif; ?>
          <?php if (!empty($site['tiktok_url'])): ?>
            <a href="<?= esc($site['tiktok_url']) ?>" target="_blank" rel="noopener" class="footer-social-link"
              title="TikTok">
              <i class="fa-brands fa-tiktok"></i>
            </a>
          <?php endif; ?>
          <?php if (!empty($site['no_whatsapp'])): ?>
            <a href="<?= wa_link($site['no_whatsapp'], 'Halo, saya ingin bertanya tentang layanan ' . $brandName) ?>"
              target="_blank" rel="noopener" class="footer-social-link" title="WhatsApp">
              <i class="fa-brands fa-whatsapp"></i>
            </a>
          <?php endif; ?>
        </div>
      </div>

      <div>
        <h4 class="footer-heading">Navigasi Layanan</h4>
        <ul class="footer-links-list">
          <li><a href="<?= site_url('/') ?>"><i class="fa-solid fa-angle-right"
                style="font-size:0.75rem;opacity:0.6"></i> Beranda</a></li>
          <?php if ($fiturReservasi): ?>
            <li><a href="<?= site_url('paket-wisata') ?>"><i class="fa-solid fa-angle-right"
                  style="font-size:0.75rem;opacity:0.6"></i> Wisata &amp; Homestay</a></li>
          <?php endif; ?>
          <?php if ($fiturProduk): ?>
            <li><a href="<?= site_url('toko') ?>"><i class="fa-solid fa-angle-right"
                  style="font-size:0.75rem;opacity:0.6"></i> Toko UMKM &amp; Catering</a></li>
            <li><a href="<?= site_url('keranjang') ?>"><i class="fa-solid fa-angle-right"
                  style="font-size:0.75rem;opacity:0.6"></i> Keranjang Belanja</a></li>
          <?php endif; ?>
          <li><a href="<?= site_url('/') ?>#cek-status"><i class="fa-solid fa-angle-right"
                style="font-size:0.75rem;opacity:0.6"></i> Cek Status Pesanan</a></li>
          <li><a href="<?= site_url('kebijakan-privasi') ?>"><i class="fa-solid fa-angle-right"
                style="font-size:0.75rem;opacity:0.6"></i> Kebijakan Privasi</a></li>
          <li><a href="<?= site_url('persyaratan') ?>"><i class="fa-solid fa-angle-right"
                style="font-size:0.75rem;opacity:0.6"></i> Syarat &amp; Ketentuan</a></li>
        </ul>
      </div>

      <div>
        <h4 class="footer-heading">Kontak &amp; Informasi</h4>
        <?php if (!empty($site['alamat'])): ?>
          <div class="footer-contact-item">
            <i class="fa-solid fa-location-dot"></i>
            <span><?= esc($site['alamat']) ?></span>
          </div>
        <?php endif; ?>
        <?php if (!empty($site['email_kontak'])): ?>
          <div class="footer-contact-item">
            <i class="fa-solid fa-envelope"></i>
            <a href="mailto:<?= esc($site['email_kontak']) ?>" style="color:inherit"><?= esc($site['email_kontak']) ?></a>
          </div>
        <?php endif; ?>
        <?php if (!empty($site['no_whatsapp'])): ?>
          <div class="footer-contact-item">
            <i class="fa-brands fa-whatsapp"></i>
            <a href="<?= wa_link($site['no_whatsapp'], 'Halo, saya ingin bertanya tentang layanan ' . $brandName) ?>"
              target="_blank" rel="noopener" style="color:inherit">
              +<?= esc($site['no_whatsapp']) ?> (Customer Service)
            </a>
          </div>
        <?php endif; ?>
        <div class="footer-contact-item">
          <i class="fa-regular fa-clock"></i>
          <span>Senin - Minggu: 08.00 - 17.00 WIB</span>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container footer-bottom-inner">
        <p>&copy; <?= date('Y') ?> <?= esc($brandName) ?>. Dikelola oleh Tim Layanan Desa Papanbinangun.</p>
        <p class="footer-bottom-links">
          <a href="<?= site_url('kebijakan-privasi') ?>">Privasi</a>
          <span style="margin:0 0.4rem;opacity:0.4">•</span>
          <a href="<?= site_url('persyaratan') ?>">Syarat &amp; Ketentuan</a>
        </p>
      </div>
    </div>
  </footer>

  <!-- FLOATING WHATSAPP BUTTON (DESKTOP) -->
  <?php if (!empty($site['no_whatsapp'])): ?>
    <a href="<?= wa_link($site['no_whatsapp'], 'Halo, saya ingin bertanya tentang layanan ' . $brandName) ?>"
      class="wa-float-btn" target="_blank" rel="noopener" aria-label="Hubungi WhatsApp">
      <i class="fa-brands fa-whatsapp"></i>
      <span class="wa-float-tooltip">Butuh bantuan? Chat CS Kami</span>
    </a>
  <?php endif; ?>

  <!-- SCRIPTS -->
  <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/select2/js/select2.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/select2/js/i18n/id.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/dropify/js/dropify.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/sweetalert2/js/sweetalert2.all.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/flatpickr/flatpickr.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/flatpickr/l10n/id.js') ?>"></script>
  <script src="<?= base_url('assets/js/swal-helper.js') ?>"></script>
  <script src="<?= base_url('assets/js/format-date-number.js') ?>"></script>
  <script src="<?= base_url('assets/js/datepicker.js') ?>"></script>
  <script src="<?= base_url('assets/js/select2-init.js') ?>"></script>
  <script src="<?= base_url('assets/js/vendor-init.js') ?>"></script>

  <script>
    (function () {
      // Header scroll shadow effect
      var header = document.getElementById('siteHeader');
      window.addEventListener('scroll', function () {
        if (window.scrollY > 20) {
          header.classList.add('is-scrolled');
        } else {
          header.classList.remove('is-scrolled');
        }
      }, { passive: true });

      // Mobile Drawer logic
      var menuToggle = document.getElementById('menuToggle');
      var drawer = document.getElementById('mobileDrawer');
      var backdrop = document.getElementById('drawerBackdrop');
      var drawerClose = document.getElementById('drawerClose');

      function openDrawer() {
        if (!drawer) return;
        drawer.classList.add('is-open');
        backdrop.classList.add('is-open');
        menuToggle.classList.add('is-active');
        menuToggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
      }

      function closeDrawer() {
        if (!drawer) return;
        drawer.classList.remove('is-open');
        backdrop.classList.remove('is-open');
        menuToggle.classList.remove('is-active');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }

      if (menuToggle) {
        menuToggle.addEventListener('click', function () {
          if (drawer.classList.contains('is-open')) {
            closeDrawer();
          } else {
            openDrawer();
          }
        });
      }

      if (backdrop) backdrop.addEventListener('click', closeDrawer);
      if (drawerClose) drawerClose.addEventListener('click', closeDrawer);

      // Close on escape key
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && drawer && drawer.classList.contains('is-open')) {
          closeDrawer();
        }
      });
    })();
  </script>
  <?= $this->renderSection('scripts') ?>
</body>

</html>