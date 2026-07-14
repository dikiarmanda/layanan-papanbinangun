<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container">
  <h1 class="section-title">Toko UMKM Desa</h1>

  <?php if (! empty($kategori)): ?>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;justify-content:center;margin-bottom:1.5rem">
      <a class="btn btn-sm <?= empty($activeKat) ? 'btn-primary' : 'btn-outline' ?>" href="<?= site_url('toko') ?>">Semua</a>
      <?php foreach ($kategori as $k): ?>
        <a class="btn btn-sm <?= (string) $activeKat === (string) $k['id'] ? 'btn-primary' : 'btn-outline' ?>" href="<?= site_url('toko?kategori=' . $k['id']) ?>"><?= esc($k['nama']) ?></a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if (empty($produk)): ?>
    <p class="text-center" style="color:var(--sepia)">Belum ada produk.</p>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($produk as $pr): ?>
        <a class="card" href="<?= site_url('toko/' . $pr['slug']) ?>">
          <?php if (! empty($pr['gambar'])): ?>
            <img class="card-img" src="<?= base_url($pr['gambar']) ?>" alt="<?= esc($pr['nama']) ?>">
          <?php else: ?>
            <div class="card-img" style="display:flex;align-items:center;justify-content:center;color:var(--sepia)">Produk</div>
          <?php endif; ?>
          <div class="card-body">
            <h3><?= esc($pr['nama']) ?></h3>
            <div class="price"><?= format_rupiah($pr['harga']) ?></div>
            <small style="color:var(--sepia)">Stok: <?= (int) $pr['stok'] ?></small>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?= $this->endSection() ?>
