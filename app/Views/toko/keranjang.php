<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="cart-page-header">
  <div class="container">
    <div class="cart-header-inner">
      <div>
        <span class="cart-header-eyebrow"><i class="fa-solid fa-basket-shopping"></i> Pesanan Belanja</span>
        <h1 class="cart-header-title">Keranjang Belanja</h1>
        <p class="cart-header-desc">Periksa kembali rincian produk, varian ukuran, dan jumlah pesanan Anda sebelum melanjutkan ke pembayaran.</p>
      </div>
      <a href="<?= site_url('toko') ?>" class="btn btn-outline btn-sm cart-back-btn">
        <i class="fa-solid fa-arrow-left"></i> Tambah Produk Lain
      </a>
    </div>
  </div>
</div>

<section class="section container cart-main-section">
  <!-- EMPTY CART STATE -->
  <div class="cart-empty-card" id="keranjang-empty" style="<?= !empty($items) ? 'display:none' : '' ?>">
    <div class="empty-basket-icon">
      <i class="fa-solid fa-basket-shopping"></i>
    </div>
    <h3 class="empty-title">Keranjang Belanja Anda Masih Kosong</h3>
    <p class="empty-subtitle">Belum ada karya kerajinan atau menu catering yang Anda pilih. Mari jelajahi koleksi khas desa Papanbinangun!</p>
    <div class="empty-actions">
      <a class="btn btn-primary" href="<?= site_url('toko?jenis=umkm') ?>">
        <i class="fa-solid fa-store me-3"></i> Belanja Produk UMKM
      </a>
      <a class="btn btn-outline" href="<?= site_url('toko?jenis=catering') ?>">
        <i class="fa-solid fa-utensils me-3"></i> Pesan Catering Desa
      </a>
    </div>
  </div>

  <!-- ACTIVE CART CONTENT -->
  <div id="keranjang-content" style="<?= empty($items) ? 'display:none' : '' ?>">
    <!-- CATEGORY/TYPE SEPARATION BANNER -->
    <?php if (!empty($cartJenis)): ?>
      <div class="cart-type-banner <?= $cartJenis === 'catering' ? 'banner-catering' : 'banner-umkm' ?>">
        <div class="type-banner-icon">
          <i class="<?= $cartJenis === 'catering' ? 'fa-solid fa-utensils' : 'fa-solid fa-store' ?>"></i>
        </div>
        <div class="type-banner-text">
          <strong>Jenis Keranjang: <?= $cartJenis === 'catering' ? 'Dapur Catering Desa' : 'Karya & Produk UMKM' ?></strong>
          <span>
            <?= $cartJenis === 'catering'
              ? 'Menu catering dikelola langsung dapur desa dengan pengantaran khusus atau ambil di tempat.'
              : 'Produk kerajinan dan oleh-oleh UMKM siap dikirim bersamaan melalui kurir ekspedisi ke seluruh Indonesia.' ?>
          </span>
        </div>
      </div>
    <?php endif; ?>

    <div class="cart-layout-grid">
      <!-- LEFT: CART ITEMS LIST -->
      <div class="cart-items-column">
        <div class="cart-items-header">
          <span>Daftar Produk Terpilih (<span id="cart-item-badge-count"><?= count($items) ?></span> item)</span>
          <span class="cart-items-hint"><i class="fa-solid fa-shield-check"></i> Stok Terverifikasi</span>
        </div>

        <div class="keranjang-list" id="keranjang-list">
          <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
              <?php
              $isCatering = ($item['produk']['jenis'] ?? 'umkm') === 'catering';
              $minBeli = max(1, (int) ($item['min_beli'] ?? 1));
              ?>
              <div class="keranjang-item" data-cart-key="<?= esc($item['cart_key']) ?>" data-harga="<?= (float) $item['harga_satuan'] ?>">
                <!-- Thumbnail -->
                <div class="keranjang-item-img">
                  <?php if (!empty($item['produk']['gambar'])): ?>
                    <img src="<?= media_url($item['produk']['gambar']) ?>" alt="<?= esc($item['produk']['nama']) ?>" loading="lazy">
                  <?php else: ?>
                    <div class="keranjang-item-img-placeholder">
                      <i class="<?= $isCatering ? 'fa-solid fa-utensils' : 'fa-solid fa-box' ?>"></i>
                    </div>
                  <?php endif; ?>
                </div>

                <!-- Product Info & Actions -->
                <div class="keranjang-item-info">
                  <div class="keranjang-item-top">
                    <div class="item-meta-wrap">
                      <div class="item-badges-row">
                        <span class="badge-jenis <?= $isCatering ? 'badge-catering' : 'badge-umkm' ?>">
                          <?= $isCatering ? 'Catering' : 'UMKM' ?>
                        </span>
                        <?php if (!empty($item['varian'])): ?>
                          <span class="badge-tag-variant">
                            <i class="fa-solid fa-sliders"></i> Ukuran: <?= esc($item['varian']) ?>
                          </span>
                        <?php endif; ?>
                        <?php if ($minBeli > 1): ?>
                          <span class="badge-tag-min">
                            Min <?= $minBeli ?> pcs
                          </span>
                        <?php endif; ?>
                      </div>

                      <h4 class="keranjang-item-name">
                        <a href="<?= site_url('toko/' . ($item['produk']['slug'] ?? '')) ?>" class="item-title-link">
                          <?= esc($item['produk']['nama']) ?>
                        </a>
                      </h4>

                      <div class="keranjang-item-unit-price">
                        Harga Satuan: <strong><?= format_rupiah($item['harga_satuan']) ?></strong>
                        <?php if ($isCatering): ?>
                          <small>/ porsi</small>
                        <?php endif; ?>
                      </div>
                    </div>

                    <!-- Delete Button -->
                    <button type="button" class="keranjang-remove" title="Hapus produk dari keranjang" data-cart-key="<?= esc($item['cart_key']) ?>">
                      <i class="fa-regular fa-trash-can"></i>
                    </button>
                  </div>

                  <!-- Bottom: Stepper Qty & Line Total -->
                  <div class="keranjang-item-bottom">
                    <div class="qty-control-wrap">
                      <label class="qty-label">Jumlah:</label>
                      <div class="keranjang-qty-form">
                        <button type="button" class="keranjang-qty-btn" data-action="minus" aria-label="Kurangi Jumlah">
                          <i class="fa-solid fa-minus"></i>
                        </button>
                        <input type="number" value="<?= (int) $item['jumlah'] ?>"
                               min="<?= $minBeli ?>"
                               data-min="<?= $minBeli ?>"
                               class="form-control keranjang-qty-input" data-cart-key="<?= esc($item['cart_key']) ?>"
                               aria-label="Jumlah unit">
                        <button type="button" class="keranjang-qty-btn" data-action="plus" aria-label="Tambah Jumlah">
                          <i class="fa-solid fa-plus"></i>
                        </button>
                      </div>
                      <?php if ($minBeli > 1): ?>
                        <small class="min-qty-hint">Min. <?= $minBeli ?> pcs</small>
                      <?php endif; ?>
                    </div>

                    <div class="subtotal-wrap">
                      <span class="subtotal-label">Subtotal</span>
                      <div class="keranjang-item-subtotal" data-cart-key="<?= esc($item['cart_key']) ?>">
                        <?= format_rupiah($item['subtotal']) ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div class="cart-assurance-banner">
          <i class="fa-solid fa-circle-check"></i>
          <span>Stok produk diverifikasi langsung dari pengrajin dan dapur warga desa Papanbinangun.</span>
        </div>
      </div>

      <!-- RIGHT: STICKY ORDER SUMMARY -->
      <aside class="cart-summary-column">
        <div class="keranjang-summary" id="keranjang-summary">
          <h3 class="summary-card-title">Ringkasan Pesanan</h3>

          <div class="summary-rows-wrap">
            <div class="keranjang-summary-row">
              <span class="summary-label">Total Barang</span>
              <span class="summary-val"><strong id="keranjang-count"><?= count($items) ?></strong> item</span>
            </div>

            <div class="keranjang-summary-row">
              <span class="summary-label">Subtotal Produk</span>
              <span class="summary-val" id="summary-subtotal-val"><?= format_rupiah($subtotal ?? 0) ?></span>
            </div>

            <div class="keranjang-summary-row">
              <span class="summary-label">Biaya Pengiriman</span>
              <span class="summary-val text-muted" style="font-size:0.85rem">Dihitung di checkout</span>
            </div>

            <div class="summary-divider"></div>

            <div class="keranjang-summary-row summary-row-total">
              <div>
                <strong class="total-title">Total Belanja</strong>
                <small class="total-subtitle">Belum termasuk ongkos kirim</small>
              </div>
              <span class="price total-price" id="keranjang-total"><?= format_rupiah($subtotal ?? 0) ?></span>
            </div>
          </div>

          <a class="btn btn-primary btn-block btn-checkout" href="<?= site_url('checkout-produk') ?>">
            <i class="fa-solid fa-lock"></i> Lanjut ke Checkout
          </a>

          <a href="<?= site_url('toko') ?>" class="continue-shopping-link">
            <i class="fa-solid fa-arrow-left"></i> Kembali Lanjut Belanja
          </a>

          <!-- Trust Badges -->
          <div class="summary-trust-badges">
            <div class="trust-badge-item">
              <i class="fa-solid fa-shield-halved"></i>
              <span>Transaksi Aman &amp; Terverifikasi Midtrans</span>
            </div>
            <div class="trust-badge-item">
              <i class="fa-solid fa-truck-fast"></i>
              <span>Ekspedisi Resmi &amp; Pengiriman Terpercaya</span>
            </div>
            <div class="trust-badge-item">
              <i class="fa-solid fa-hand-holding-heart"></i>
              <span>Mendukung Langsung Perekonomian Warga Desa</span>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>

<!-- ==========================================================================
     STYLING KERANJANG BELANJA VINTAGE & RESPONSIVE
     ========================================================================== -->
<style>
/* Header Banner */
.cart-page-header {
  padding: 2rem 0 1.25rem;
  background: linear-gradient(180deg, rgba(232, 217, 192, 0.4) 0%, rgba(244, 233, 216, 0) 100%);
  border-bottom: 1px dashed var(--cream-dark, #e8d9c0);
  margin-bottom: 2rem;
}
.cart-header-inner {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 1.5rem;
  flex-wrap: wrap;
}
.cart-header-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.82rem;
  font-weight: 600;
  letter-spacing: 0.04em;
  color: var(--terracotta, #c1502e);
  background: rgba(193, 80, 46, 0.08);
  border: 1px solid rgba(193, 80, 46, 0.2);
  padding: 0.3rem 0.75rem;
  border-radius: 20px;
  margin-bottom: 0.4rem;
}
.cart-header-title {
  font-family: var(--font-heading, serif);
  font-size: clamp(1.85rem, 3.5vw, 2.5rem);
  font-weight: 700;
  color: var(--deep, #4a3222);
  line-height: 1.2;
  margin: 0.2rem 0 0.45rem;
}
.cart-header-desc {
  font-size: 0.96rem;
  color: var(--sepia, #8b5e3c);
  margin: 0;
  max-width: 600px;
}
.cart-back-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  white-space: nowrap;
}

/* Empty State */
.cart-empty-card {
  text-align: center;
  padding: 4rem 1.5rem;
  background: var(--white, #fffbf5);
  border: 1px dashed var(--gold, #c9a66b);
  border-radius: 14px;
  max-width: 580px;
  margin: 2rem auto;
}
.empty-basket-icon {
  width: 76px;
  height: 76px;
  border-radius: 50%;
  background: rgba(201, 166, 107, 0.15);
  color: var(--gold, #c9a66b);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.2rem;
  margin: 0 auto 1.25rem;
}
.empty-title {
  font-family: var(--font-heading, serif);
  font-size: 1.6rem;
  font-weight: 700;
  color: var(--deep, #4a3222);
  margin: 0 0 0.5rem;
}
.empty-subtitle {
  color: var(--sepia, #8b5e3c);
  font-size: 0.98rem;
  max-width: 440px;
  margin: 0 auto 1.75rem;
  line-height: 1.6;
}
.empty-actions {
  display: flex;
  justify-content: center;
  gap: 0.85rem;
  flex-wrap: wrap;
}

/* Cart Type Banner */
.cart-type-banner {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.9rem 1.25rem;
  border-radius: 10px;
  margin-bottom: 1.75rem;
  border: 1px solid transparent;
}
.banner-umkm {
  background: rgba(193, 80, 46, 0.08);
  border-color: rgba(193, 80, 46, 0.25);
  color: var(--deep, #4a3222);
}
.banner-catering {
  background: rgba(122, 132, 80, 0.1);
  border-color: rgba(122, 132, 80, 0.3);
  color: var(--deep, #4a3222);
}
.type-banner-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.15rem;
  flex-shrink: 0;
}
.banner-umkm .type-banner-icon {
  background: rgba(193, 80, 46, 0.15);
  color: var(--terracotta, #c1502e);
}
.banner-catering .type-banner-icon {
  background: rgba(122, 132, 80, 0.18);
  color: var(--olive, #7a8450);
}
.type-banner-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
  font-size: 0.88rem;
  line-height: 1.45;
}
.type-banner-text strong {
  font-size: 0.94rem;
  color: var(--deep, #4a3222);
}

/* 2-Column Grid Layout */
.cart-layout-grid {
  display: grid;
  grid-template-columns: 1fr 360px;
  gap: 2rem;
  align-items: flex-start;
}

/* Items List Column */
.cart-items-column {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.cart-items-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--deep, #4a3222);
  padding-bottom: 0.5rem;
  border-bottom: 1px solid var(--cream-dark, #e8d9c0);
}
.cart-items-hint {
  font-size: 0.82rem;
  color: var(--olive, #7a8450);
  font-weight: 500;
}

.keranjang-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

/* Individual Cart Item Card */
.keranjang-item {
  display: flex;
  gap: 1.15rem;
  background: var(--white, #fffbf5);
  border: 1px solid var(--cream-dark, #e8d9c0);
  border-radius: 12px;
  padding: 1.15rem 1.25rem;
  transition: box-shadow .25s ease, border-color .25s ease, opacity .3s, transform .3s;
  position: relative;
}
.keranjang-item:hover {
  border-color: var(--gold, #c9a66b);
  box-shadow: 0 4px 16px var(--shadow, rgba(74, 50, 34, 0.08));
}
.keranjang-item.is-removing {
  opacity: 0;
  transform: translateX(30px);
}
.keranjang-item.is-updating .keranjang-item-subtotal {
  opacity: 0.35;
}

/* Thumbnail */
.keranjang-item-img {
  width: 84px;
  min-width: 84px;
  height: 84px;
  border-radius: 8px;
  overflow: hidden;
  flex-shrink: 0;
  background: var(--cream, #f4e9d8);
  border: 1px solid var(--cream-dark, #e8d9c0);
}
.keranjang-item-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: sepia(0.08);
}
.keranjang-item-img-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--sepia, #8b5e3c);
  font-size: 1.5rem;
}

/* Content */
.keranjang-item-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  gap: 0.85rem;
  min-width: 0;
}
.keranjang-item-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 0.75rem;
}
.item-meta-wrap {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  min-width: 0;
}
.item-badges-row {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  flex-wrap: wrap;
}
.badge-tag-variant {
  font-size: 0.72rem;
  padding: 2px 7px;
  border-radius: 4px;
  background: rgba(43, 33, 24, 0.1);
  color: var(--deep, #4a3222);
  font-weight: 600;
}
.badge-tag-min {
  font-size: 0.72rem;
  padding: 2px 7px;
  border-radius: 4px;
  background: rgba(201, 166, 107, 0.25);
  color: var(--deep, #4a3222);
  font-weight: 600;
}

.keranjang-item-name {
  margin: 0;
  font-family: var(--font-heading, serif);
  font-size: 1.15rem;
  font-weight: 600;
  line-height: 1.3;
}
.item-title-link {
  color: var(--deep, #4a3222);
  text-decoration: none;
  transition: color 0.2s;
}
.item-title-link:hover {
  color: var(--terracotta, #c1502e);
}
.keranjang-item-unit-price {
  font-size: 0.84rem;
  color: var(--sepia, #8b5e3c);
}
.keranjang-item-unit-price strong {
  color: var(--deep, #4a3222);
}

.keranjang-remove {
  background: none;
  border: none;
  color: var(--sepia, #8b5e3c);
  opacity: 0.45;
  font-size: 1rem;
  cursor: pointer;
  padding: 0.35rem;
  border-radius: 6px;
  transition: all .2s;
  flex-shrink: 0;
}
.keranjang-remove:hover {
  opacity: 1;
  color: #b91c1c;
  background: rgba(185, 28, 28, 0.08);
}

/* Bottom Stepper & Subtotal */
.keranjang-item-bottom {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 1rem;
  padding-top: 0.65rem;
  border-top: 1px dashed rgba(232, 217, 192, 0.8);
}
.qty-control-wrap {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  flex-wrap: wrap;
}
.qty-label {
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--deep, #4a3222);
}
.keranjang-qty-form {
  display: inline-flex;
  align-items: center;
  background: var(--cream, #f4e9d8);
  border: 1px solid var(--cream-dark, #e8d9c0);
  border-radius: 8px;
  overflow: hidden;
}
.keranjang-qty-btn {
  width: 32px;
  height: 32px;
  border: none;
  background: transparent;
  color: var(--deep, #4a3222);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  transition: background .2s, color .2s;
}
.keranjang-qty-btn:hover {
  background: var(--cream-dark, #e8d9c0);
  color: var(--terracotta, #c1502e);
}
.keranjang-qty-input {
  width: 44px !important;
  height: 32px;
  text-align: center;
  border: none !important;
  border-left: 1px solid var(--cream-dark, #e8d9c0) !important;
  border-right: 1px solid var(--cream-dark, #e8d9c0) !important;
  border-radius: 0 !important;
  padding: 0 !important;
  font-family: var(--font-body, serif);
  font-weight: 600;
  font-size: 0.92rem;
  color: var(--deep, #4a3222);
  background: #ffffff !important;
  -moz-appearance: textfield;
}
.keranjang-qty-input::-webkit-inner-spin-button,
.keranjang-qty-input::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.min-qty-hint {
  font-size: 0.75rem;
  color: var(--sepia, #8b5e3c);
  font-style: italic;
}

.subtotal-wrap {
  text-align: right;
}
.subtotal-label {
  display: block;
  font-size: 0.76rem;
  color: var(--sepia, #8b5e3c);
  text-transform: uppercase;
  letter-spacing: 0.04em;
  margin-bottom: 2px;
}
.keranjang-item-subtotal {
  font-family: var(--font-heading, serif);
  font-size: 1.28rem;
  font-weight: 700;
  color: var(--terracotta, #c1502e);
  white-space: nowrap;
  transition: opacity .2s;
}

.cart-assurance-banner {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  background: rgba(201, 166, 107, 0.12);
  color: var(--deep, #4a3222);
  font-size: 0.84rem;
}
.cart-assurance-banner i {
  color: var(--gold, #c9a66b);
  font-size: 1rem;
}

/* Right Sticky Summary Card */
.cart-summary-column {
  position: sticky;
  top: calc(var(--header-h, 70px) + 1.5rem);
}
.keranjang-summary {
  background: var(--white, #fffbf5);
  border: 1px solid var(--gold, #c9a66b);
  border-radius: 14px;
  box-shadow: 0 6px 22px var(--shadow, rgba(74, 50, 34, 0.08));
  padding: 1.5rem;
}
.summary-card-title {
  font-family: var(--font-heading, serif);
  font-size: 1.35rem;
  font-weight: 700;
  color: var(--deep, #4a3222);
  margin: 0 0 1.25rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid var(--cream-dark, #e8d9c0);
}
.summary-rows-wrap {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}
.keranjang-summary-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.92rem;
}
.summary-label {
  color: var(--sepia, #8b5e3c);
}
.summary-val {
  font-weight: 600;
  color: var(--deep, #4a3222);
}
.summary-divider {
  height: 1px;
  border-top: 1px dashed var(--cream-dark, #e8d9c0);
  margin: 0.4rem 0;
}
.summary-row-total {
  align-items: baseline;
  padding-top: 0.4rem;
}
.total-title {
  font-family: var(--font-heading, serif);
  font-size: 1.15rem;
  color: var(--deep, #4a3222);
  display: block;
}
.total-subtitle {
  display: block;
  font-size: 0.74rem;
  color: var(--sepia, #8b5e3c);
}
.total-price {
  font-family: var(--font-heading, serif);
  font-size: 1.55rem;
  font-weight: 700;
  color: var(--terracotta, #c1502e);
}

.btn-checkout {
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.55rem;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 8px;
  box-shadow: 0 3px 10px rgba(193, 80, 46, 0.28);
}
.btn-checkout:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(193, 80, 46, 0.38);
}

.continue-shopping-link {
  display: block;
  text-align: center;
  margin-top: 1rem;
  font-size: 0.88rem;
  color: var(--sepia, #8b5e3c);
  text-decoration: none;
  transition: color 0.2s;
}
.continue-shopping-link:hover {
  color: var(--terracotta, #c1502e);
}

/* Trust Badges */
.summary-trust-badges {
  margin-top: 1.5rem;
  padding-top: 1.25rem;
  border-top: 1px solid var(--cream-dark, #e8d9c0);
  display: flex;
  flex-direction: column;
  gap: 0.65rem;
}
.trust-badge-item {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-size: 0.78rem;
  color: var(--sepia, #8b5e3c);
  line-height: 1.35;
}
.trust-badge-item i {
  color: var(--gold, #c9a66b);
  font-size: 0.95rem;
  flex-shrink: 0;
}

/* ==========================================================================
   MOBILE & RESPONSIVE REFINEMENTS
   ========================================================================== */
@media (max-width: 900px) {
  .cart-layout-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  .cart-summary-column {
    position: static;
  }
}

@media (max-width: 640px) {
  .cart-page-header {
    padding: 1.25rem 0 0.85rem;
    margin-bottom: 1.25rem;
  }
  .cart-header-inner {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.75rem;
  }
  .cart-back-btn {
    width: 100%;
    justify-content: center;
  }

  .keranjang-item {
    gap: 0.75rem;
    padding: 0.9rem;
  }
  .keranjang-item-img {
    width: 70px;
    min-width: 70px;
    height: 70px;
  }
  .keranjang-item-name {
    font-size: 1.02rem;
  }
  .keranjang-item-bottom {
    flex-direction: column;
    align-items: stretch;
    gap: 0.75rem;
  }
  .qty-control-wrap {
    justify-content: space-between;
  }
  .subtotal-wrap {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding-top: 0.4rem;
    border-top: 1px dashed rgba(232, 217, 192, 0.5);
  }
  .keranjang-item-subtotal {
    font-size: 1.15rem;
  }

  .keranjang-summary {
    padding: 1.25rem 1rem;
  }
  .total-price {
    font-size: 1.35rem;
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
    var badgeCountEl = document.getElementById('cart-item-badge-count');
    var totalEl = document.getElementById('keranjang-total');
    var subtotalValEl = document.getElementById('summary-subtotal-val');
    var headerCartCount = document.getElementById('headerCartCount');

    if (countEl) countEl.textContent = data.count;
    if (badgeCountEl) badgeCountEl.textContent = data.count;
    if (totalEl) totalEl.textContent = data.subtotal_fmt;
    if (subtotalValEl) subtotalValEl.textContent = data.subtotal_fmt;

    if (headerCartCount) {
      if (data.count > 0) {
        headerCartCount.textContent = data.count;
        headerCartCount.style.display = '';
      } else {
        headerCartCount.style.display = 'none';
      }
    }

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
    var badgeCountEl = document.getElementById('cart-item-badge-count');
    var totalEl = document.getElementById('keranjang-total');
    var subtotalValEl = document.getElementById('summary-subtotal-val');

    if (countEl) countEl.textContent = count;
    if (badgeCountEl) badgeCountEl.textContent = count;
    if (totalEl) totalEl.textContent = formatRupiah(total);
    if (subtotalValEl) subtotalValEl.textContent = formatRupiah(total);
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
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: 'Gagal mengupdate keranjang.',
          timer: 2000,
          showConfirmButton: false
        });
      }
    });
  }

  function ajaxRemove(cartKey) {
    var row = document.querySelector('.keranjang-item[data-cart-key="' + cartKey + '"]');

    Swal.fire({
      title: 'Hapus dari keranjang?',
      text: 'Produk ini akan dikeluarkan dari keranjang belanja Anda.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#c1502e',
      cancelButtonColor: '#8b5e3c'
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
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Gagal menghapus produk.',
            timer: 2000,
            showConfirmButton: false
          });
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
