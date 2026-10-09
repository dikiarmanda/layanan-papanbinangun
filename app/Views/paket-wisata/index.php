<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$q = $q ?? '';
$ketersediaan = $ketersediaan ?? [];
?>
<section class="section container">
  <h1 class="section-title"><?= esc($title) ?></h1>

  <form method="get" action="<?= site_url('paket-wisata') ?>" class="katalog-filter" role="search">
    <?php if (!empty($activeJenis)): ?>
      <input type="hidden" name="jenis" value="<?= esc($activeJenis) ?>">
    <?php endif; ?>
    <div class="filter-tabs katalog-tabs">
      <a href="<?= site_url('paket-wisata') ?>" class="<?= empty($activeJenis) ? 'is-active' : '' ?>">Semua</a>
      <a href="<?= site_url('paket-wisata?jenis=wisata') ?>"
        class="<?= ($activeJenis ?? '') === 'wisata' ? 'is-active' : '' ?>">Paket Wisata</a>
      <a href="<?= site_url('paket-wisata?jenis=menginap') ?>"
        class="<?= ($activeJenis ?? '') === 'menginap' ? 'is-active' : '' ?>">Homestay &amp; Camping</a>
    </div>
    <div class="katalog-search">
      <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
      <input type="search" name="q" class="form-control" placeholder="Cari paket, homestay, atau camping…"
        value="<?= esc($q) ?>">
      <button type="submit" class="btn btn-primary">Cari</button>
    </div>
  </form>

  <?php if ($q !== ''): ?>
    <p class="katalog-count"><?= count($paket) ?> hasil untuk pencarian &ldquo;<?= esc($q) ?>&rdquo;</p>
  <?php endif; ?>

  <?php if (empty($paket)): ?>
    <p class="text-center" style="color:var(--sepia)">
      <?= $q !== '' ? 'Tidak ditemukan paket yang cocok dengan pencarian Anda.' : 'Belum ada paket untuk filter ini.' ?>
    </p>
    <?php if ($q !== ''): ?>
      <p class="text-center"><a class="btn btn-outline" href="<?= site_url('paket-wisata') ?>">Tampilkan semua paket</a></p>
    <?php endif; ?>
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
          $isMenginap = is_paket_menginap($jenisPaket);
          $avail = $ketersediaan[(int) $p['id']] ?? ['total' => 0, 'available' => 0, 'remaining' => null];
          $next = !empty($avail['remaining']) ? format_tanggal($avail['remaining']) : null;
        ?>
        <a class="card" href="<?= site_url('paket-wisata/' . $p['slug']) ?>">
          <img class="card-img" src="<?= esc(cover_url($p['gambar_cover'] ?? null, $jenisPaket)) ?>" alt="<?= esc($p['nama']) ?>">
          <div class="card-body">
            <div class="card-badges">
              <span class="badge-jenis <?= $badgeClass ?>"><?= esc(label_jenis_paket($jenisPaket)) ?></span>
              <?php if ((int) $avail['available'] > 0): ?>
                <span class="badge badge-available">Tersedia</span>
              <?php else: ?>
                <span class="badge badge-soldout">Penuh</span>
              <?php endif; ?>
            </div>
            <h3><?= esc($p['nama']) ?></h3>
            <p style="color:var(--sepia);font-size:0.9rem"><?= esc(mb_substr(teks_plain($p['deskripsi']), 0, 90)) ?>…</p>
            <div class="price">
              <?= format_rupiah($p['harga']) ?>
              <small style="font-weight:400;color:var(--sepia)">/ <?= esc(satuan_label($jenisPaket, $p['satuan_harga'] ?? null)) ?></small>
            </div>
            <div class="card-avail">
              <?php if ((int) $avail['available'] > 0): ?>
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                <span><?= $isMenginap ? 'Bisa menginap' : 'Slot tersedia' ?> — terdekat <?= esc($next ?? '-') ?></span>
              <?php else: ?>
                <i class="fa-solid fa-circle-xmark" aria-hidden="true"></i>
                <span>Belum ada jadwal tersedia</span>
              <?php endif; ?>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?= $this->endSection() ?>
