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
$fiturProduk = fitur_produk_aktif();
?>

<section class="home-hero" id="homeHero" data-active="wisata" aria-label="Beranda layanan">
  <div class="home-hero-bg" aria-hidden="true">
    <div class="home-hero-photo is-active" data-bg="wisata"
      style="background-image:url('<?= esc($bgWisata, 'attr') ?>')"></div>
    <div class="home-hero-photo" data-bg="menginap" style="background-image:url('<?= esc($bgMenginap, 'attr') ?>')">
    </div>
    <?php if ($fiturProduk): ?>
      <div class="home-hero-photo" data-bg="umkm" style="background-image:url('<?= esc($bgUmkm, 'attr') ?>')"></div>
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
      <p class="home-hero-brand"><?= esc($brand) ?></p>
      <h1 class="home-hero-title"><?= esc($tagline) ?></h1>
      <p class="home-hero-lead" id="heroLead">Paket wisata desa — harga per orang, hitung estimasi sebelum pesan.</p>

      <div class="home-service-rail" role="tablist" aria-label="Pilih jenis layanan">
        <button type="button" class="home-service-tab is-active" role="tab" aria-selected="true" data-service="wisata">
          <span class="home-service-tab-label">Paket Wisata</span>
        </button>
        <button type="button" class="home-service-tab" role="tab" aria-selected="false" data-service="menginap">
          <span class="home-service-tab-label">Homestay &amp; Camping</span>
        </button>
        <?php if ($fiturProduk): ?>
          <button type="button" class="home-service-tab" role="tab" aria-selected="false" data-service="umkm">
            <span class="home-service-tab-label">UMKM</span>
          </button>
          <button type="button" class="home-service-tab" role="tab" aria-selected="false" data-service="catering">
            <span class="home-service-tab-label">Catering</span>
          </button>
        <?php endif; ?>
      </div>

      <div class="home-estimate" id="heroEstimate" data-mode="wisata" hidden>
        <div class="home-estimate-head">
          <p class="home-estimate-title" id="estimateTitle">Coba hitung biayanya</p>
          <p class="home-estimate-note" id="estimateNote">Pilih paket, lalu atur jumlah orang.</p>
        </div>

        <div class="home-estimate-fields" id="estimateFields">
          <div class="home-estimate-field home-estimate-field--paket">
            <label for="estimatePaket">Paket</label>
            <select id="estimatePaket" class="home-estimate-select"></select>
          </div>

          <div class="home-estimate-row" id="estimateControls">
            <div class="home-estimate-stepper" id="estimateQtyWrap">
              <span class="home-estimate-stepper-label" id="estimateQtyLabel">Orang</span>
              <div class="home-estimate-stepper-ctrl">
                <button type="button" class="home-estimate-step" data-step="-1" data-target="estimateQty"
                  aria-label="Kurangi">−</button>
                <input type="number" id="estimateQty" class="home-estimate-input" min="1" max="50" value="2"
                  inputmode="numeric">
                <button type="button" class="home-estimate-step" data-step="1" data-target="estimateQty"
                  aria-label="Tambah">+</button>
              </div>
            </div>

            <div class="home-estimate-field home-estimate-field--dates" id="estimateNightsWrap" hidden>
              <label for="estimateDateRange">Tanggal menginap</label>
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

      <div class="home-hero-actions">
        <a class="btn btn-primary" id="heroPrimaryCta" href="<?= site_url('paket-wisata?jenis=wisata') ?>">Lihat Paket
          Wisata</a>
        <a class="btn btn-cream" href="<?= site_url('/') ?>#cek-status">Cek Status</a>
      </div>
    </div>

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
        <?php
        $jenisPaket = $p['jenis'] ?? 'wisata';
        $badgeClass = match ($jenisPaket) {
          'homestay' => 'badge-homestay',
          'camping' => 'badge-camping',
          default => 'badge-wisata',
        };
        $avail = $ketersediaan[(int) $p['id']] ?? ['total' => 0, 'available' => 0, 'remaining' => null];
        ?>
        <a class="card" href="<?= site_url('paket-wisata/' . $p['slug']) ?>">
          <img class="card-img" src="<?= esc(cover_url($p['gambar_cover'] ?? null, $jenisPaket)) ?>"
            alt="<?= esc($p['nama']) ?>">
          <div class="card-body">
            <div class="card-badges">
              <span class="badge-jenis <?= $badgeClass ?>"><?= esc(label_jenis_paket($jenisPaket)) ?></span>
              <?php if ((int) $avail['available'] > 0): ?>
                <span class="badge badge-available">Tersedia</span>
              <?php else: ?>
                <span class="badge badge-soldout">Penuh</span>
              <?php endif; ?>
            </div>
            <h3><?= esc($p['nama']) ?></h3>
            <div class="price"><?= format_rupiah($p['harga']) ?>
              <small style="font-weight:400;color:var(--sepia)">/
                <?= esc(satuan_label($jenisPaket, $p['satuan_harga'] ?? null)) ?></small>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php if ($fiturProduk): ?>
  <section class="section container">
    <h2 class="section-title">Produk UMKM Unggulan</h2>
    <?php if (empty($produk)): ?>
      <p class="text-center" style="color:var(--sepia)">Belum ada produk dipublikasikan.</p>
    <?php else: ?>
      <div class="card-grid">
        <?php foreach ($produk as $pr): ?>
          <?php
          $jenisProduk = ($pr['jenis'] ?? 'umkm') === 'catering' ? 'catering' : 'umkm';
          $isCatering = $jenisProduk === 'catering';
          ?>
          <a class="card" href="<?= site_url('toko/' . $pr['slug']) ?>">
            <img class="card-img" src="<?= esc(cover_url($pr['gambar'] ?? null, $jenisProduk)) ?>"
              alt="<?= esc($pr['nama']) ?>">
            <div class="card-body">
              <h3><?= esc($pr['nama']) ?></h3>
              <span class="badge-jenis <?= $isCatering ? 'badge-catering' : 'badge-umkm' ?>">
                <?= $isCatering ? 'Catering' : 'UMKM' ?>
              </span>
              <div class="price"><?= format_rupiah($pr['harga']) ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
<?php endif; ?>

<section class="section container" id="cek-status">
  <h2 class="section-title">Cek Status Transaksi</h2>
  <form method="get"
    onsubmit="event.preventDefault(); const k=this.kode.value.trim(); if(k) location.href='<?= site_url('status') ?>/'+encodeURIComponent(k);"
    style="max-width:480px;margin:0 auto">
    <div class="form-group">
      <label for="kode">Kode transaksi (RSV-…<?= $fiturProduk ? ' atau ORD-…' : '' ?>)</label>
      <input class="form-control" type="text" name="kode" id="kode"
        placeholder="RSV-xxxx<?= $fiturProduk ? ' atau ORD-xxxx' : '' ?>" required>
    </div>
    <button class="btn btn-primary" type="submit" style="width:100%">Cek Status</button>
  </form>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  (() => {
    const estimateData = {
      wisata: <?= json_encode(array_values($estimateWisata), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
      menginap: <?= json_encode(array_values($estimateMenginap), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
    };

    const services = {
      wisata: {
        lead: 'Paket wisata desa — harga per orang, hitung estimasi sebelum pesan.',
        cta: 'Lihat Paket Wisata',
        href: '<?= site_url('paket-wisata?jenis=wisata') ?>',
        estimate: true,
      },
      menginap: {
        lead: 'Homestay & camping ground — harga per rumah / malam. Hitung estimasi menginap Anda.',
        cta: 'Lihat Homestay & Camping',
        href: '<?= site_url('paket-wisata?jenis=menginap') ?>',
        estimate: true,
      },
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
    let availTimer = null;
    const carousel = document.getElementById('heroCarousel');
    const slides = carousel ? Array.from(carousel.querySelectorAll('.hero-carousel-slide')) : [];
    const dotsWrap = document.getElementById('heroCarouselDots');
    let index = 0;
    let timer = null;
    let activeJenis = 'wisata';
    let rangeFp = null;

    const rupiah = (n) => 'Rp ' + Math.round(n).toLocaleString('id-ID');

    function nightsFromDates(start, end) {
      if (!start || !end) return 0;
      const a = new Date(start.getFullYear(), start.getMonth(), start.getDate());
      const b = new Date(end.getFullYear(), end.getMonth(), end.getDate());
      const diff = Math.round((b - a) / 86400000);
      return diff > 0 ? diff : 0;
    }

    function defaultRangeDates() {
      const start = new Date();
      start.setHours(0, 0, 0, 0);
      const end = new Date(start);
      end.setDate(end.getDate() + 2);
      return [start, end];
    }

    function syncNightsFromFp() {
      if (!estimateNights || !rangeFp) return;
      const dates = rangeFp.selectedDates || [];
      const nights = dates.length === 2 ? nightsFromDates(dates[0], dates[1]) : 0;
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
          } else if (selectedDates.length < 2) {
            if (estimateNights) estimateNights.value = '0';
            calcEstimate();
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
      if (estimateQtyLabel) estimateQtyLabel.textContent = on ? 'Rumah' : 'Orang';
      if (estimateNote) {
        estimateNote.textContent = on
          ? 'Pilih tanggal check-in & check-out, lalu atur jumlah rumah.'
          : 'Pilih paket, lalu atur jumlah orang.';
      }
      if (estimateQty && !estimateQty.dataset.touched) {
        estimateQty.value = on ? '1' : '2';
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

    function destroyEstimateSelect2() {
      if (!estimateSelect || typeof jQuery === 'undefined') return;
      const $el = jQuery(estimateSelect);
      if ($el.hasClass('select2-hidden-accessible')) {
        $el.select2('destroy');
      }
    }

    function initEstimateSelect2() {
      if (!estimateSelect || typeof jQuery === 'undefined' || typeof jQuery.fn.select2 !== 'function') return;
      destroyEstimateSelect2();
      const $el = jQuery(estimateSelect);
      const $parent = $el.closest('.home-estimate');
      $el.select2({
        width: '100%',
        minimumResultsForSearch: 8,
        dropdownParent: $parent.length ? $parent : jQuery(document.body),
        language: 'id',
        placeholder: 'Pilih paket…',
      });
    }

    function fillEstimate(mode) {
      const list = estimateData[mode] || [];
      if (!estimateSelect) return;
      destroyEstimateSelect2();
      estimateSelect.innerHTML = '';
      if (!list.length) {
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = 'Belum ada paket';
        estimateSelect.appendChild(opt);
        if (estimateTotal) estimateTotal.textContent = 'Rp 0';
        if (estimateBreakdown) estimateBreakdown.textContent = '—';
        setMenginapMode(mode === 'menginap');
        initEstimateSelect2();
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
      initEstimateSelect2();
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
        const rumahTxt = qty + ' rumah';
        if (nights < 1) {
          total = 0;
          breakdown = rumahTxt + ' · pilih tanggal menginap';
        } else {
          total = harga * qty * nights;
          breakdown = rumahTxt + ' · ' + nights + ' malam · ' + rupiah(harga) + '/malam';
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
    }

    function syncEstimateCta() {
      if (!estimateSelect || !primary) return;
      const opt = estimateSelect.selectedOptions[0];
      const slug = opt?.dataset?.slug;
      if (slug && (activeJenis === 'wisata' || activeJenis === 'menginap')) {
        primary.setAttribute('href', '<?= site_url('paket-wisata') ?>/' + encodeURIComponent(slug));
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