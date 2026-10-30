<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<section class="section container">
  <h1 class="section-title"><?= esc($title) ?></h1>

  <!-- TOOLBAR FILTER & PENCARIAN (SELECT2 & SEARCH) -->
  <form id="toko-search-form" method="get" action="<?= site_url('toko') ?>" class="toko-filter-bar">
    <div class="filter-item filter-search">
      <div style="position:relative">
        <input type="text" name="q" id="toko-search-input" class="form-control" placeholder="Cari nama produk atau makanan..."
               value="<?= esc($q ?? '') ?>" style="padding-left:2.4rem;border-radius:6px" autocomplete="off">
        <i id="search-icon" class="fas fa-search" style="position:absolute;left:0.9rem;top:50%;transform:translateY(-50%);color:var(--sepia);opacity:0.6"></i>
      </div>
    </div>

    <div class="filter-item filter-select">
      <select name="jenis" id="filter-jenis" class="form-control select2" data-placeholder="Semua Jenis" data-allow-clear="1">
        <option value="">Semua Jenis</option>
        <option value="umkm" <?= ($activeJenis ?? '') === 'umkm' ? 'selected' : '' ?>>Produk UMKM</option>
        <option value="catering" <?= ($activeJenis ?? '') === 'catering' ? 'selected' : '' ?>>Catering</option>
      </select>
    </div>

    <?php if (!empty($kategori)): ?>
      <div class="filter-item filter-select">
        <select name="kategori" id="filter-kategori" class="form-control select2" data-placeholder="Semua Kategori" data-allow-clear="1">
          <option value="">Semua Kategori</option>
          <?php foreach ($kategori as $k): ?>
            <option value="<?= (int) $k['id'] ?>" <?= (string) ($activeKat ?? '') === (string) $k['id'] ? 'selected' : '' ?>>
              <?= esc($k['nama']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>

    <div class="filter-item filter-action">
      <button type="button" id="btn-reset-filters" class="btn btn-outline" title="Reset semua filter"
              style="<?= (empty($q) && empty($activeJenis) && empty($activeKat)) ? 'display:none;' : '' ?>">
        <i class="fas fa-rotate-left"></i> Reset
      </button>
    </div>
  </form>

  <!-- CONTAINER PRODUK HASIL AJAX -->
  <div id="toko-grid-container" style="transition:opacity .2s">
    <?= view('toko/_grid', [
        'produk'      => $produk,
        'q'           => $q,
        'activeJenis' => $activeJenis,
        'activeKat'   => $activeKat,
    ]) ?>
  </div>
</section>

<style>
.toko-filter-bar {
  display: flex;
  gap: 0.75rem;
  align-items: center;
  flex-wrap: wrap;
  max-width: 900px;
  margin: 0 auto 2rem;
  background: var(--white, #fff);
  padding: 0.85rem 1rem;
  border-radius: 8px;
  border: 1px solid var(--cream-dark, #e2d7c5);
  box-shadow: 0 2px 8px var(--shadow, rgba(0,0,0,0.04));
}
.toko-filter-bar .filter-search {
  flex: 2;
  min-width: 220px;
}
.toko-filter-bar .filter-select {
  flex: 1.2;
  min-width: 170px;
}
.toko-filter-bar .filter-action {
  flex-shrink: 0;
}
.toko-filter-bar .select2-container {
  width: 100% !important;
}
.toko-filter-bar .select2-container .select2-selection--single {
  height: 38px !important;
  border: 1px solid var(--cream-dark, #e2d7c5) !important;
  border-radius: 6px !important;
  display: flex !important;
  align-items: center;
}
.toko-filter-bar .select2-container .select2-selection--single .select2-selection__rendered {
  line-height: normal !important;
  padding-left: 0.75rem !important;
  color: var(--deep, #2b2118);
}
.toko-filter-bar .select2-container .select2-selection--single .select2-selection__arrow {
  height: 36px !important;
  right: 6px !important;
}
@media (max-width: 640px) {
  .toko-filter-bar {
    flex-direction: column;
    align-items: stretch;
    padding: 0.75rem;
  }
  .toko-filter-bar .filter-search,
  .toko-filter-bar .filter-select,
  .toko-filter-bar .filter-action {
    width: 100%;
    min-width: 100%;
  }
  .toko-filter-bar .filter-action button {
    width: 100%;
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
  var $resetBtn = $('#btn-reset-filters');
  var $grid = $('#toko-grid-container');
  var $jenis = $('#filter-jenis');
  var $kategori = $('#filter-kategori');
  var searchTimer = null;
  var currentVal = $input.val().trim();
  var isSearching = false;

  function doAjaxSearch() {
    if (isSearching || !$grid.length) return;

    var qVal = $input.val().trim();
    var jVal = $jenis.val() || '';
    var kVal = $kategori.val() || '';

    currentVal = qVal;

    // Toggle tombol reset
    if (qVal !== '' || jVal !== '' || kVal !== '') {
      $resetBtn.show();
    } else {
      $resetBtn.hide();
    }

    var params = new URLSearchParams();
    if (qVal) params.append('q', qVal);
    if (jVal) params.append('jenis', jVal);
    if (kVal) params.append('kategori', kVal);

    var targetUrl = $form.attr('action') + (params.toString() ? '?' + params.toString() : '');

    isSearching = true;
    $grid.css('opacity', '0.45');
    if ($icon.length) {
      $icon.attr('class', 'fas fa-spinner fa-spin');
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
          $icon.attr('class', 'fas fa-search');
        }
      }
    });
  }

  // Listener input keyup debounce 1 detik
  $input.on('keyup', function(e) {
    if ([9, 16, 17, 18, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 91, 92].indexOf(e.keyCode) !== -1) {
      return;
    }

    clearTimeout(searchTimer);
    searchTimer = setTimeout(function() {
      if ($input.val().trim() !== currentVal) {
        doAjaxSearch();
      }
    }, 1000);
  });

  // Listener submit form manual (Enter)
  $form.on('submit', function(e) {
    e.preventDefault();
    clearTimeout(searchTimer);
    doAjaxSearch();
  });

  // Listener perubahan select2 jenis & kategori
  $jenis.on('change', function() {
    clearTimeout(searchTimer);
    doAjaxSearch();
  });

  $kategori.on('change', function() {
    clearTimeout(searchTimer);
    doAjaxSearch();
  });

  // Listener tombol reset
  $resetBtn.on('click', function(e) {
    e.preventDefault();
    $input.val('');
    $jenis.val('').trigger('change.select2');
    $kategori.val('').trigger('change.select2');
    clearTimeout(searchTimer);
    doAjaxSearch();
  });
})(jQuery);
</script>
<?= $this->endSection() ?>
