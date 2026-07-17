<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<section class="service-hero">
  <div class="service-hero-pattern" aria-hidden="true"></div>
  <div class="container service-hero-inner">
    <?php $site = pengaturan(); ?>
    <span class="stamp">Layanan <?= esc($site['nama_desa']) ?></span>
    <h1><?= esc($site['tagline'] ?: 'Jelajahi Desa, Bawa Pulang Ceritanya') ?></h1>
    <p><?= esc(strip_tags((string) ($site['deskripsi_singkat'] ?: 'Pesan pengalaman wisata dan homestay, lalu temukan karya terbaik UMKM desa dalam satu layanan.'))) ?></p>
    <div class="hero-actions">
      <a class="btn btn-primary" href="<?= site_url('paket-wisata') ?>">Lihat Paket Wisata</a>
      <a class="btn btn-cream" href="<?= site_url('toko') ?>">Belanja Produk UMKM</a>
    </div>
  </div>
</section>

<section class="section container">
  <h2 class="section-title">Paket Unggulan</h2>
  <?php if (empty($paket)): ?>
    <p class="text-center" style="color:var(--sepia)">Belum ada paket dipublikasikan.</p>
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
            <div class="price"><?= format_rupiah($p['harga']) ?> <small style="font-weight:400;color:var(--sepia)">/ <?= esc(str_replace('_', ' ', $p['satuan_harga'])) ?></small></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<section class="section container">
  <h2 class="section-title">Produk UMKM Unggulan</h2>
  <?php if (empty($produk)): ?>
    <p class="text-center" style="color:var(--sepia)">Belum ada produk dipublikasikan.</p>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($produk as $pr): ?>
        <a class="card" href="<?= site_url('toko/' . $pr['slug']) ?>">
          <?php if (! empty($pr['gambar'])): ?>
            <img class="card-img" src="<?= media_url($pr['gambar']) ?>" alt="<?= esc($pr['nama']) ?>">
          <?php else: ?>
            <div class="card-img" style="display:flex;align-items:center;justify-content:center;color:var(--sepia)">Produk</div>
          <?php endif; ?>
          <div class="card-body">
            <h3><?= esc($pr['nama']) ?></h3>
            <div class="price"><?= format_rupiah($pr['harga']) ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<section class="section container" id="cek-status">
  <h2 class="section-title">Cek Status Transaksi</h2>
  <form method="get" onsubmit="event.preventDefault(); const k=this.kode.value.trim(); if(k) location.href='<?= site_url('status') ?>/'+encodeURIComponent(k);" style="max-width:480px;margin:0 auto">
    <div class="form-group">
      <label for="kode">Kode transaksi (RSV-… atau ORD-…)</label>
      <input class="form-control" type="text" name="kode" id="kode" placeholder="RSV-xxxx atau ORD-xxxx" required>
    </div>
    <button class="btn btn-primary" type="submit" style="width:100%">Cek Status</button>
  </form>
</section>

<?= $this->endSection() ?>
