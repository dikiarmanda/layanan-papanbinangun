<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php $isCatering = ($produk['jenis'] ?? '') === 'catering'; ?>
<section class="section container">
  <div class="booking-layout">
    <div>
      <?php if (!empty($produk['gambar'])): ?>
        <img src="<?= media_url($produk['gambar']) ?>" alt="<?= esc($produk['nama']) ?>"
          style="width:100%;max-height:420px;object-fit:cover;border-radius:8px">
      <?php endif; ?>
      <span class="badge-jenis <?= $isCatering ? 'badge-catering' : 'badge-umkm' ?>" style="margin-top:1.25rem">
        <?= $isCatering ? 'Catering' : 'Produk UMKM' ?>
      </span>
      <h1 style="margin-top:0.5rem"><?= esc($produk['nama']) ?></h1>
      <div class="price" style="margin-bottom:0.75rem"><?= format_rupiah($produk['harga']) ?></div>
      <p style="color:var(--sepia)">Stok: <?= (int) $produk['stok'] ?></p>
      <?php if ($isCatering): ?>
        <div class="alert alert-info" style="margin-top:1rem">
          Catering hanya tersedia untuk ambil di tempat atau antar lokal. Wajib mengisi tanggal &amp; waktu acara saat
          checkout.
        </div>
      <?php endif; ?>
      <div style="white-space:pre-wrap;margin:1rem 0"><?= esc($produk['deskripsi']) ?></div>
    </div>

    <div class="booking-card">
      <h2>Tambah ke keranjang</h2>
      <p class="hint">Keranjang tidak boleh mencampur UMKM dan catering.</p>
      <?php if ((int) $produk['stok'] > 0): ?>
        <form method="post" action="<?= site_url('keranjang/add') ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="produk_id" value="<?= (int) $produk['id'] ?>">
          <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" min="1" max="<?= (int) $produk['stok'] ?>" value="1"
              required>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%">Tambah ke Keranjang</button>
        </form>
      <?php else: ?>
        <div class="alert alert-error">Stok habis</div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?= $this->endSection() ?>