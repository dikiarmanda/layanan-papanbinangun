<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p><?= $produk ? 'Ubah data produk.' : 'Tambah produk UMKM atau catering.' ?></p>
  <a href="<?= site_url('admin/produk') ?>" class="btn btn-outline btn-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<div class="card card-form">
  <form method="post" enctype="multipart/form-data"
    action="<?= $produk ? site_url('admin/produk/' . $produk['id']) : site_url('admin/produk') ?>">
    <?= csrf_field() ?>
    <div class="form-group"><label>Nama</label><input name="nama" class="form-control" required
        value="<?= esc($produk['nama'] ?? '') ?>"></div>
    <div class="form-group">
      <label>Jenis</label>
      <select name="jenis" class="form-control select2" required data-placeholder="Pilih jenis">
        <option value="umkm" <?= ($produk['jenis'] ?? 'umkm') === 'umkm' ? 'selected' : '' ?>>Produk UMKM (bisa dikirim
          ekspedisi)</option>
        <option value="catering" <?= ($produk['jenis'] ?? '') === 'catering' ? 'selected' : '' ?>>Catering (ambil / antar
          lokal + tanggal acara)</option>
      </select>
    </div>
    <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control summernote-field"
        data-height="260" data-placeholder="Tulis deskripsi produk…" rows="5"
        required><?= esc($produk['deskripsi'] ?? '') ?></textarea></div>
    <div class="form-group"><label>Harga</label><input type="number" name="harga" class="form-control" required
        value="<?= esc($produk['harga'] ?? '') ?>"></div>
    <div class="form-group"><label>Stok</label><input type="number" name="stok" class="form-control" required
        value="<?= esc($produk['stok'] ?? '0') ?>"></div>
    <div class="form-group"><label>Berat (gram) <small>— untuk ongkir UMKM</small></label><input type="number"
        name="berat" class="form-control" value="<?= esc($produk['berat'] ?? '1000') ?>"></div>
    <div class="form-group">
      <label>Kategori</label>
      <select name="kategori_id" class="form-control select2" data-placeholder="Pilih kategori" data-allow-clear="1">
        <option value="">—</option>
        <?php foreach ($kategori as $k): ?>
          <option value="<?= (int) $k['id'] ?>" <?= (string) ($produk['kategori_id'] ?? '') === (string) $k['id'] ? 'selected' : '' ?>><?= esc($k['nama']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Status</label>
      <select name="status" class="form-control select2" data-placeholder="Status">
        <option value="draft" <?= ($produk['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
        <option value="publish" <?= ($produk['status'] ?? '') === 'publish' ? 'selected' : '' ?>>Publish</option>
      </select>
    </div>
    <div class="form-group">
      <label>Gambar</label>
      <input type="file" name="gambar" accept="image/*" class="dropify"
        data-default-file="<?= ! empty($produk['gambar']) ? esc(media_url($produk['gambar']), 'attr') : '' ?>">
    </div>
    <div class="actions">
      <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
      <a href="<?= site_url('admin/produk') ?>" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>

<?= $this->endSection() ?>
