<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container">
  <div style="display:grid;gap:2rem;grid-template-columns:1fr">
    <?php if (! empty($produk['gambar'])): ?>
      <img src="<?= media_url($produk['gambar']) ?>" alt="<?= esc($produk['nama']) ?>" style="width:100%;max-height:420px;object-fit:cover;border-radius:6px">
    <?php endif; ?>
    <div>
      <h1><?= esc($produk['nama']) ?></h1>
      <div class="price" style="margin-bottom:0.75rem"><?= format_rupiah($produk['harga']) ?></div>
      <p style="color:var(--sepia)">Stok: <?= (int) $produk['stok'] ?></p>
      <div style="white-space:pre-wrap;margin:1rem 0 1.5rem"><?= esc($produk['deskripsi']) ?></div>
      <?php if ((int) $produk['stok'] > 0): ?>
        <form method="post" action="<?= site_url('keranjang/add') ?>" style="display:flex;gap:0.75rem;align-items:end;flex-wrap:wrap">
          <?= csrf_field() ?>
          <input type="hidden" name="produk_id" value="<?= (int) $produk['id'] ?>">
          <div class="form-group" style="margin:0">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" min="1" max="<?= (int) $produk['stok'] ?>" value="1" style="width:100px">
          </div>
          <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
        </form>
      <?php else: ?>
        <div class="alert alert-error">Stok habis</div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?= $this->endSection() ?>
