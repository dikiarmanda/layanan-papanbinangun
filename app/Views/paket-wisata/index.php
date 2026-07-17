<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container">
  <h1 class="section-title">Paket Wisata &amp; Homestay</h1>
  <?php if (empty($paket)): ?>
    <p class="text-center" style="color:var(--sepia)">Belum ada paket.</p>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($paket as $p): ?>
        <a class="card" href="<?= site_url('paket-wisata/' . $p['slug']) ?>">
          <?php if (! empty($p['gambar_cover'])): ?>
            <img class="card-img" src="<?= media_url($p['gambar_cover']) ?>" alt="<?= esc($p['nama']) ?>">
          <?php else: ?>
            <div class="card-img" style="display:flex;align-items:center;justify-content:center;color:var(--sepia)">Paket</div>
          <?php endif; ?>
          <div class="card-body">
            <h3><?= esc($p['nama']) ?></h3>
            <p style="color:var(--sepia);font-size:0.9rem"><?= esc(mb_substr(strip_tags($p['deskripsi']), 0, 90)) ?>…</p>
            <div class="price"><?= format_rupiah($p['harga']) ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?= $this->endSection() ?>
