<?php if (!empty($q)): ?>
  <div style="text-align:center;margin-bottom:1.5rem;color:var(--sepia)">
    Menampilkan hasil pencarian untuk: <strong>"<?= esc($q) ?>"</strong> (<?= count($produk) ?> produk ditemukan)
  </div>
<?php endif; ?>

<?php if (empty($produk)): ?>
  <div style="text-align:center;padding:3rem 1rem;color:var(--sepia)">
    <i class="fas fa-box-open" style="font-size:3rem;color:var(--gold);margin-bottom:1rem;display:block"></i>
    <p style="font-size:1.1rem">Tidak ada produk yang cocok dengan pencarian atau filter ini.</p>
    <?php if (!empty($q) || !empty($activeKat) || !empty($activeJenis)): ?>
      <a href="<?= site_url('toko') ?>" class="btn btn-outline" style="margin-top:0.75rem">Lihat Semua Produk</a>
    <?php endif; ?>
  </div>
<?php else: ?>
  <div class="card-grid">
    <?php foreach ($produk as $pr): ?>
      <?php $isCatering = ($pr['jenis'] ?? '') === 'catering'; ?>
      <a class="card" href="<?= site_url('toko/' . $pr['slug']) ?>">
        <?php if (!empty($pr['gambar'])): ?>
          <img class="card-img" src="<?= media_url($pr['gambar']) ?>" alt="<?= esc($pr['nama']) ?>">
        <?php else: ?>
          <div class="card-img" style="display:flex;align-items:center;justify-content:center;color:var(--sepia)">
            <?= $isCatering ? 'Catering' : 'UMKM' ?>
          </div>
        <?php endif; ?>
        <div class="card-body">
          <span class="badge-jenis <?= $isCatering ? 'badge-catering' : 'badge-umkm' ?>">
            <?= $isCatering ? 'Catering' : 'UMKM' ?>
          </span>
          <h3><?= esc($pr['nama']) ?></h3>
          <div class="price"><?= format_rupiah(harga_produk_akhir($pr['harga'])) ?></div>
          <small style="color:var(--sepia)">Stok: <?= (int) $pr['stok'] ?></small>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
