<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container">
  <h1 class="section-title"><?= esc($title) ?></h1>
  <div class="filter-tabs">
    <a href="<?= site_url('paket-wisata') ?>" class="<?= empty($activeJenis) ? 'is-active' : '' ?>">Semua</a>
    <a href="<?= site_url('paket-wisata?jenis=wisata') ?>"
      class="<?= ($activeJenis ?? '') === 'wisata' ? 'is-active' : '' ?>">Paket Wisata</a>
    <a href="<?= site_url('paket-wisata?jenis=homestay') ?>"
      class="<?= ($activeJenis ?? '') === 'homestay' ? 'is-active' : '' ?>">Homestay</a>
  </div>

  <?php if (empty($paket)): ?>
    <p class="text-center" style="color:var(--sepia)">Belum ada paket untuk filter ini.</p>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($paket as $p): ?>
        <?php $isHomestay = ($p['jenis'] ?? '') === 'homestay'; ?>
        <a class="card" href="<?= site_url('paket-wisata/' . $p['slug']) ?>">
          <?php if (!empty($p['gambar_cover'])): ?>
            <img class="card-img" src="<?= media_url($p['gambar_cover']) ?>" alt="<?= esc($p['nama']) ?>">
          <?php else: ?>
            <div class="card-img" style="display:flex;align-items:center;justify-content:center;color:var(--sepia)">
              <?= $isHomestay ? 'Homestay' : 'Wisata' ?></div>
          <?php endif; ?>
          <div class="card-body">
            <span
              class="badge-jenis <?= $isHomestay ? 'badge-homestay' : 'badge-wisata' ?>"><?= $isHomestay ? 'Homestay' : 'Wisata' ?></span>
            <h3><?= esc($p['nama']) ?></h3>
            <p style="color:var(--sepia);font-size:0.9rem"><?= esc(mb_substr(strip_tags($p['deskripsi']), 0, 90)) ?>…</p>
            <div class="price">
              <?= format_rupiah($p['harga']) ?>
              <small style="font-weight:400;color:var(--sepia)">/ <?= $isHomestay ? 'malam' : 'orang' ?></small>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?= $this->endSection() ?>