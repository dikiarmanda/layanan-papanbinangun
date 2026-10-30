<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="toko-hero-wrap">
  <div class="container">
    <div class="toko-hero-content">
      <span class="toko-hero-eyebrow">
        <i class="fa-solid fa-store"></i> Karya Warga &amp; Kuliner Autentik
      </span>
      <h1 class="toko-hero-title">Pasar Desa Papanbinangun</h1>
      <p class="toko-hero-desc">
        Dukung UMKM lokal dengan membeli kerajinan tangan asli, oleh-oleh khas desa, dan sajian katering segar langsung dari dapur warga.
      </p>
    </div>

    <!-- TOOLBAR FILTER & PENCARIAN (SELECT2 & AJAX) -->
    <div class="toko-toolbar-card">
      <form id="toko-search-form" method="get" action="<?= site_url('toko') ?>" class="toko-search-grid">
        <div class="toolbar-col col-search">
          <label for="toko-search-input" class="toolbar-label">Cari Produk</label>
          <div class="search-input-wrap">
            <i id="search-icon" class="fas fa-search search-prefix-icon"></i>
            <input type="text" name="q" id="toko-search-input" class="form-control toolbar-input"
                   placeholder="Ketik nama kerajinan, oleh-oleh, atau menu catering..."
                   value="<?= esc($q ?? '') ?>" autocomplete="off">
            <button type="button" id="btn-clear-q" class="search-clear-btn" style="<?= empty($q) ? 'display:none;' : '' ?>" title="Hapus teks pencarian">
              <i class="fas fa-times-circle"></i>
            </button>
          </div>
        </div>

        <div class="toolbar-col col-jenis">
          <label for="filter-jenis" class="toolbar-label">Jenis Layanan</label>
          <select name="jenis" id="filter-jenis" class="form-control select2" data-placeholder="Semua Jenis" data-allow-clear="1">
            <option value="">Semua Jenis (UMKM &amp; Catering)</option>
            <option value="umkm" <?= ($activeJenis ?? '') === 'umkm' ? 'selected' : '' ?>>Karya UMKM (Bisa Kirim Ekspedisi)</option>
            <option value="catering" <?= ($activeJenis ?? '') === 'catering' ? 'selected' : '' ?>>Dapur Katering (Acara / Antar Lokal)</option>
          </select>
        </div>

        <?php if (!empty($kategori)): ?>
          <div class="toolbar-col col-kategori">
            <label for="filter-kategori" class="toolbar-label">Kategori</label>
            <select name="kategori" id="filter-kategori" class="form-control select2" data-placeholder="Semua Kategori" data-allow-clear="1">
              <option value="">Semua Kategori Produk</option>
              <?php foreach ($kategori as $k): ?>
                <option value="<?= (int) $k['id'] ?>" <?= (string) ($activeKat ?? '') === (string) $k['id'] ? 'selected' : '' ?>>
                  <?= esc($k['nama']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php endif; ?>

        <div class="toolbar-col col-action">
          <button type="button" id="btn-reset-filters" class="btn btn-outline btn-reset" title="Kembalikan semua filter"
                  style="<?= (empty($q) && empty($activeJenis) && empty($activeKat)) ? 'display:none;' : '' ?>">
            <i class="fas fa-rotate-left"></i> Reset
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<section class="section container toko-catalog-section">
  <!-- CONTAINER PRODUK HASIL AJAX -->
  <div id="toko-grid-container" class="toko-grid-container">
    <?= view('toko/_grid', [
        'produk'      => $produk,
        'q'           => $q,
        'activeJenis' => $activeJenis,
        'activeKat'   => $activeKat,
    ]) ?>
  </div>
</section>

<style>
/* ==========================================================================
   TOKO HERO & FILTER STYLING (Tema Papanbinangun Vintage Warm)
   ========================================================================== */
.toko-hero-wrap {
  padding: 2.25rem 0 1.25rem;
  background: linear-gradient(180deg, rgba(232, 217, 192, 0.45) 0%, rgba(244, 233, 216, 0) 100%);
  border-bottom: 1px dashed var(--cream-dark, #e8d9c0);
  margin-bottom: 2rem;
}

.toko-hero-content {
  text-align: center;
  max-width: 720px;
  margin: 0 auto 1.75rem;
}

.toko-hero-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  font-size: 0.85rem;
  font-weight: 600;
  letter-spacing: 0.04em;
  color: var(--terracotta, #c1502e);
  background: rgba(193, 80, 46, 0.08);
  border: 1px solid rgba(193, 80, 46, 0.2);
  padding: 0.35rem 0.85rem;
  border-radius: 20px;
  margin-bottom: 0.65rem;
}

.toko-hero-title {
  font-family: var(--font-heading, serif);
  font-size: clamp(2rem, 4.5vw, 2.75rem);
  font-weight: 700;
  color: var(--deep, #4a3222);
  line-height: 1.2;
  margin: 0.25rem 0 0.65rem;
}

.toko-hero-desc {
  font-size: 1.02rem;
  color: var(--sepia, #8b5e3c);
  line-height: 1.6;
  margin: 0;
}

/* Toolbar Card */
.toko-toolbar-card {
  background: var(--white, #fffbf5);
  border: 1px solid var(--gold, #c9a66b);
  border-radius: 12px;
  box-shadow: 0 4px 20px var(--shadow, rgba(74, 50, 34, 0.08));
  padding: 1.15rem 1.25rem;
  max-width: 960px;
  margin: 0 auto;
}

.toko-search-grid {
  display: grid;
  grid-template-columns: 2fr 1.3fr 1.3fr auto;
  gap: 1rem;
  align-items: flex-end;
}

.toolbar-col {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  min-width: 0;
}

.toolbar-label {
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--deep, #4a3222);
  letter-spacing: 0.02em;
}

.search-input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.search-prefix-icon {
  position: absolute;
  left: 0.95rem;
  color: var(--sepia, #8b5e3c);
  opacity: 0.65;
  pointer-events: none;
  font-size: 0.95rem;
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.toolbar-input {
  width: 100%;
  height: 42px;
  padding: 0 2.2rem 0 2.5rem;
  background: var(--cream, #f4e9d8);
  border: 1px solid var(--cream-dark, #e8d9c0);
  border-radius: 8px;
  font-family: var(--font-body, serif);
  font-size: 0.95rem;
  color: var(--deep, #4a3222);
  transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}

.toolbar-input:focus {
  background: #ffffff;
  border-color: var(--gold, #c9a66b);
  box-shadow: 0 0 0 3px rgba(201, 166, 107, 0.22);
  outline: none;
}

.search-clear-btn {
  position: absolute;
  right: 0.65rem;
  background: none;
  border: none;
  color: var(--sepia, #8b5e3c);
  opacity: 0.55;
  cursor: pointer;
  padding: 0.25rem;
  font-size: 1rem;
  transition: opacity 0.2s, color 0.2s;
}

.search-clear-btn:hover {
  opacity: 1;
  color: var(--terracotta, #c1502e);
}

/* Custom Select2 Skin */
.toko-toolbar-card .select2-container--default .select2-selection--single {
  height: 42px !important;
  background-color: var(--cream, #f4e9d8) !important;
  border: 1px solid var(--cream-dark, #e8d9c0) !important;
  border-radius: 8px !important;
  display: flex !important;
  align-items: center;
  transition: border-color 0.2s, background-color 0.2s, box-shadow 0.2s;
}

.toko-toolbar-card .select2-container--default.select2-container--focus .select2-selection--single,
.toko-toolbar-card .select2-container--default.select2-container--open .select2-selection--single {
  background-color: #ffffff !important;
  border-color: var(--gold, #c9a66b) !important;
  box-shadow: 0 0 0 3px rgba(201, 166, 107, 0.22) !important;
}

.toko-toolbar-card .select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: normal !important;
  padding-left: 0.85rem !important;
  padding-right: 2rem !important;
  color: var(--deep, #4a3222) !important;
  font-family: var(--font-body, serif);
  font-size: 0.92rem;
}

.toko-toolbar-card .select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 40px !important;
  right: 0.5rem !important;
}

.btn-reset {
  height: 42px;
  padding: 0 1rem;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  border-radius: 8px;
  font-size: 0.9rem;
  white-space: nowrap;
}

/* ==========================================================================
   PRODUCT CATALOG GRID & CARD STYLING
   ========================================================================== */
.toko-catalog-section {
  padding-top: 0;
  min-height: 400px;
}

.toko-grid-container {
  transition: opacity 0.25s ease;
}

.toko-grid-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid var(--cream-dark, #e8d9c0);
}

.toko-results-count {
  font-size: 0.95rem;
  color: var(--sepia, #8b5e3c);
}

.toko-filter-indicator {
  margin-left: 0.4rem;
  color: var(--terracotta, #c1502e);
  font-weight: 600;
}

.active-filter-chips {
  display: flex;
  gap: 0.45rem;
  flex-wrap: wrap;
}

.filter-chip {
  background: var(--white, #fffbf5);
  border: 1px solid var(--cream-dark, #e8d9c0);
  border-radius: 20px;
  padding: 0.25rem 0.65rem;
  font-size: 0.78rem;
  color: var(--deep, #4a3222);
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  cursor: pointer;
  transition: border-color 0.2s, background 0.2s;
}

.filter-chip:hover {
  border-color: var(--terracotta, #c1502e);
  color: var(--terracotta, #c1502e);
}

.chip-close {
  opacity: 0.6;
}

/* Products Grid */
.toko-products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 1.35rem;
}

.toko-card {
  background: var(--white, #fffbf5);
  border: 1px solid var(--cream-dark, #e8d9c0);
  border-radius: 10px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
  position: relative;
}

.toko-card:hover {
  transform: translateY(-4px);
  border-color: var(--gold, #c9a66b);
  box-shadow: 0 10px 25px var(--shadow, rgba(74, 50, 34, 0.12));
}

.toko-card.is-out-of-stock {
  opacity: 0.78;
}

.toko-card-link {
  color: inherit;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.toko-card-link:hover {
  color: inherit;
}

/* Media */
.toko-card-media {
  position: relative;
  aspect-ratio: 4 / 3;
  width: 100%;
  overflow: hidden;
  background: var(--cream, #f4e9d8);
}

.toko-card-media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: sepia(0.08) contrast(1.02);
  transition: transform 0.4s ease;
}

.toko-card:hover .toko-card-media img {
  transform: scale(1.04);
}

.toko-media-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  color: var(--sepia, #8b5e3c);
  opacity: 0.7;
}

.toko-media-placeholder i {
  font-size: 2rem;
  color: var(--gold, #c9a66b);
}

.toko-media-placeholder span {
  font-size: 0.85rem;
  font-weight: 600;
}

/* Badges on image */
.toko-badges-overlay {
  position: absolute;
  top: 0.65rem;
  left: 0.65rem;
  right: 0.65rem;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 0.4rem;
  pointer-events: none;
}

.toko-badge-pill {
  font-size: 0.72rem;
  font-weight: 600;
  padding: 0.2rem 0.55rem;
  border-radius: 4px;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
  letter-spacing: 0.02em;
}

.badge-pill-umkm {
  background: rgba(193, 80, 46, 0.92);
  color: #ffffff;
  backdrop-filter: blur(4px);
}

.badge-pill-catering {
  background: rgba(122, 132, 80, 0.92);
  color: #ffffff;
  backdrop-filter: blur(4px);
}

.badge-pill-variant {
  background: rgba(43, 33, 24, 0.85);
  color: #f4e9d8;
  backdrop-filter: blur(4px);
  margin-left: auto;
}

.badge-pill-min {
  background: rgba(201, 166, 107, 0.95);
  color: #2b2118;
  backdrop-filter: blur(4px);
  margin-left: auto;
}

/* Content */
.toko-card-content {
  padding: 1rem 1.15rem 1.15rem;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.toko-card-category {
  font-size: 0.76rem;
  color: var(--sepia, #8b5e3c);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 0.3rem;
  font-weight: 600;
}

.toko-card-title {
  font-family: var(--font-heading, serif);
  font-size: 1.18rem;
  font-weight: 600;
  line-height: 1.35;
  color: var(--deep, #4a3222);
  margin: 0 0 0.65rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  height: 3.2em;
}

.toko-card-footer {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  gap: 0.5rem;
  margin-top: auto;
  padding-top: 0.5rem;
  border-top: 1px dashed rgba(232, 217, 192, 0.7);
}

.toko-price-wrap {
  display: flex;
  align-items: baseline;
  gap: 0.25rem;
}

.toko-price-val {
  font-family: var(--font-heading, serif);
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--terracotta, #c1502e);
}

.toko-unit-hint {
  font-size: 0.78rem;
  color: var(--sepia, #8b5e3c);
}

.toko-stock-status {
  font-size: 0.78rem;
}

.stock-ready {
  color: var(--olive, #7a8450);
  font-weight: 600;
}

.stock-empty {
  color: #b91c1c;
  font-weight: 600;
}

.toko-card-action {
  margin-top: 0.75rem;
}

.btn-card-view {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  width: 100%;
  padding: 0.45rem;
  border-radius: 6px;
  background: rgba(244, 233, 216, 0.6);
  border: 1px solid var(--cream-dark, #e8d9c0);
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--deep, #4a3222);
  transition: background 0.2s, color 0.2s, border-color 0.2s;
}

.toko-card:hover .btn-card-view {
  background: var(--terracotta, #c1502e);
  border-color: var(--terracotta, #c1502e);
  color: #ffffff;
}

/* Empty State */
.toko-empty-state {
  text-align: center;
  padding: 3.5rem 1.5rem;
  background: var(--white, #fffbf5);
  border: 1px dashed var(--gold, #c9a66b);
  border-radius: 12px;
  max-width: 560px;
  margin: 1.5rem auto 3rem;
}

.empty-icon-wrap {
  width: 64px;
  height: 64px;
  margin: 0 auto 1.25rem;
  border-radius: 50%;
  background: rgba(201, 166, 107, 0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.75rem;
  color: var(--gold, #c9a66b);
}

.toko-empty-state h3 {
  font-family: var(--font-heading, serif);
  font-size: 1.5rem;
  margin-bottom: 0.4rem;
  color: var(--deep, #4a3222);
}

.toko-empty-state p {
  color: var(--sepia, #8b5e3c);
  font-size: 0.95rem;
  max-width: 420px;
  margin: 0 auto;
}

/* ==========================================================================
   MOBILE & RESPONSIVE REFINEMENTS
   ========================================================================== */
@media (max-width: 900px) {
  .toko-search-grid {
    grid-template-columns: 1fr 1fr;
  }
  .toolbar-col.col-search {
    grid-column: span 2;
  }
  .toolbar-col.col-action {
    grid-column: span 2;
    align-items: stretch;
  }
  .btn-reset {
    justify-content: center;
  }
}

@media (max-width: 640px) {
  .toko-hero-wrap {
    padding: 1.5rem 0 1rem;
    margin-bottom: 1.25rem;
  }
  .toko-toolbar-card {
    padding: 1rem;
  }
  .toko-search-grid {
    grid-template-columns: 1fr;
    gap: 0.75rem;
  }
  .toolbar-col.col-search,
  .toolbar-col.col-action {
    grid-column: span 1;
  }
  
  /* Mobile 2-column e-commerce grid */
  .toko-products-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
  }
  .toko-card-content {
    padding: 0.75rem 0.75rem 0.85rem;
  }
  .toko-card-title {
    font-size: 0.98rem;
    margin-bottom: 0.35rem;
    height: 2.7em;
  }
  .toko-price-val {
    font-size: 1.05rem;
  }
  .toko-card-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.2rem;
  }
  .btn-card-view {
    padding: 0.35rem;
    font-size: 0.78rem;
  }
  .toko-badge-pill {
    font-size: 0.65rem;
    padding: 0.15rem 0.4rem;
  }
}

@media (max-width: 360px) {
  .toko-products-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function($) {
  var $form = $('#toko-search-form');
  var $input = $('#toko-search-input');
  var $icon = $('#search-icon');
  var $clearQBtn = $('#btn-clear-q');
  var $resetBtn = $('#btn-reset-filters');
  var $grid = $('#toko-grid-container');
  var $jenis = $('#filter-jenis');
  var $kategori = $('#filter-kategori');
  var searchTimer = null;
  var currentVal = $input.val().trim();
  var isSearching = false;

  function updateActionButtons() {
    var qVal = $input.val().trim();
    var jVal = $jenis.val() || '';
    var kVal = $kategori.val() || '';

    if (qVal !== '') {
      $clearQBtn.show();
    } else {
      $clearQBtn.hide();
    }

    if (qVal !== '' || jVal !== '' || kVal !== '') {
      $resetBtn.show();
    } else {
      $resetBtn.hide();
    }
  }

  function doAjaxSearch() {
    if (isSearching || !$grid.length) return;

    var qVal = $input.val().trim();
    var jVal = $jenis.val() || '';
    var kVal = $kategori.val() || '';

    currentVal = qVal;
    updateActionButtons();

    var params = new URLSearchParams();
    if (qVal) params.append('q', qVal);
    if (jVal) params.append('jenis', jVal);
    if (kVal) params.append('kategori', kVal);

    var targetUrl = $form.attr('action') + (params.toString() ? '?' + params.toString() : '');

    isSearching = true;
    $grid.css('opacity', '0.45');
    if ($icon.length) {
      $icon.attr('class', 'fas fa-spinner fa-spin search-prefix-icon');
    }

    $.ajax({
      url: targetUrl,
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      dataType: 'json',
      success: function(data) {
        if (data && data.html !== undefined) {
          $grid.html(data.html);
          window.history.replaceState(null, '', targetUrl);
        }
      },
      error: function() {
        $form.off('submit').submit();
      },
      complete: function() {
        isSearching = false;
        $grid.css('opacity', '1');
        if ($icon.length) {
          $icon.attr('class', 'fas fa-search search-prefix-icon');
        }
      }
    });
  }

  // Input keyup debounce 1 detik
  $input.on('keyup', function(e) {
    if ([9, 16, 17, 18, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 91, 92].indexOf(e.keyCode) !== -1) {
      return;
    }

    updateActionButtons();
    clearTimeout(searchTimer);
    searchTimer = setTimeout(function() {
      if ($input.val().trim() !== currentVal) {
        doAjaxSearch();
      }
    }, 1000);
  });

  // Clear query button
  $clearQBtn.on('click', function(e) {
    e.preventDefault();
    $input.val('').focus();
    updateActionButtons();
    clearTimeout(searchTimer);
    doAjaxSearch();
  });

  // Submit form manual (Enter)
  $form.on('submit', function(e) {
    e.preventDefault();
    clearTimeout(searchTimer);
    doAjaxSearch();
  });

  // Select2 changes
  $jenis.on('change', function() {
    clearTimeout(searchTimer);
    doAjaxSearch();
  });

  $kategori.on('change', function() {
    clearTimeout(searchTimer);
    doAjaxSearch();
  });

  // Reset all
  function resetAll() {
    $input.val('');
    $jenis.val('').trigger('change.select2');
    $kategori.val('').trigger('change.select2');
    updateActionButtons();
    clearTimeout(searchTimer);
    doAjaxSearch();
  }

  $resetBtn.on('click', function(e) {
    e.preventDefault();
    resetAll();
  });

  $(document).on('click', '.btn-reset-all', function(e) {
    e.preventDefault();
    resetAll();
  });

  // Filter chips click to remove
  $(document).on('click', '.chip-remove-q', function() {
    $input.val('');
    updateActionButtons();
    doAjaxSearch();
  });

  $(document).on('click', '.chip-remove-jenis', function() {
    $jenis.val('').trigger('change.select2');
    updateActionButtons();
    doAjaxSearch();
  });

  $(document).on('click', '.chip-remove-kategori', function() {
    $kategori.val('').trigger('change.select2');
    updateActionButtons();
    doAjaxSearch();
  });
})(jQuery);
</script>
<?= $this->endSection() ?>
