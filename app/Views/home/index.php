<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$site = pengaturan();
$brand = $site['nama_desa'] ?: 'Wisata Binangun';
$tagline = $site['tagline'] ?: 'Jelajahi Desa, Bawa Pulang Ceritanya';
$heroSlides = $heroSlides ?? [];
$heroBackgrounds = $heroBackgrounds ?? [];
$estimateWisata = $estimateWisata ?? [];
$estimateMenginap = $estimateMenginap ?? [];
$bgWisata = $heroBackgrounds['wisata'] ?? unsplash_by_jenis('wisata');
$bgMenginap = $heroBackgrounds['menginap'] ?? unsplash_by_jenis('homestay');
$bgUmkm = $heroBackgrounds['umkm'] ?? unsplash_by_jenis('umkm');
$bgCatering = $heroBackgrounds['catering'] ?? unsplash_by_jenis('catering');
$fiturReservasi = fitur_reservasi_aktif();
$fiturProduk = fitur_produk_aktif();
$defaultService = $fiturReservasi ? 'wisata' : ($fiturProduk ? 'umkm' : 'none');
?>

<!-- ==========================================================================
     HERO SECTION
     ========================================================================== -->
<section class="home-hero" id="homeHero" data-active="<?= esc($defaultService, 'attr') ?>"
  aria-label="Beranda Layanan Desa Papanbinangun">
  <div class="home-hero-bg" aria-hidden="true">
    <?php if ($fiturReservasi): ?>
      <div class="home-hero-photo is-active" data-bg="wisata"
        style="background-image:url('<?= esc($bgWisata, 'attr') ?>')"></div>
      <div class="home-hero-photo" data-bg="menginap" style="background-image:url('<?= esc($bgMenginap, 'attr') ?>')">
      </div>
    <?php endif; ?>
    <?php if ($fiturProduk): ?>
      <div class="home-hero-photo <?= !$fiturReservasi ? 'is-active' : '' ?>" data-bg="umkm"
        style="background-image:url('<?= esc($bgUmkm, 'attr') ?>')"></div>
      <div class="home-hero-photo" data-bg="catering" style="background-image:url('<?= esc($bgCatering, 'attr') ?>')">
      </div>
    <?php endif; ?>
    <div class="home-hero-scrim"></div>
    <div class="home-hero-glow"></div>
    <div class="home-hero-pattern"></div>
    <img class="home-hero-ornament home-hero-ornament--tl" src="<?= base_url('assets/images/hero-floral-corner.svg') ?>"
      alt="">
    <img class="home-hero-ornament home-hero-ornament--br" src="<?= base_url('assets/images/hero-floral-corner.svg') ?>"
      alt="">
  </div>

  <div class="container home-hero-grid">
    <div class="home-hero-copy">
      <div class="home-hero-badge">
        <i class="fa-solid fa-seedling"></i>
        <span>Desa Wisata &amp; Budaya <?= esc($brand) ?></span>
      </div>

      <h1 class="home-hero-title"><?= esc($tagline) ?></h1>

      <p class="home-hero-lead" id="heroLead">
        <?= $fiturReservasi
          ? 'Paket wisata desa — harga per orang, hitung estimasi sebelum pesan.'
          : 'Belanja produk UMKM &amp; sajian catering khas olahan warga desa.' ?>
      </p>

      <?php if ($fiturReservasi || $fiturProduk): ?>
        <!-- SERVICE SELECTION RAIL -->
        <div class="home-service-rail" role="tablist" aria-label="Pilih jenis layanan">
          <?php if ($fiturReservasi): ?>
            <button type="button" class="home-service-tab is-active" role="tab" aria-selected="true" data-service="wisata">
              <i class="fa-solid fa-mountain-sun"></i>
              <span class="home-service-tab-label">Paket Wisata</span>
            </button>
            <button type="button" class="home-service-tab" role="tab" aria-selected="false" data-service="menginap">
              <i class="fa-solid fa-campground"></i>
              <span class="home-service-tab-label">Homestay &amp; Camping</span>
            </button>
          <?php endif; ?>
          <?php if ($fiturProduk): ?>
            <button type="button" class="home-service-tab <?= !$fiturReservasi ? 'is-active' : '' ?>" role="tab"
              aria-selected="<?= !$fiturReservasi ? 'true' : 'false' ?>" data-service="umkm">
              <i class="fa-solid fa-store"></i>
              <span class="home-service-tab-label">Karya UMKM</span>
            </button>
            <button type="button" class="home-service-tab" role="tab" aria-selected="false" data-service="catering">
              <i class="fa-solid fa-utensils"></i>
              <span class="home-service-tab-label">Dapur Catering</span>
            </button>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- INTERACTIVE ESTIMATE CALCULATOR (WISATA / HOMESTAY) -->
      <div class="home-estimate" id="heroEstimate" data-mode="wisata" hidden>
        <div class="home-estimate-head">
          <p class="home-estimate-title" id="estimateTitle"><i class="fa-solid fa-calculator"></i> Hitung Estimasi Biaya
          </p>
          <p class="home-estimate-note" id="estimateNote">Pilih paket, lalu atur jumlah orang.</p>
        </div>

        <div class="home-estimate-fields" id="estimateFields">
          <div class="home-estimate-field home-estimate-field--paket">
            <label for="estimatePaket">Pilihan Paket</label>
            <select id="estimatePaket" class="home-estimate-select js-select2" data-placeholder="Pilih paket…"
              data-dropdown-parent=".home-estimate" data-minimum-results-for-search="8"></select>
          </div>

          <div class="home-estimate-row" id="estimateControls">
            <div class="home-estimate-stepper" id="estimateQtyWrap">
              <span class="home-estimate-stepper-label" id="estimateQtyLabel">Jumlah Orang</span>
              <div class="home-estimate-stepper-ctrl">
                <button type="button" class="home-estimate-step" data-step="-1" data-target="estimateQty"
                  aria-label="Kurangi">
                  <i class="fa-solid fa-minus"></i>
                </button>
                <input type="number" id="estimateQty" class="home-estimate-input" min="1" max="50" value="2"
                  inputmode="numeric">
                <button type="button" class="home-estimate-step" data-step="1" data-target="estimateQty"
                  aria-label="Tambah">
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
            </div>

            <div class="home-estimate-field home-estimate-field--dates" id="estimateNightsWrap" hidden>
              <label for="estimateDateRange">Jadwal Menginap</label>
              <input type="text" id="estimateDateRange" class="home-estimate-daterange"
                placeholder="Pilih check-in — check-out" readonly>
              <input type="hidden" id="estimateNights" value="2">
            </div>
          </div>
        </div>

        <div class="home-estimate-result">
          <div class="home-estimate-breakdown" id="estimateBreakdown">2 orang</div>
          <strong id="estimateTotal">Rp 0</strong>
        </div>
        <p id="estimateAvailMsg" class="availability-msg" aria-live="polite" hidden></p>
      </div>

      <!-- CTA ACTIONS -->
      <div class="home-hero-actions">
        <a class="btn btn-primary" id="heroPrimaryCta" href="<?= site_url('paket-wisata?jenis=wisata') ?>">
          <i class="fa-solid fa-arrow-right me-3"></i> Pesan Sekarang
        </a>
        <a class="btn btn-cream" href="<?= site_url('/') ?>#cek-status">
          <i class="fa-solid fa-receipt me-3"></i> Cek Status Pesanan
        </a>
      </div>
    </div>

    <!-- HERO CAROUSEL ASIDE -->
    <div class="home-hero-aside">
      <?php if (!empty($heroSlides)): ?>
        <div class="hero-carousel" id="heroCarousel" data-active-jenis="wisata">
          <div class="hero-carousel-viewport">
            <?php foreach ($heroSlides as $i => $slide): ?>
              <a class="hero-carousel-slide<?= $i === 0 ? ' is-active' : '' ?>" href="<?= esc($slide['url']) ?>"
                data-jenis="<?= esc($slide['jenis']) ?>" data-group="<?= esc($slide['group'] ?? $slide['jenis']) ?>"
                data-index="<?= $i ?>" <?= $i === 0 ? '' : 'aria-hidden="true" tabindex="-1"' ?>>
                <img src="<?= esc($slide['img']) ?>" alt="<?= esc($slide['nama']) ?>"
                  loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
                <span class="hero-carousel-caption">
                  <span class="hero-carousel-badge"><?= esc(label_jenis_paket($slide['jenis'])) ?></span>
                  <strong><?= esc($slide['nama']) ?></strong>
                  <small>
                    <?= format_rupiah($slide['harga']) ?>
                    <?= !empty($slide['satuan']) ? ' / ' . esc($slide['satuan']) : '' ?>
                  </small>
                </span>
              </a>
            <?php endforeach; ?>
          </div>
          <button type="button" class="hero-carousel-nav hero-carousel-nav--prev" aria-label="Slide sebelumnya">
            <i class="fa-solid fa-chevron-left"></i>
          </button>
          <button type="button" class="hero-carousel-nav hero-carousel-nav--next" aria-label="Slide berikutnya">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
          <div class="hero-carousel-dots" id="heroCarouselDots" aria-label="Navigasi carousel"></div>
        </div>
      <?php else: ?>
        <div class="home-hero-visual" aria-hidden="true">
          <img src="<?= base_url('assets/images/hero-floral-medallion.svg') ?>" alt="" class="home-hero-medallion">
        </div>
      <?php endif; ?>
    </div>
  </div>

  <a class="home-hero-scroll" href="#layanan-unggulan" aria-label="Gulir ke konten berikutnya">
    <span>Jelajahi Unggulan</span>
  </a>
</section>

<!-- ==========================================================================
     VILLAGE VALUE PILLARS (Pilar Nilai & Budaya Desa)
     ========================================================================== -->
<section class="section container home-pillars-section" id="layanan-unggulan">
  <div class="pillars-grid">
    <div class="pillar-card">
      <div class="pillar-icon-box">
        <i class="fa-solid fa-leaf"></i>
      </div>
      <h3 class="pillar-title">Karya Asli Warga Desa</h3>
      <p class="pillar-desc">
        Setiap kerajinan bambu, kain batik, dan olahan pangan diproduksi langsung oleh keluarga perajin lokal di
        Papanbinangun.
      </p>
    </div>

    <div class="pillar-card">
      <div class="pillar-icon-box">
        <i class="fa-solid fa-bowl-food"></i>
      </div>
      <h3 class="pillar-title">Cita Rasa Dapur Tradisi</h3>
      <p class="pillar-desc">
        Catering dan jamuan tradisional diolah higienis dari bahan panen segar sekitar lereng, siap disajikan untuk
        acara Anda.
      </p>
    </div>

    <div class="pillar-card">
      <div class="pillar-icon-box">
        <i class="fa-solid fa-shield-halved"></i>
      </div>
      <h3 class="pillar-title">Reservasi &amp; Belanja Aman</h3>
      <p class="pillar-desc">
        Didukung pembayaran digital terpercaya (Midtrans), verifikasi otomatis, serta layanan bantuan WhatsApp langsung
        dari pengelola desa.
      </p>
    </div>
  </div>
</section>

<!-- ==========================================================================
     FEATURED TOURISM / PACKAGES (Wisata & Homestay)
     ========================================================================== -->
<?php if ($fiturReservasi): ?>
  <section class="section container home-featured-section">
    <div class="section-heading-bar">
      <div>
        <span class="section-eyebrow"><i class="fa-solid fa-compass"></i> Paket Pilihan</span>
        <h2 class="section-title">Wisata &amp; Menginap di Desa</h2>
        <p class="section-subtitle">Nikmati udara sejuk lereng, kehangatan homestay pedesaan, dan petualangan camping
          asri.</p>
      </div>
      <a href="<?= site_url('paket-wisata') ?>" class="section-link-more">
        Lihat Semua Paket <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    <?php if (empty($paket)): ?>
      <div class="home-empty-notice">
        <i class="fa-solid fa-mountain-sun"></i>
        <p>Belum ada paket wisata dipublikasikan saat ini.</p>
      </div>
    <?php else: ?>
      <div class="card-grid home-cards-grid">
        <?php foreach ($paket as $p): ?>
          <?php
          $jenisPaket = $p['jenis'] ?? 'wisata';
          $badgeClass = match ($jenisPaket) {
            'homestay' => 'badge-homestay',
            'camping' => 'badge-camping',
            default => 'badge-wisata',
          };
          $avail = $ketersediaan[(int) $p['id']] ?? ['total' => 0, 'available' => 0, 'remaining' => null];
          ?>
          <article class="card home-product-card">
            <a href="<?= site_url('paket-wisata/' . $p['slug']) ?>" class="card-link-block">
              <div class="card-img-wrap">
                <img class="card-img" src="<?= esc(cover_url($p['gambar_cover'] ?? null, $jenisPaket)) ?>"
                  alt="<?= esc($p['nama']) ?>" loading="lazy">
                <div class="card-badges-top">
                  <span class="badge-jenis <?= $badgeClass ?>"><?= esc(label_jenis_paket($jenisPaket)) ?></span>
                  <?php if ((int) $avail['available'] > 0): ?>
                    <span class="badge badge-available"><i class="fa-solid fa-check"></i> Tersedia</span>
                  <?php else: ?>
                    <span class="badge badge-soldout"><i class="fa-solid fa-times"></i> Penuh</span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="card-body">
                <h3 class="card-title"><?= esc($p['nama']) ?></h3>
                <div class="price">
                  <?= format_rupiah($p['harga']) ?>
                  <small style="font-weight:400;color:var(--sepia)">/
                    <?= esc(satuan_label($jenisPaket, $p['satuan_harga'] ?? null)) ?></small>
                </div>
                <div class="card-action-cue">
                  <span>Pesan Sekarang</span>
                  <i class="fa-solid fa-arrow-right"></i>
                </div>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
<?php endif; ?>

<!-- ==========================================================================
     FEATURED UMKM & CATERING (Karya Pengrajin & Kuliner Desa)
     ========================================================================== -->
<?php if ($fiturProduk): ?>
  <section class="section container home-featured-section">
    <div class="section-heading-bar">
      <div>
        <span class="section-eyebrow"><i class="fa-solid fa-store"></i> Pasar Kerajinan &amp; Rasa</span>
        <h2 class="section-title text-left">Produk UMKM &amp; Catering Pilihan</h2>
        <p class="section-subtitle">Buah karya tangan pengrajin desa dan sajian catering tradisional khas Papanbinangun.
        </p>
      </div>
      <a href="<?= site_url('toko') ?>" class="section-link-more">
        Jelajahi Toko Wisata Binangun <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    <?php if (empty($produk)): ?>
      <div class="home-empty-notice">
        <i class="fa-solid fa-box-open"></i>
        <p>Belum ada produk dipublikasikan saat ini.</p>
      </div>
    <?php else: ?>
      <div class="card-grid home-cards-grid">
        <?php foreach ($produk as $pr): ?>
          <?php
          $jenisProduk = ($pr['jenis'] ?? 'umkm') === 'catering' ? 'catering' : 'umkm';
          $isCatering = $jenisProduk === 'catering';
          $stok = (int) ($pr['stok'] ?? 0);
          ?>
          <article class="card home-product-card <?= $stok <= 0 ? 'is-soldout' : '' ?>">
            <a href="<?= site_url('toko/' . $pr['slug']) ?>" class="card-link-block">
              <div class="card-img-wrap">
                <img class="card-img" src="<?= esc(cover_url($pr['gambar'] ?? null, $jenisProduk)) ?>"
                  alt="<?= esc($pr['nama']) ?>" loading="lazy">
                <div class="card-badges-top">
                  <span class="badge-jenis <?= $isCatering ? 'badge-catering' : 'badge-umkm' ?>">
                    <i class="<?= $isCatering ? 'fa-solid fa-utensils' : 'fa-solid fa-store' ?>"></i>
                    <?= $isCatering ? 'Catering' : 'UMKM' ?>
                  </span>
                  <?php if ($stok > 0): ?>
                    <span class="badge badge-stock-ok">Stok <?= $stok ?></span>
                  <?php else: ?>
                    <span class="badge badge-soldout">Habis</span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="card-body">
                <h3 class="card-title"><?= esc($pr['nama']) ?></h3>
                <div class="price">
                  <?= format_rupiah(harga_produk_akhir($pr['harga'])) ?>
                  <?php if ($isCatering): ?>
                    <small style="font-weight:400;color:var(--sepia)">/ porsi</small>
                  <?php endif; ?>
                </div>
                <div class="card-action-cue">
                  <span>Lihat Produk</span>
                  <i class="fa-solid fa-arrow-right"></i>
                </div>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
<?php endif; ?>

<!-- ==========================================================================
     ORDER / RESERVATION STATUS TRACKING (Cek Status Transaksi)
     ========================================================================== -->
<section class="section container" id="cek-status">
  <div class="status-tracking-card">
    <div class="tracking-icon-box">
      <i class="fa-solid fa-receipt"></i>
    </div>
    <span class="section-eyebrow" style="margin-bottom:0.4rem">Lacak Pesanan</span>
    <h2 class="status-card-title">Cek Status Transaksi &amp; Reservasi</h2>
    <p class="status-card-desc">
      Masukkan kode reservasi wisata atau kode order produk belanja Anda untuk melihat status pembayaran, konfirmasi,
      dan nomor resi pengiriman secara real-time.
    </p>

    <form method="get" class="status-tracking-form"
      onsubmit="event.preventDefault(); const k=this.kode.value.trim(); if(k) location.href='<?= site_url('status') ?>/'+encodeURIComponent(k);">
      <?php
      $placeholderKode = match (true) {
        $fiturReservasi && $fiturProduk => 'Contoh: RSV-202610... atau ORD-202610...',
        $fiturProduk => 'Contoh: ORD-202610-xxxx',
        default => 'Contoh: RSV-202610-xxxx',
      };
      ?>
      <div class="tracking-input-group">
        <div class="tracking-input-wrap">
          <i class="fa-solid fa-magnifying-glass tracking-input-icon"></i>
          <input class="form-control tracking-input" type="text" name="kode" id="kode"
            placeholder="<?= esc($placeholderKode) ?>" required autocomplete="off">
        </div>
        <button class="btn btn-primary tracking-btn" type="submit">
          <i class="fa-solid fa-arrow-right"></i> Cek Status
        </button>
      </div>
    </form>
  </div>
</section>

<!-- ==========================================================================
     CUSTOM STYLING FOR HOME (Tema Vintage Papanbinangun)
     ========================================================================== -->
<style>
  /* Hero Eyebrow Badge */
  .home-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.85rem;
    background: rgba(201, 166, 107, 0.15);
    border: 1px solid rgba(201, 166, 107, 0.35);
    border-radius: 20px;
    color: var(--gold, #c9a66b);
    font-size: 0.84rem;
    font-weight: 600;
    letter-spacing: 0.03em;
    margin-bottom: 0.85rem;
    animation: heroFadeUp 0.7s ease both;
  }

  /* Service Rail Tab Improvements */
  .home-service-rail {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    margin-bottom: 1.5rem;
  }

  .home-service-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    background: rgba(250, 247, 240, 0.08);
    border: 1px solid rgba(201, 166, 107, 0.35);
    color: var(--cream, #f4e9d8);
    cursor: pointer;
    transition: all 0.25s ease;
    font-family: var(--font-body, serif);
    font-size: 0.92rem;
    min-height: 42px;
  }

  .home-service-tab i {
    font-size: 0.85rem;
    opacity: 0.85;
  }

  .home-service-tab:hover {
    background: rgba(250, 247, 240, 0.16);
    border-color: var(--gold, #c9a66b);
    color: #ffffff;
  }

  .home-service-tab.is-active {
    background: var(--gold, #c9a66b);
    border-color: var(--gold, #c9a66b);
    color: var(--deep, #4a3222);
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(201, 166, 107, 0.3);
  }

  .home-service-tab.is-active i {
    opacity: 1;
  }

  /* Stepper adjustments */
  .home-estimate-stepper-ctrl button {
    min-width: 36px;
    min-height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Carousel Nav Buttons */
  .hero-carousel-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(43, 33, 24, 0.7);
    color: var(--cream, #f4e9d8);
    border: 1px solid rgba(201, 166, 107, 0.3);
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .hero-carousel-nav:hover {
    background: var(--gold, #c9a66b);
    color: var(--deep, #4a3222);
  }

  /* ==========================================================================
   VILLAGE PILLARS SECTION
   ========================================================================== */
  .home-pillars-section {
    padding: 3rem 0 1.5rem;
  }

  .pillars-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }

  .pillar-card {
    background: var(--white, #fffbf5);
    border: 1px solid var(--cream-dark, #e8d9c0);
    border-radius: 12px;
    padding: 1.75rem 1.5rem;
    box-shadow: 0 4px 16px var(--shadow, rgba(74, 50, 34, 0.05));
    transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
  }

  .pillar-card:hover {
    transform: translateY(-4px);
    border-color: var(--gold, #c9a66b);
    box-shadow: 0 8px 24px var(--shadow, rgba(74, 50, 34, 0.1));
  }

  .pillar-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    background: rgba(201, 166, 107, 0.15);
    color: var(--gold, #c9a66b);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.45rem;
    margin-bottom: 1.15rem;
  }

  .pillar-title {
    font-family: var(--font-heading, serif);
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--deep, #4a3222);
    margin: 0 0 0.5rem;
  }

  .pillar-desc {
    color: var(--sepia, #8b5e3c);
    font-size: 0.94rem;
    line-height: 1.6;
    margin: 0;
  }

  /* ==========================================================================
   FEATURED SECTIONS & CARDS
   ========================================================================== */
  .home-featured-section {
    padding-top: 2rem;
    padding-bottom: 2.5rem;
  }

  .section-heading-bar {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding-bottom: 0.85rem;
    border-bottom: 1px solid var(--cream-dark, #e8d9c0);
  }

  .section-eyebrow {
    display: inline-block;
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    color: var(--terracotta, #c1502e);
    margin-bottom: 0.25rem;
    text-transform: uppercase;
  }

  .section-heading-bar .section-title {
    font-family: var(--font-heading, serif);
    font-size: clamp(1.65rem, 3.2vw, 2.3rem);
    font-weight: 700;
    color: var(--deep, #4a3222);
    margin: 0 0 0.35rem;
  }

  .section-subtitle {
    color: var(--sepia, #8b5e3c);
    font-size: 0.96rem;
    margin: 0;
  }

  .section-link-more {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--terracotta, #c1502e);
    white-space: nowrap;
    padding: 0.4rem 0.85rem;
    border-radius: 6px;
    background: rgba(193, 80, 46, 0.06);
    border: 1px solid rgba(193, 80, 46, 0.2);
    transition: all 0.2s ease;
  }

  .section-link-more:hover {
    background: var(--terracotta, #c1502e);
    color: #ffffff;
    transform: translateX(3px);
  }

  /* Product & Package Cards */
  .home-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.5rem;
  }

  .home-product-card {
    background: var(--white, #fffbf5);
    border: 1px solid var(--cream-dark, #e8d9c0);
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    position: relative;
    display: flex;
    flex-direction: column;
  }

  .home-product-card:hover {
    transform: translateY(-4px);
    border-color: var(--gold, #c9a66b);
    box-shadow: 0 10px 25px var(--shadow, rgba(74, 50, 34, 0.12));
  }

  .card-link-block {
    color: inherit;
    display: flex;
    flex-direction: column;
    height: 100%;
    text-decoration: none;
  }

  .card-img-wrap {
    position: relative;
    aspect-ratio: 4 / 3;
    width: 100%;
    overflow: hidden;
    background: var(--cream, #f4e9d8);
  }

  .card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: sepia(0.08) contrast(1.02);
    transition: transform 0.4s ease;
  }

  .home-product-card:hover .card-img-wrap img {
    transform: scale(1.04);
  }

  .card-badges-top {
    position: absolute;
    top: 0.65rem;
    left: 0.65rem;
    right: 0.65rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.35rem;
    pointer-events: none;
  }

  .badge-stock-ok {
    background: rgba(122, 132, 80, 0.92);
    color: #fff;
    font-size: 0.72rem;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-weight: 600;
    margin-left: auto;
  }

  .card-title {
    font-family: var(--font-heading, serif);
    font-size: 1.2rem;
    font-weight: 600;
    line-height: 1.35;
    color: var(--deep, #4a3222);
    margin: 0 0 0.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 2.7em;
  }

  .home-product-card .price {
    font-family: var(--font-heading, serif);
    font-size: 1.22rem;
    font-weight: 700;
    color: var(--terracotta, #c1502e);
    margin-top: auto;
  }

  .card-action-cue {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 0.85rem;
    padding-top: 0.65rem;
    border-top: 1px dashed rgba(232, 217, 192, 0.7);
    font-size: 0.84rem;
    font-weight: 600;
    color: var(--deep, #4a3222);
    transition: color 0.2s;
  }

  .home-product-card:hover .card-action-cue {
    color: var(--terracotta, #c1502e);
  }

  .home-empty-notice {
    text-align: center;
    padding: 3rem 1.5rem;
    background: var(--white, #fffbf5);
    border: 1px dashed var(--cream-dark, #e8d9c0);
    border-radius: 10px;
    color: var(--sepia, #8b5e3c);
  }

  .home-empty-notice i {
    font-size: 2.5rem;
    color: var(--gold, #c9a66b);
    margin-bottom: 0.75rem;
    display: block;
  }

  /* ==========================================================================
   STATUS TRACKING CARD
   ========================================================================== */
  .status-tracking-card {
    background: var(--white, #fffbf5);
    border: 1px solid var(--gold, #c9a66b);
    border-radius: 14px;
    box-shadow: 0 6px 25px var(--shadow, rgba(74, 50, 34, 0.08));
    padding: 3rem 2rem;
    max-width: 680px;
    margin: 1.5rem auto 3rem;
    text-align: center;
    position: relative;
  }

  .tracking-icon-box {
    width: 62px;
    height: 62px;
    border-radius: 50%;
    background: rgba(193, 80, 46, 0.1);
    color: var(--terracotta, #c1502e);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    margin: 0 auto 1.15rem;
  }

  .status-card-title {
    font-family: var(--font-heading, serif);
    font-size: clamp(1.6rem, 3vw, 2.1rem);
    font-weight: 700;
    color: var(--deep, #4a3222);
    margin: 0 0 0.65rem;
  }

  .status-card-desc {
    color: var(--sepia, #8b5e3c);
    font-size: 0.95rem;
    line-height: 1.6;
    max-width: 520px;
    margin: 0 auto 1.75rem;
  }

  .status-tracking-form {
    max-width: 520px;
    margin: 0 auto;
  }

  .tracking-input-group {
    display: flex;
    gap: 0.6rem;
  }

  .tracking-input-wrap {
    position: relative;
    flex: 1;
  }

  .tracking-input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--sepia, #8b5e3c);
    opacity: 0.65;
    font-size: 0.95rem;
  }

  .tracking-input {
    width: 100%;
    height: 48px;
    padding: 0 1rem 0 2.5rem;
    border-radius: 8px;
    border: 1px solid var(--cream-dark, #e8d9c0);
    background: var(--cream, #f4e9d8);
    font-family: var(--font-body, serif);
    font-size: 0.95rem;
    color: var(--deep, #4a3222);
  }

  .tracking-input:focus {
    background: #ffffff;
    border-color: var(--terracotta, #c1502e);
    box-shadow: 0 0 0 3px rgba(193, 80, 46, 0.2);
    outline: none;
  }

  .tracking-btn {
    height: 48px;
    padding: 0 1.5rem;
    border-radius: 8px;
    white-space: nowrap;
    font-weight: 600;
    font-size: 0.95rem;
  }

  /* ==========================================================================
   MOBILE & RESPONSIVE BREAKPOINTS
   ========================================================================== */
  @media (max-width: 900px) {
    .pillars-grid {
      grid-template-columns: 1fr;
      gap: 1rem;
    }
  }

  @media (max-width: 640px) {
    .home-hero-badge {
      font-size: 0.78rem;
      padding: 0.3rem 0.75rem;
    }

    .home-service-rail {
      gap: 0.4rem;
    }

    .home-service-tab {
      font-size: 0.85rem;
      padding: 0.4rem 0.8rem;
      min-height: 38px;
    }

    .home-hero-actions {
      flex-direction: column;
      width: 100%;
    }

    .home-hero-actions .btn {
      width: 100%;
      justify-content: center;
    }

    .section-heading-bar {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.75rem;
    }

    /* Mobile 2-column Grid */
    .home-cards-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 0.75rem;
    }

    .home-product-card .card-body {
      padding: 0.75rem 0.75rem 0.85rem;
    }

    .card-title {
      font-size: 0.98rem;
      margin-bottom: 0.35rem;
      height: 2.7em;
    }

    .home-product-card .price {
      font-size: 1.05rem;
    }

    .card-action-cue {
      font-size: 0.76rem;
      padding-top: 0.45rem;
      margin-top: 0.55rem;
    }

    .status-tracking-card {
      padding: 2rem 1.25rem;
    }

    .tracking-input-group {
      flex-direction: column;
    }

    .tracking-btn {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 360px) {
    .home-cards-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  (() => {
    const estimateData = {
      wisata: <?= json_encode(array_values($estimateWisata), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
      menginap: <?= json_encode(array_values($estimateMenginap), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
    };

    const services = {
      <?php if ($fiturReservasi): ?>
        wisata: {
          lead: 'Paket wisata desa — harga per orang, hitung estimasi sebelum pesan.',
          cta: 'Lihat Paket Wisata',
          href: '<?= site_url('paket-wisata?jenis=wisata') ?>',
          estimate: true,
        },
        menginap: {
          lead: 'Homestay & camping ground — harga per malam. Hitung estimasi menginap Anda.',
          cta: 'Lihat Homestay & Camping',
          href: '<?= site_url('paket-wisata?jenis=menginap') ?>',
          estimate: true,
        },
      <?php endif; ?>
      <?php if ($fiturProduk): ?>
        umkm: {
          lead: 'Karya UMKM desa — belanja online dengan pengiriman ekspedisi.',
          cta: 'Belanja UMKM',
          href: '<?= site_url('toko?jenis=umkm') ?>',
          estimate: false,
        },
        catering: {
          lead: 'Catering acara — ambil di tempat atau antar lokal sesuai zona.',
          cta: 'Pesan Catering',
          href: '<?= site_url('toko?jenis=catering') ?>',
          estimate: false,
        },
      <?php endif; ?>
    };

    const hero = document.getElementById('homeHero');
    const lead = document.getElementById('heroLead');
    const primary = document.getElementById('heroPrimaryCta');
    const tabs = Array.from(document.querySelectorAll('.home-service-tab'));
    const photos = Array.from(document.querySelectorAll('.home-hero-photo'));
    const estimateBox = document.getElementById('heroEstimate');
    const estimateFields = document.getElementById('estimateFields');
    const estimateSelect = document.getElementById('estimatePaket');
    const estimateQty = document.getElementById('estimateQty');
    const estimateQtyLabel = document.getElementById('estimateQtyLabel');
    const estimateNightsWrap = document.getElementById('estimateNightsWrap');
    const estimateNights = document.getElementById('estimateNights');
    const estimateDateRange = document.getElementById('estimateDateRange');
    const estimateTotal = document.getElementById('estimateTotal');
    const estimateNote = document.getElementById('estimateNote');
    const estimateBreakdown = document.getElementById('estimateBreakdown');
    const estimateAvailMsg = document.getElementById('estimateAvailMsg');
    const { formatRupiah: rupiah, nightsBetween } = window.AppUtils;
    let availTimer = null;
    const carousel = document.getElementById('heroCarousel');
    const slides = carousel ? Array.from(carousel.querySelectorAll('.hero-carousel-slide')) : [];
    const dotsWrap = document.getElementById('heroCarouselDots');
    let index = 0;
    let timer = null;
    let activeJenis = 'wisata';
    let rangeFp = null;

    function defaultRangeDates() {
      const start = new Date();
      start.setHours(0, 0, 0, 0);
      const end = new Date(start);
      end.setDate(end.getDate() + 1);
      return [start, end];
    }

    function syncNightsFromFp() {
      if (!estimateNights || !rangeFp) return;
      const dates = rangeFp.selectedDates || [];
      const nights = dates.length === 2 ? nightsBetween(dates[0], dates[1]) : 0;
      estimateNights.value = String(Math.max(0, nights));
    }

    function initRangePicker() {
      if (!estimateDateRange || typeof flatpickr === 'undefined' || rangeFp) return;
      const locale = flatpickr.l10ns && flatpickr.l10ns.id ? flatpickr.l10ns.id : 'default';
      const defaults = defaultRangeDates();
      rangeFp = flatpickr(estimateDateRange, {
        mode: 'range',
        locale,
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'j M',
        altInputClass: 'home-estimate-daterange',
        allowInput: false,
        disableMobile: true,
        minDate: 'today',
        defaultDate: defaults,
        showMonths: window.matchMedia('(min-width: 720px)').matches ? 2 : 1,
        onChange(selectedDates) {
          if (selectedDates.length === 2) {
            syncNightsFromFp();
            calcEstimate();
            syncEstimateCta();
          } else if (selectedDates.length < 2) {
            if (estimateNights) estimateNights.value = '0';
            calcEstimate();
            syncEstimateCta();
          }
        },
      });
      syncNightsFromFp();
    }

    function setMenginapMode(on) {
      if (estimateNightsWrap) {
        estimateNightsWrap.hidden = !on;
        estimateNightsWrap.setAttribute('aria-hidden', on ? 'false' : 'true');
      }
      if (estimateFields) estimateFields.classList.toggle('is-menginap', !!on);
      if (estimateNote) {
        estimateNote.textContent = on
          ? 'Pilih tanggal check-in & check-out, lalu atur jumlah rumah.'
          : 'Pilih paket, lalu atur jumlah orang.';
      }
      if (estimateQty && !estimateQty.dataset.touched) {
        estimateQty.value = on ? '1' : '1';
      }
      if (on) {
        initRangePicker();
        if (rangeFp && (!rangeFp.selectedDates || rangeFp.selectedDates.length < 2)) {
          rangeFp.setDate(defaultRangeDates(), true);
        } else {
          syncNightsFromFp();
        }
      }
    }

    function fillEstimate(mode) {
      const list = estimateData[mode] || [];
      if (!estimateSelect) return;
      window.Select2Init?.destroy(estimateSelect);
      estimateSelect.innerHTML = '';
      if (!list.length) {
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = 'Belum ada paket';
        estimateSelect.appendChild(opt);
        if (estimateTotal) estimateTotal.textContent = 'Rp 0';
        if (estimateBreakdown) estimateBreakdown.textContent = '—';
        setMenginapMode(mode === 'menginap');
        window.Select2Init?.init(estimateSelect);
        return;
      }
      list.forEach((item, i) => {
        const opt = document.createElement('option');
        opt.value = String(item.id);
        opt.dataset.harga = String(item.harga);
        opt.dataset.slug = item.slug || '';
        opt.dataset.satuan = item.satuan || 'per_orang';
        opt.dataset.label = item.label || 'per orang';
        const shortLabel = mode === 'menginap' ? '/ malam' : '/ orang';
        opt.textContent = item.nama.replace(/ \((Homestay|Camping)\)$/, '') + ' · ' + rupiah(item.harga) + ' ' + shortLabel;
        if (i === 0) opt.selected = true;
        estimateSelect.appendChild(opt);
      });
      if (estimateQty) delete estimateQty.dataset.touched;
      setMenginapMode(mode === 'menginap');
      window.Select2Init?.init(estimateSelect);
      calcEstimate();
    }

    function setAvail(msg, ok, busy) {
      if (!estimateAvailMsg) return;
      if (busy) {
        estimateAvailMsg.hidden = false;
        estimateAvailMsg.className = 'availability-msg';
        estimateAvailMsg.textContent = msg;
        return;
      }
      estimateAvailMsg.hidden = !msg;
      estimateAvailMsg.textContent = msg || '';
      estimateAvailMsg.className = 'availability-msg' + (ok ? ' ok' : ' err');
    }

    function checkMenginapAvailability() {
      if (activeJenis !== 'menginap' || !rangeFp) {
        setAvail('', false);
        return;
      }
      const dates = rangeFp.selectedDates || [];
      const opt = estimateSelect?.selectedOptions?.[0];
      if (!opt || !opt.value || dates.length !== 2) {
        setAvail('', false);
        return;
      }
      const fmt = (d) => d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
      const checkIn = fmt(dates[0]);
      const checkOut = fmt(dates[1]);
      setAvail('Memeriksa ketersediaan…', false, true);
      clearTimeout(availTimer);
      availTimer = setTimeout(() => {
        fetch('<?= site_url('api/homestay-availability') ?>'
          + '?paket_id=' + encodeURIComponent(opt.value)
          + '&check_in=' + encodeURIComponent(checkIn)
          + '&check_out=' + encodeURIComponent(checkOut))
          .then((res) => res.json())
          .then((data) => setAvail(data.message || '', !!data.ok))
          .catch(() => setAvail('Gagal cek ketersediaan', false));
      }, 400);
    }

    function calcEstimate() {
      if (!estimateSelect || !estimateQty || !estimateTotal) return;
      const opt = estimateSelect.selectedOptions[0];
      const harga = opt ? Number(opt.dataset.harga || 0) : 0;
      const qty = Math.max(1, Number(estimateQty.value || 1));
      estimateQty.value = String(qty);

      const isMenginap = activeJenis === 'menginap';
      let total = harga * qty;
      let breakdown = qty + ' orang · ' + rupiah(harga) + '/orang';

      if (isMenginap && !estimateNightsWrap?.hidden) {
        const nights = Math.max(0, Number(estimateNights?.value || 0));
        if (nights < 1) {
          total = 0;
          breakdown = 'pilih tanggal menginap';
        } else {
          total = harga * qty * nights;
          breakdown = nights + ' malam · ' + rupiah(harga) + '/malam';
        }
      }

      estimateTotal.textContent = total > 0 ? rupiah(total) : (isMenginap ? '—' : rupiah(0));
      if (estimateBreakdown) estimateBreakdown.textContent = breakdown;
      if (isMenginap) checkMenginapAvailability();
      else if (estimateAvailMsg) setAvail('', false);
    }

    function nudge(inputId, delta) {
      const el = document.getElementById(inputId);
      if (!el || inputId === 'estimateNights') return;
      const min = Number(el.min || 1);
      const max = Number(el.max || 99);
      const next = Math.min(max, Math.max(min, Number(el.value || min) + delta));
      el.value = String(next);
      el.dataset.touched = '1';
      calcEstimate();
      syncEstimateCta();
    }

    function syncEstimateCta() {
      if (!estimateSelect || !primary) return;
      const opt = estimateSelect.selectedOptions[0];
      const slug = opt?.dataset?.slug;
      if (slug && (activeJenis === 'wisata' || activeJenis === 'menginap')) {
        const params = new URLSearchParams();
        params.set('paket_id', opt.value);
        params.set('qty', estimateQty?.value || '1');

        if (activeJenis === 'menginap' && !estimateNightsWrap?.hidden) {
          const dates = rangeFp?.selectedDates || [];
          if (dates.length === 2) {
            const fmt = (d) => d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
            params.set('check_in', fmt(dates[0]));
            params.set('check_out', fmt(dates[1]));
            params.set('nights', estimateNights?.value || '0');
          }
        }

        primary.setAttribute('href', '<?= site_url('paket-wisata') ?>/' + encodeURIComponent(slug) + '?' + params.toString());
        primary.textContent = 'Pesan paket ini';
      }
    }

    function visibleSlides() {
      const filtered = slides.filter((s) => (s.dataset.group || s.dataset.jenis) === activeJenis);
      return filtered.length ? filtered : slides;
    }

    function renderDots(list) {
      if (!dotsWrap) return;
      dotsWrap.innerHTML = '';
      list.forEach((slide, i) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'hero-carousel-dot' + (i === 0 ? ' is-active' : '');
        btn.setAttribute('aria-label', 'Slide ' + (i + 1));
        btn.addEventListener('click', () => goTo(i, true));
        dotsWrap.appendChild(btn);
      });
    }

    function showSlide(list, i) {
      slides.forEach((s) => {
        s.classList.remove('is-active');
        s.setAttribute('aria-hidden', 'true');
        s.setAttribute('tabindex', '-1');
      });
      const slide = list[i];
      if (!slide) return;
      slide.classList.add('is-active');
      slide.removeAttribute('aria-hidden');
      slide.removeAttribute('tabindex');
      index = i;
      if (dotsWrap) {
        Array.from(dotsWrap.children).forEach((d, di) => d.classList.toggle('is-active', di === i));
      }
    }

    function goTo(i, user) {
      const list = visibleSlides();
      if (!list.length) return;
      const next = ((i % list.length) + list.length) % list.length;
      showSlide(list, next);
      if (user) restart();
    }

    function next() { goTo(index + 1, false); }

    function restart() {
      clearInterval(timer);
      if (visibleSlides().length > 1) {
        timer = setInterval(next, 4500);
      }
    }

    function activate(key, tab) {
      const data = services[key];
      if (!data) return;
      activeJenis = key;

      tabs.forEach((t) => {
        const on = t === tab;
        t.classList.toggle('is-active', on);
        t.setAttribute('aria-selected', on ? 'true' : 'false');
      });

      photos.forEach((p) => p.classList.toggle('is-active', p.dataset.bg === key));

      lead.classList.remove('is-swap');
      void lead.offsetWidth;
      lead.classList.add('is-swap');
      lead.textContent = data.lead;
      primary.textContent = data.cta;
      primary.setAttribute('href', data.href);
      hero?.setAttribute('data-active', key);
      carousel?.setAttribute('data-active-jenis', key);

      if (estimateBox) {
        if (data.estimate) {
          estimateBox.hidden = false;
          estimateBox.dataset.mode = key;
          estimateBox.classList.remove('is-swap');
          void estimateBox.offsetWidth;
          estimateBox.classList.add('is-swap');
          fillEstimate(key);
          syncEstimateCta();
        } else {
          estimateBox.hidden = true;
        }
      }

      const list = visibleSlides();
      renderDots(list);
      showSlide(list, 0);
      restart();
    }

    tabs.forEach((tab) => {
      tab.addEventListener('click', () => activate(tab.dataset.service, tab));
    });

    estimateSelect?.addEventListener('change', () => {
      calcEstimate();
      syncEstimateCta();
    });
    estimateQty?.addEventListener('input', () => {
      if (estimateQty) estimateQty.dataset.touched = '1';
      calcEstimate();
      syncEstimateCta();
    });
    document.querySelectorAll('.home-estimate-step').forEach((btn) => {
      btn.addEventListener('click', () => {
        nudge(btn.dataset.target, Number(btn.dataset.step || 0));
      });
    });

    carousel?.querySelector('.hero-carousel-nav--next')?.addEventListener('click', () => goTo(index + 1, true));
    carousel?.querySelector('.hero-carousel-nav--prev')?.addEventListener('click', () => goTo(index - 1, true));

    const first = tabs.find((t) => t.classList.contains('is-active')) || tabs[0];
    if (first) activate(first.dataset.service, first);
  })();
</script>
<?= $this->endSection() ?>