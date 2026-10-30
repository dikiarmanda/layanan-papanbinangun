<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container">
  <h2 class="section-title"><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h2>

  <?php if (!empty($cartJenis)): ?>
    <div class="alert alert-info" style="text-align:center;margin-bottom:1.5rem">
      <i class="fas fa-info-circle"></i> Jenis keranjang:
      <strong><?= $cartJenis === 'catering' ? 'Catering' : 'Produk UMKM' ?></strong>
      — tidak bisa dicampur dengan jenis lain.
    </div>
  <?php endif; ?>

  <?php if (empty($items)): ?>
    <div id="keranjang-empty" style="text-align:center;padding:3rem 1rem">
      <i class="fas fa-cart-plus" style="font-size:3.5rem;color:var(--gold);margin-bottom:1rem;display:block"></i>
      <p style="color:var(--sepia);font-size:1.15rem;margin-bottom:1.5rem">Keranjang belanja Anda masih kosong.</p>
      <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap">
        <a class="btn btn-primary" href="<?= site_url('toko?jenis=umkm') ?>">
          <i class="fas fa-store"></i> Belanja UMKM
        </a>
        <a class="btn btn-outline" href="<?= site_url('toko?jenis=catering') ?>">
          <i class="fas fa-utensils"></i> Pesan Catering
        </a>
      </div>
    </div>
  <?php endif; ?>

  <div id="keranjang-content" style="<?= empty($items) ? 'display:none' : '' ?>">
    <div class="keranjang-list" id="keranjang-list">
      <?php if (!empty($items)): ?>
        <?php foreach ($items as $item): ?>
          <div class="keranjang-item" data-cart-key="<?= esc($item['cart_key']) ?>" data-harga="<?= (float) $item['harga_satuan'] ?>">
            <div class="keranjang-item-img">
              <?php if (!empty($item['produk']['gambar'])): ?>
                <img src="<?= media_url($item['produk']['gambar']) ?>"
                     alt="<?= esc($item['produk']['nama']) ?>">
              <?php else: ?>
                <div class="keranjang-item-img-placeholder">
                  <i class="fas fa-image"></i>
                </div>
              <?php endif; ?>
            </div>
            <div class="keranjang-item-info">
              <div class="keranjang-item-top">
                <div style="min-width:0">
                  <h4 class="keranjang-item-name"><?= esc($item['produk']['nama']) ?></h4>
                  <div style="display:flex;gap:0.4rem;align-items:center;flex-wrap:wrap;margin-top:2px">
                    <span class="badge-jenis badge-<?= esc($item['produk']['jenis'] ?? 'umkm') ?>" style="font-size:0.68rem">
                      <?= ($item['produk']['jenis'] ?? 'umkm') === 'catering' ? 'Catering' : 'UMKM' ?>
                    </span>
                    <?php if (!empty($item['varian'])): ?>
                      <span class="badge" style="background:#0d6efd;color:#fff;font-size:0.68rem;padding:2px 6px;border-radius:4px">
                        Ukuran: <?= esc($item['varian']) ?>
                      </span>
                    <?php endif; ?>
                    <?php if (!empty($item['min_beli']) && (int) $item['min_beli'] > 1): ?>
                      <span class="badge" style="background:#ffc107;color:#212529;font-size:0.68rem;padding:2px 6px;border-radius:4px">
                        Min <?= (int) $item['min_beli'] ?> pcs
                      </span>
                    <?php endif; ?>
                  </div>
                  <span class="keranjang-item-price" style="margin-left:0;display:block;margin-top:3px"><?= format_rupiah($item['harga_satuan']) ?></span>
                </div>
                <button type="button" class="keranjang-remove" title="Hapus" data-cart-key="<?= esc($item['cart_key']) ?>">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
              <div class="keranjang-item-bottom">
                <div class="keranjang-qty-form">
                  <button type="button" class="keranjang-qty-btn" data-action="minus">
                    <i class="fas fa-minus"></i>
                  </button>
                  <input type="number" value="<?= (int) $item['jumlah'] ?>"
                         min="<?= (int) ($item['min_beli'] ?? 1) ?>"
                         data-min="<?= (int) ($item['min_beli'] ?? 1) ?>"
                         class="form-control keranjang-qty-input" data-cart-key="<?= esc($item['cart_key']) ?>">
                  <button type="button" class="keranjang-qty-btn" data-action="plus">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
                <div class="keranjang-item-subtotal" data-cart-key="<?= esc($item['cart_key']) ?>">
                  <?= format_rupiah($item['subtotal']) ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="keranjang-summary" id="keranjang-summary">
      <div class="keranjang-summary-row">
        <span style="color:var(--sepia)">Total (<span id="keranjang-count"><?= count($items) ?></span> produk)</span>
        <span class="price" style="font-size:1.35rem" id="keranjang-total"><?= format_rupiah($subtotal ?? 0) ?></span>
      </div>
      <a class="btn btn-primary btn-block" href="<?= site_url('checkout-produk') ?>">
        <i class="fas fa-credit-card"></i> Lanjut ke Checkout
      </a>
      <a href="<?= site_url('toko') ?>" style="display:block;text-align:center;margin-top:0.75rem;color:var(--sepia);font-size:0.95rem">
        <i class="fas fa-arrow-left"></i> Lanjut belanja
      </a>
    </div>
  </div>
</section>

<style>
  .keranjang-list {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
  }
  .keranjang-item {
    display: flex;
    gap: 0.75rem;
    background: var(--white);
    border: 1px solid var(--cream-dark);
    border-radius: 8px;
    padding: 0.6rem 0.75rem;
    transition: box-shadow .2s, opacity .3s, transform .3s;
    align-items: center;
  }
  .keranjang-item:hover {
    box-shadow: 0 2px 10px var(--shadow);
    border-color: var(--gold);
  }
  .keranjang-item.is-removing {
    opacity: 0;
    transform: translateX(30px);
  }
  .keranjang-item.is-updating .keranjang-item-subtotal {
    opacity: 0.4;
  }
  .keranjang-item-img {
    width: 64px;
    min-width: 64px;
    height: 64px;
    border-radius: 6px;
    overflow: hidden;
    flex-shrink: 0;
  }
  .keranjang-item-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: sepia(.12);
  }
  .keranjang-item-img-placeholder {
    width: 100%;
    height: 100%;
    background: var(--cream);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--cream-dark);
    font-size: 1.25rem;
  }
  .keranjang-item-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
    gap: 0.35rem;
  }
  .keranjang-item-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.5rem;
  }
  .keranjang-item-name {
    margin: 0;
    color: var(--deep);
    font-size: 0.95rem;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .keranjang-item-price {
    color: var(--sepia);
    font-size: 0.8rem;
    margin-left: 0.4rem;
  }
  .keranjang-remove {
    color: var(--sepia);
    opacity: 0.4;
    font-size: 0.8rem;
    transition: opacity .2s, color .2s;
    padding: 0.15rem;
    flex-shrink: 0;
    background: none;
    border: none;
    cursor: pointer;
  }
  .keranjang-remove:hover {
    opacity: 1;
    color: var(--terracotta);
  }
  .keranjang-item-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
  }
  .keranjang-item-subtotal {
    font-family: var(--font-heading);
    font-weight: 600;
    color: var(--terracotta);
    font-size: 1rem;
    white-space: nowrap;
    transition: opacity .2s;
  }
  .keranjang-qty-form {
    display: flex;
    align-items: center;
    border: 1px solid var(--cream-dark);
    border-radius: 6px;
    overflow: hidden;
  }
  .keranjang-qty-btn {
    width: 28px;
    height: 28px;
    border: none;
    background: var(--cream);
    color: var(--deep);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    transition: background .2s;
  }
  .keranjang-qty-btn:hover {
    background: var(--cream-dark);
  }
  .keranjang-qty-input {
    width: 38px !important;
    text-align: center;
    border: none !important;
    border-left: 1px solid var(--cream-dark) !important;
    border-right: 1px solid var(--cream-dark) !important;
    border-radius: 0 !important;
    height: 28px;
    padding: 0 !important;
    font-size: 0.85rem;
    -moz-appearance: textfield;
  }
  .keranjang-qty-input::-webkit-inner-spin-button,
  .keranjang-qty-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  .keranjang-summary {
    margin-top: 1.25rem;
    background: var(--white);
    border: 1px solid var(--gold);
    border-radius: 8px;
    padding: 1rem 1.25rem;
  }
  .keranjang-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.85rem;
    padding-bottom: 0.85rem;
    border-bottom: 1px dashed var(--cream-dark);
  }
  @media (max-width: 640px) {
    .keranjang-item-img {
      width: 56px;
      min-width: 56px;
      height: 56px;
    }
    .keranjang-item-name {
      font-size: 0.88rem;
    }
    .keranjang-item-bottom {
      flex-wrap: wrap;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function() {
  var updateUrl = '<?= site_url('keranjang/update') ?>';
  var removeUrl = '<?= site_url('keranjang/remove') ?>';
  var csrfName = '<?= csrf_token() ?>';
  var csrfHash = '<?= csrf_hash() ?>';
  var debounceTimers = {};

  function formatRupiah(n) {
    return 'Rp' + Math.round(n).toLocaleString('id-ID');
  }

  function updateSummary(data) {
    var countEl = document.getElementById('keranjang-count');
    var totalEl = document.getElementById('keranjang-total');
    if (countEl) countEl.textContent = data.count;
    if (totalEl) totalEl.textContent = data.subtotal_fmt;

    if (data.count === 0) {
      document.getElementById('keranjang-content').style.display = 'none';
      var emptyEl = document.getElementById('keranjang-empty');
      if (emptyEl) {
        emptyEl.style.display = '';
      } else {
        location.reload();
      }
    }
  }

  function updateSubtotalLocal(row) {
    var harga = parseFloat(row.dataset.harga);
    var input = row.querySelector('.keranjang-qty-input');
    var qty = parseInt(input.value) || 0;
    var subEl = row.querySelector('.keranjang-item-subtotal');
    if (subEl) subEl.textContent = formatRupiah(harga * qty);

    var allRows = document.querySelectorAll('.keranjang-item');
    var total = 0;
    var count = 0;
    allRows.forEach(function(r) {
      var h = parseFloat(r.dataset.harga);
      var q = parseInt(r.querySelector('.keranjang-qty-input').value) || 0;
      total += h * q;
      count++;
    });
    var countEl = document.getElementById('keranjang-count');
    var totalEl = document.getElementById('keranjang-total');
    if (countEl) countEl.textContent = count;
    if (totalEl) totalEl.textContent = formatRupiah(total);
  }

  function getCsrfToken() {
    var match = document.cookie.match(new RegExp('(^|;\\s*)csrf_cookie_name=([^;]*)'));
    return match ? decodeURIComponent(match[2]) : csrfHash;
  }

  function ajaxUpdate(cartKey, jumlah) {
    var row = document.querySelector('.keranjang-item[data-cart-key="' + cartKey + '"]');
    if (row) row.classList.add('is-updating');

    var currentToken = getCsrfToken();

    $.ajax({
      url: updateUrl,
      method: 'POST',
      data: {cart_key: cartKey, jumlah: jumlah, [csrfName]: currentToken},
      headers: {'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': currentToken},
      dataType: 'json',
      success: function(data) {
        if (data.csrf_token) csrfHash = data.csrf_token;
        if (data.csrf_hash) csrfHash = data.csrf_hash;
        if (row) row.classList.remove('is-updating');

        if (jumlah <= 0 && row) {
          row.classList.add('is-removing');
          setTimeout(function() {
            row.remove();
            updateSummary(data);
          }, 300);
        } else {
          data.items.forEach(function(it) {
            var subEl = document.querySelector('.keranjang-item-subtotal[data-cart-key="' + it.cart_key + '"]');
            if (subEl) subEl.textContent = it.subtotal_fmt;
          });
          updateSummary(data);
        }
      },
      error: function() {
        if (row) row.classList.remove('is-updating');
        Swal.fire({icon:'error', title:'Gagal', text:'Gagal mengupdate keranjang.', timer:2000, showConfirmButton:false});
      }
    });
  }

  function ajaxRemove(cartKey) {
    var row = document.querySelector('.keranjang-item[data-cart-key="' + cartKey + '"]');

    Swal.fire({
      title: 'Hapus produk?',
      text: 'Produk ini akan dihapus dari keranjang.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#c1502e'
    }).then(function(result) {
      if (!result.isConfirmed) return;

      if (row) row.classList.add('is-removing');

      $.ajax({
        url: removeUrl + '/' + encodeURIComponent(cartKey),
        method: 'GET',
        dataType: 'json',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        success: function(data) {
          if (data.csrf_token) csrfHash = data.csrf_token;
          if (data.csrf_hash) csrfHash = data.csrf_hash;
          setTimeout(function() {
            if (row) row.remove();
            updateSummary(data);
          }, 300);
        },
        error: function() {
          if (row) row.classList.remove('is-removing');
          Swal.fire({icon:'error', title:'Gagal', text:'Gagal menghapus produk.', timer:2000, showConfirmButton:false});
        }
      });
    });
  }

  function debouncedUpdate(cartKey, jumlah) {
    if (debounceTimers[cartKey]) clearTimeout(debounceTimers[cartKey]);
    debounceTimers[cartKey] = setTimeout(function() {
      ajaxUpdate(cartKey, jumlah);
    }, 400);
  }

  $(document).on('click', '.keranjang-qty-btn', function() {
    var btn = $(this);
    var action = btn.data('action');
    var form = btn.closest('.keranjang-qty-form');
    var input = form.find('.keranjang-qty-input');
    var val = parseInt(input.val()) || 0;
    var min = parseInt(input.data('min')) || 1;
    var row = btn.closest('.keranjang-item');
    var cartKey = row.data('cart-key');

    if (action === 'minus') {
      if (val - 1 < min) {
        ajaxRemove(cartKey);
        return;
      }
      val = val - 1;
    } else {
      val = val + 1;
    }
    input.val(val);

    updateSubtotalLocal(row[0]);
    debouncedUpdate(cartKey, val);
  });

  $(document).on('change', '.keranjang-qty-input', function() {
    var input = $(this);
    var val = parseInt(input.val()) || 0;
    var min = parseInt(input.data('min')) || 1;
    var row = input.closest('.keranjang-item');
    var cartKey = row.data('cart-key');

    if (val < min) {
      if (val <= 0) {
        ajaxRemove(cartKey);
        input.val(min);
        return;
      }
      Swal.fire({
        icon: 'warning',
        title: 'Minimal Pembelian',
        text: 'Minimal pembelian untuk produk ini adalah ' + min + ' pcs.',
        timer: 2000,
        showConfirmButton: false
      });
      val = min;
      input.val(min);
    }

    updateSubtotalLocal(row[0]);
    debouncedUpdate(cartKey, val);
  });

  $(document).on('click', '.keranjang-remove', function(e) {
    e.preventDefault();
    var cartKey = $(this).data('cart-key');
    ajaxRemove(cartKey);
  });
})();
</script>
<?= $this->endSection() ?>
