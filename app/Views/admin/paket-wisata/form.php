<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<form method="post" enctype="multipart/form-data" action="<?= $paket ? site_url('admin/paket-wisata/' . $paket['id']) : site_url('admin/paket-wisata') ?>" style="max-width:640px">
  <?= csrf_field() ?>
  <div class="form-group"><label>Nama</label><input name="nama" class="form-control" required value="<?= esc($paket['nama'] ?? '') ?>"></div>
  <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control" rows="5" required><?= esc($paket['deskripsi'] ?? '') ?></textarea></div>
  <div class="form-group"><label>Harga</label><input type="number" name="harga" class="form-control" required value="<?= esc($paket['harga'] ?? '') ?>"></div>
  <div class="form-group">
    <label>Satuan harga</label>
    <select name="satuan_harga" class="form-control">
      <option value="per_orang" <?= ($paket['satuan_harga'] ?? '') === 'per_orang' ? 'selected' : '' ?>>Per orang</option>
      <option value="per_paket" <?= ($paket['satuan_harga'] ?? '') === 'per_paket' ? 'selected' : '' ?>>Per paket</option>
    </select>
  </div>
  <div class="form-group"><label>Kuota default</label><input type="number" name="kuota_default" class="form-control" value="<?= esc($paket['kuota_default'] ?? '10') ?>"></div>
  <div class="form-group">
    <label>Status</label>
    <select name="status" class="form-control">
      <option value="draft" <?= ($paket['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
      <option value="publish" <?= ($paket['status'] ?? '') === 'publish' ? 'selected' : '' ?>>Publish</option>
    </select>
  </div>
  <div class="form-group"><label>Gambar cover</label><input type="file" name="gambar_cover" accept="image/*" class="form-control"></div>
  <?php if (! empty($paket['gambar_cover'])): ?>
    <img src="<?= media_url($paket['gambar_cover']) ?>" style="max-width:200px;margin-bottom:1rem">
  <?php endif; ?>
  <button class="btn btn-primary" type="submit">Simpan</button>
  <a href="<?= site_url('admin/paket-wisata') ?>" class="btn btn-outline">Batal</a>
</form>
<?= $this->endSection() ?>
