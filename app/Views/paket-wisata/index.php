<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container">
  <h1 class="section-title"><?= esc($title) ?></h1>
  <div class="filter-tabs">
    <a href="<?= site_url('paket-wisata') ?>" class="<?= empty($activeJenis) ? 'is-active' : '' ?>">Semua</a>
    <a href="<?= site_url('paket-wisata?jenis=wisata') ?>"
      class="<?= ($activeJenis ?? '') === 'wisata' ? 'is-active' : '' ?>">Paket Wisata</a>
    <a href="<?= site_url('paket-wisata?jenis=menginap') ?>"
      class="<?= ($activeJenis ?? '') === 'menginap' ? 'is-active' : '' ?>">Homestay &amp; Camping</a>
  </div>

  <?php if (empty($paket)): ?>
    <p class="text-center" style="color:var(--sepia)">Belum ada paket untuk filter ini.</p>
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
        ?>
        <a class="card" href="<?= site_url('paket-wisata/' . $p['slug']) ?>">
          <img class="card-img" src="<?= esc(cover_url($p['gambar_cover'] ?? null, $jenisPaket)) ?>" alt="<?= esc($p['nama']) ?>">
          <div class="card-body">
            <span class="badge-jenis <?= $badgeClass ?>"><?= esc(label_jenis_paket($jenisPaket)) ?></span>
            <h3><?= esc($p['nama']) ?></h3>
            <p style="color:var(--sepia);font-size:0.9rem"><?= esc(mb_substr(strip_tags($p['deskripsi']), 0, 90)) ?>…</p>
            <div class="price">
              <?= format_rupiah($p['harga']) ?>
              <small style="font-weight:400;color:var(--sepia)">/ <?= esc(satuan_label($jenisPaket, $p['satuan_harga'] ?? null)) ?></small>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?= $this->endSection() ?>
