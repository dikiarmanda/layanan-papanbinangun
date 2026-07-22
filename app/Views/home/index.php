<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$site = pengaturan();
$brand = $site['nama_desa'] ?: 'Wisata Binangun';
$tagline = $site['tagline'] ?: 'Jelajahi Desa, Bawa Pulang Ceritanya';
$heroSlides = $heroSlides ?? [];
?>

<section class="home-hero" id="homeHero" aria-label="Beranda layanan">
  <div class="home-hero-bg" aria-hidden="true">
    <div class="home-hero-glow"></div>
    <div class="home-hero-pattern"></div>
    <img class="home-hero-ornament home-hero-ornament--tl" src="<?= base_url('assets/images/hero-floral-corner.svg') ?>" alt="">
    <img class="home-hero-ornament home-hero-ornament--br" src="<?= base_url('assets/images/hero-floral-corner.svg') ?>" alt="">
  </div>

  <div class="container home-hero-grid">
    <div class="home-hero-copy">
      <p class="home-hero-brand"><?= esc($brand) ?></p>
      <h1 class="home-hero-title"><?= esc($tagline) ?></h1>
      <p class="home-hero-lead" id="heroLead">Pesan wisata, homestay, UMKM, dan catering desa dalam satu tempat.</p>

      <div class="home-service-rail" role="tablist" aria-label="Pilih jenis layanan">
        <button type="button" class="home-service-tab is-active" role="tab" aria-selected="true" data-service="wisata">
          <span class="home-service-tab-label">Wisata</span>
        </button>
        <button type="button" class="home-service-tab" role="tab" aria-selected="false" data-service="homestay">
          <span class="home-service-tab-label">Homestay</span>
        </button>
        <button type="button" class="home-service-tab" role="tab" aria-selected="false" data-service="umkm">
          <span class="home-service-tab-label">UMKM</span>
        </button>
        <button type="button" class="home-service-tab" role="tab" aria-selected="false" data-service="catering">
          <span class="home-service-tab-label">Catering</span>
        </button>
      </div>

      <div class="home-hero-actions">
        <a class="btn btn-primary" id="heroPrimaryCta" href="<?= site_url('paket-wisata?jenis=wisata') ?>">Lihat Paket Wisata</a>
        <a class="btn btn-cream" href="<?= site_url('/') ?>#cek-status">Cek Status</a>
      </div>
    </div>

    <div class="home-hero-aside">
      <?php if (! empty($heroSlides)): ?>
        <div class="hero-carousel" id="heroCarousel" data-active-jenis="wisata">
          <div class="hero-carousel-viewport">
            <?php foreach ($heroSlides as $i => $slide): ?>
              <a
                class="hero-carousel-slide<?= $i === 0 ? ' is-active' : '' ?>"
                href="<?= esc($slide['url']) ?>"
                data-jenis="<?= esc($slide['jenis']) ?>"
                data-index="<?= $i ?>"
                <?= $i === 0 ? '' : 'aria-hidden="true" tabindex="-1"' ?>
              >
                <img src="<?= esc($slide['img']) ?>" alt="<?= esc($slide['nama']) ?>" loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
                <span class="hero-carousel-caption">
                  <span class="hero-carousel-badge"><?= esc(ucfirst($slide['jenis'])) ?></span>
                  <strong><?= esc($slide['nama']) ?></strong>
                  <small>
                    <?= format_rupiah($slide['harga']) ?>
                    <?= ! empty($slide['satuan']) ? ' / ' . esc($slide['satuan']) : '' ?>
                  </small>
                </span>
              </a>
            <?php endforeach; ?>
          </div>
          <button type="button" class="hero-carousel-nav hero-carousel-nav--prev" aria-label="Slide sebelumnya">‹</button>
          <button type="button" class="hero-carousel-nav hero-carousel-nav--next" aria-label="Slide berikutnya">›</button>
          <div class="hero-carousel-dots" id="heroCarouselDots" aria-label="Navigasi carousel"></div>
        </div>
      <?php else: ?>
        <div class="home-hero-visual" aria-hidden="true">
          <img src="<?= base_url('assets/images/hero-floral-medallion.svg') ?>" alt="" class="home-hero-medallion">
        </div>
      <?php endif; ?>
    </div>
  </div>

  <a class="home-hero-scroll" href="#paket-unggulan" aria-label="Gulir ke konten berikutnya">
    <span>Lihat unggulan</span>
  </a>
</section>

<section class="section container" id="paket-unggulan">
  <h2 class="section-title">Paket Unggulan</h2>
  <?php if (empty($paket)): ?>
    <p class="text-center" style="color:var(--sepia)">Belum ada paket dipublikasikan.</p>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($paket as $p): ?>
        <a class="card" href="<?= site_url('paket-wisata/' . $p['slug']) ?>">
          <?php if (!empty($p['gambar_cover'])): ?>
            <img class="card-img" src="<?= media_url($p['gambar_cover']) ?>" alt="<?= esc($p['nama']) ?>">
          <?php else: ?>
            <div class="card-img" style="display:flex;align-items:center;justify-content:center;color:var(--sepia)">Paket
            </div>
          <?php endif; ?>
          <div class="card-body">
            <h3><?= esc($p['nama']) ?></h3>
            <span class="badge-jenis <?= ($p['jenis'] ?? '') === 'homestay' ? 'badge-homestay' : 'badge-wisata' ?>">
              <?= ($p['jenis'] ?? '') === 'homestay' ? 'Homestay' : 'Wisata' ?>
            </span>
            <div class="price"><?= format_rupiah($p['harga']) ?>
              <small style="font-weight:400;color:var(--sepia)">/
                <?= ($p['jenis'] ?? '') === 'homestay' ? 'malam' : 'orang' ?></small>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<section class="section container">
  <h2 class="section-title">Produk UMKM Unggulan</h2>
  <?php if (empty($produk)): ?>
    <p class="text-center" style="color:var(--sepia)">Belum ada produk dipublikasikan.</p>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($produk as $pr): ?>
        <a class="card" href="<?= site_url('toko/' . $pr['slug']) ?>">
          <?php if (!empty($pr['gambar'])): ?>
            <img class="card-img" src="<?= media_url($pr['gambar']) ?>" alt="<?= esc($pr['nama']) ?>">
          <?php else: ?>
            <div class="card-img" style="display:flex;align-items:center;justify-content:center;color:var(--sepia)">Produk
            </div>
          <?php endif; ?>
          <div class="card-body">
            <h3><?= esc($pr['nama']) ?></h3>
            <span class="badge-jenis <?= ($pr['jenis'] ?? '') === 'catering' ? 'badge-catering' : 'badge-umkm' ?>">
              <?= ($pr['jenis'] ?? '') === 'catering' ? 'Catering' : 'UMKM' ?>
            </span>
            <div class="price"><?= format_rupiah($pr['harga']) ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<section class="section container" id="cek-status">
  <h2 class="section-title">Cek Status Transaksi</h2>
  <form method="get"
    onsubmit="event.preventDefault(); const k=this.kode.value.trim(); if(k) location.href='<?= site_url('status') ?>/'+encodeURIComponent(k);"
    style="max-width:480px;margin:0 auto">
    <div class="form-group">
      <label for="kode">Kode transaksi (RSV-… atau ORD-…)</label>
      <input class="form-control" type="text" name="kode" id="kode" placeholder="RSV-xxxx atau ORD-xxxx" required>
    </div>
    <button class="btn btn-primary" type="submit" style="width:100%">Cek Status</button>
  </form>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(() => {
  const services = {
    wisata: {
      lead: 'Paket wisata desa — harga per orang, pilih jadwal yang tersedia.',
      cta: 'Lihat Paket Wisata',
      href: '<?= site_url('paket-wisata?jenis=wisata') ?>',
    },
    homestay: {
      lead: 'Homestay warga — harga per malam, atur check-in & check-out.',
      cta: 'Lihat Homestay',
      href: '<?= site_url('paket-wisata?jenis=homestay') ?>',
    },
    umkm: {
      lead: 'Karya UMKM desa — belanja online dengan pengiriman ekspedisi.',
      cta: 'Belanja UMKM',
      href: '<?= site_url('toko?jenis=umkm') ?>',
    },
    catering: {
      lead: 'Catering acara — ambil di tempat atau antar lokal sesuai zona.',
      cta: 'Pesan Catering',
      href: '<?= site_url('toko?jenis=catering') ?>',
    },
  };

  const hero = document.getElementById('homeHero');
  const lead = document.getElementById('heroLead');
  const primary = document.getElementById('heroPrimaryCta');
  const tabs = Array.from(document.querySelectorAll('.home-service-tab'));
  const carousel = document.getElementById('heroCarousel');
  const slides = carousel ? Array.from(carousel.querySelectorAll('.hero-carousel-slide')) : [];
  const dotsWrap = document.getElementById('heroCarouselDots');
  let index = 0;
  let timer = null;
  let activeJenis = 'wisata';

  function visibleSlides() {
    const filtered = slides.filter((s) => s.dataset.jenis === activeJenis);
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
  function prev() { goTo(index - 1, false); }

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

    lead.classList.remove('is-swap');
    void lead.offsetWidth;
    lead.classList.add('is-swap');
    lead.textContent = data.lead;
    primary.textContent = data.cta;
    primary.setAttribute('href', data.href);
    hero?.setAttribute('data-active', key);
    carousel?.setAttribute('data-active-jenis', key);

    const list = visibleSlides();
    renderDots(list);
    showSlide(list, 0);
    restart();
  }

  tabs.forEach((tab) => {
    tab.addEventListener('click', () => activate(tab.dataset.service, tab));
  });

  carousel?.querySelector('.hero-carousel-nav--next')?.addEventListener('click', () => goTo(index + 1, true));
  carousel?.querySelector('.hero-carousel-nav--prev')?.addEventListener('click', () => goTo(index - 1, true));

  const first = tabs.find((t) => t.classList.contains('is-active')) || tabs[0];
  if (first) activate(first.dataset.service, first);
})();
</script>
<?= $this->endSection() ?>