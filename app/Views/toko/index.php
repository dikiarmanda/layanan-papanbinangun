<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container">
  <h1 class="section-title"><?= esc($title) ?></h1>

  <div class="filter-tabs">
    <a href="<?= site_url('toko') ?>" class="<?= empty($activeJenis) ? 'is-active' : '' ?>">Semua</a>
    <a href="<?= site_url('toko?jenis=umkm') ?>"
      class="<?= ($activeJenis ?? '') === 'umkm' ? 'is-active' : '' ?>">Produk UMKM</a>
    <a href="<?= site_url('toko?jenis=catering') ?>"
      class="<?= ($activeJenis ?? '') === 'catering' ? 'is-active' : '' ?>">Catering</a>
  </div>

  <?php if (!empty($kategori)): ?>
    <div style="display:flex;gap:0.6rem;flex-wrap:wrap;justify-content:center;margin-bottom:1.5rem">
      <?php
      $base = 'toko';
      $qs = [];
      if (!empty($activeJenis)) {
        $qs[] = 'jenis=' . urlencode((string) $activeJenis);
      }
      ?>
      <a class="btn btn-sm <?= empty($activeKat) ? 'btn-primary' : 'btn-outline' ?>"
        href="<?= site_url($base . ($qs ? '?' . implode('&', $qs) : '')) ?>">Semua kategori</a>
      <?php foreach ($kategori as $k): ?>
        <?php
        $kQs = $qs;
        $kQs[] = 'kategori=' . (int) $k['id'];
        ?>
        <a class="btn btn-sm <?= (string) ($activeKat ?? '') === (string) $k['id'] ? 'btn-primary' : 'btn-outline' ?>"
          href="<?= site_url('toko?' . implode('&', $kQs)) ?>"><?= esc($k['nama']) ?></a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if (empty($produk)): ?>
    <p class="text-center" style="color:var(--sepia)">Belum ada produk untuk filter ini.</p>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($produk as $pr): ?>
        <?php $isCatering = ($pr['jenis'] ?? '') === 'catering'; ?>
        <a class="card" href="<?= site_url('toko/' . $pr['slug']) ?>">
          <?php if (!empty($pr['gambar'])): ?>
            <img class="card-img" src="<?= media_url($pr['gambar']) ?>" alt="<?= esc($pr['nama']) ?>">
          <?php else: ?>
            <div class="card-img" style="display:flex;align-items:center;justify-content:center;color:var(--sepia)">
              <?= $isCatering ? 'Catering' : 'UMKM' ?></div>
          <?php endif; ?>
          <div class="card-body">
            <span
              class="badge-jenis <?= $isCatering ? 'badge-catering' : 'badge-umkm' ?>"><?= $isCatering ? 'Catering' : 'UMKM' ?></span>
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