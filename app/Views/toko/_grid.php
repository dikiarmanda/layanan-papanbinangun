<?php
$hasFilter = !empty($q) || !empty($activeJenis) || !empty($activeKat);
?>

<div class="toko-grid-header">
  <div class="toko-results-count">
    <span>Menampilkan <strong><?= count($produk) ?></strong> produk &amp; hidangan desa</span>
    <?php if ($hasFilter): ?>
      <span class="toko-filter-indicator">• Hasil tersaring</span>
    <?php endif; ?>
  </div>

  <?php if ($hasFilter): ?>
    <div class="active-filter-chips">
      <?php if (!empty($q)): ?>
        <button type="button" class="filter-chip chip-remove-q" title="Hapus pencarian teks">
          <i class="fas fa-search"></i> "<?= esc($q) ?>"
          <i class="fas fa-times chip-close"></i>
        </button>
      <?php endif; ?>
      <?php if (!empty($activeJenis)): ?>
        <button type="button" class="filter-chip chip-remove-jenis" title="Hapus filter jenis">
          <i class="fas fa-tag"></i> <?= $activeJenis === 'catering' ? 'Catering' : 'Produk UMKM' ?>
          <i class="fas fa-times chip-close"></i>
        </button>
      <?php endif; ?>
      <?php if (!empty($activeKat)): ?>
        <button type="button" class="filter-chip chip-remove-kategori" title="Hapus filter kategori">
          <i class="fas fa-layer-group"></i> Kategori terpilih
          <i class="fas fa-times chip-close"></i>
        </button>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>

<?php if (empty($produk)): ?>
  <div class="toko-empty-state">
    <div class="empty-icon-wrap">
      <i class="fa-solid fa-wheat-awn"></i>
    </div>
    <h3>Produk Tidak Ditemukan</h3>
    <p>Belum ada produk atau hidangan yang cocok dengan kriteria pencarian dan filter Anda saat ini.</p>
    <?php if ($hasFilter): ?>
      <button type="button" class="btn btn-outline btn-reset-all" style="margin-top:0.75rem">
        <i class="fas fa-rotate-left"></i> Bersihkan Semua Filter
      </button>
    <?php endif; ?>
  </div>
<?php else: ?>
  <div class="toko-products-grid">
    <?php foreach ($produk as $pr): ?>
      <?php
      $isCatering = ($pr['jenis'] ?? '') === 'catering';
      $varianArr = !empty($pr['opsi_varian']) ? json_decode($pr['opsi_varian'], true) : [];
      $minBeli = (int) ($pr['min_beli'] ?? 1);
      $stok = (int) $pr['stok'];
      ?>
      <article class="toko-card <?= $stok <= 0 ? 'is-out-of-stock' : '' ?>">
        <a href="<?= site_url('toko/' . $pr['slug']) ?>" class="toko-card-link" aria-label="<?= esc($pr['nama']) ?>">
          <div class="toko-card-media">
            <?php if (!empty($pr['gambar'])): ?>
              <img src="<?= media_url($pr['gambar']) ?>" alt="<?= esc($pr['nama']) ?>" loading="lazy">
            <?php else: ?>
              <div class="toko-media-placeholder">
                <i class="<?= $isCatering ? 'fas fa-utensils' : 'fas fa-gift' ?>"></i>
                <span><?= $isCatering ? 'Catering Desa' : 'Karya UMKM' ?></span>
              </div>
            <?php endif; ?>

            <!-- BADGE OVERLAY -->
            <div class="toko-badges-overlay">
              <span class="toko-badge-pill <?= $isCatering ? 'badge-pill-catering' : 'badge-pill-umkm' ?>">
                <i class="<?= $isCatering ? 'fas fa-utensils' : 'fas fa-store' ?>"></i>
                <?= $isCatering ? 'Catering' : 'UMKM' ?>
              </span>

              <?php if (!empty($varianArr)): ?>
                <span class="toko-badge-pill badge-pill-variant">
                  <i class="fas fa-sliders"></i> <?= count($varianArr) ?> Ukuran
                </span>
              <?php elseif ($minBeli > 1): ?>
                <span class="toko-badge-pill badge-pill-min">
                  Min <?= $minBeli ?> pcs
                </span>
              <?php endif; ?>
            </div>
          </div>

          <div class="toko-card-content">
            <?php if (!empty($pr['kategori_nama'])): ?>
              <div class="toko-card-category"><?= esc($pr['kategori_nama']) ?></div>
            <?php endif; ?>

            <h3 class="toko-card-title"><?= esc($pr['nama']) ?></h3>

            <div class="toko-card-footer">
              <div class="toko-price-wrap">
                <span class="toko-price-val"><?= format_rupiah(harga_produk_akhir($pr['harga'])) ?></span>
                <?php if ($isCatering): ?>
                  <span class="toko-unit-hint">/ porsi</span>
                <?php endif; ?>
              </div>

              <div class="toko-stock-status">
                <?php if ($stok > 0): ?>
                  <span class="stock-ready"><i class="fas fa-check-circle"></i> Stok <?= $stok ?></span>
                <?php else: ?>
                  <span class="stock-empty"><i class="fas fa-times-circle"></i> Habis</span>
                <?php endif; ?>
              </div>
            </div>

            <div class="toko-card-action">
              <span class="btn-card-view">
                Lihat Detail <i class="fas fa-arrow-right"></i>
              </span>
            </div>
          </div>
        </a>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
