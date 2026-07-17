<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<form method="post" enctype="multipart/form-data" action="<?= $produk ? site_url('admin/produk/' . $produk['id']) : site_url('admin/produk') ?>" style="max-width:640px">
  <?= csrf_field() ?>
  <div class="form-group"><label>Nama</label><input name="nama" class="form-control" required value="<?= esc($produk['nama'] ?? '') ?>"></div>
  <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control" rows="5" required><?= esc($produk['deskripsi'] ?? '') ?></textarea></div>
  <div class="form-group"><label>Harga</label><input type="number" name="harga" class="form-control" required value="<?= esc($produk['harga'] ?? '') ?>"></div>
  <div class="form-group"><label>Stok</label><input type="number" name="stok" class="form-control" required value="<?= esc($produk['stok'] ?? '0') ?>"></div>
  <div class="form-group"><label>Berat (gram)</label><input type="number" name="berat" class="form-control" value="<?= esc($produk['berat'] ?? '1000') ?>"></div>
  <div class="form-group">
    <label>Kategori</label>
    <select name="kategori_id" class="form-control">
      <option value="">—</option>
      <?php foreach ($kategori as $k): ?>
        <option value="<?= (int) $k['id'] ?>" <?= (string)($produk['kategori_id'] ?? '') === (string)$k['id'] ? 'selected' : '' ?>><?= esc($k['nama']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
    <label>Status</label>
    <select name="status" class="form-control">
      <option value="draft" <?= ($produk['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
      <option value="publish" <?= ($produk['status'] ?? '') === 'publish' ? 'selected' : '' ?>>Publish</option>
    </select>
  </div>
  <div class="form-group"><label>Gambar</label><input type="file" name="gambar" accept="image/*" class="form-control"></div>
  <?php if (! empty($produk['gambar'])): ?>
    <img src="<?= media_url($produk['gambar']) ?>" style="max-width:200px;margin-bottom:1rem">
  <?php endif; ?>
  <button class="btn btn-primary" type="submit">Simpan</button>
</form>
<?= $this->endSection() ?>
