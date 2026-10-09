<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p><?= $paket ? 'Ubah data paket.' : 'Tambah paket wisata, homestay, atau camping.' ?></p>
  <a href="<?= site_url('admin/paket-wisata') ?>" class="btn btn-outline btn-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<div class="card card-form">
  <form method="post" enctype="multipart/form-data"
    action="<?= $paket ? site_url('admin/paket-wisata/' . $paket['id']) : site_url('admin/paket-wisata') ?>">
    <?= csrf_field() ?>
    <div class="form-group"><label>Nama</label><input name="nama" class="form-control" required
        value="<?= esc($paket['nama'] ?? '') ?>"></div>
    <div class="form-group">
      <label>Jenis</label>
      <select name="jenis" id="paket-jenis" class="form-control select2" required data-placeholder="Pilih jenis">
        <option value="wisata" <?= ($paket['jenis'] ?? 'wisata') === 'wisata' ? 'selected' : '' ?>>Paket Wisata (per orang)
        </option>
        <option value="homestay" <?= ($paket['jenis'] ?? '') === 'homestay' ? 'selected' : '' ?>>Homestay (per rumah / malam)
        </option>
        <option value="camping" <?= ($paket['jenis'] ?? '') === 'camping' ? 'selected' : '' ?>>Camping Ground (per rumah /
          malam)
        </option>
      </select>
    </div>
    <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control summernote-field"
        data-height="260" data-placeholder="Tulis deskripsi paket…" rows="5"
        required><?= esc($paket['deskripsi'] ?? '') ?></textarea></div>
    <div class="form-group">
      <label id="label-harga">Harga</label>
      <input type="number" name="harga" class="form-control" required value="<?= esc($paket['harga'] ?? '') ?>">
      <small class="text-muted">Wisata: per orang. Homestay &amp; camping: per rumah / malam.</small>
    </div>
    <input type="hidden" name="satuan_harga" id="satuan_harga" value="<?= esc($paket['satuan_harga'] ?? 'per_orang') ?>">
    <div class="form-group"><label>Kuota default <small>(homestay/camping biasanya 1)</small></label><input type="number"
        name="kuota_default" class="form-control" value="<?= esc($paket['kuota_default'] ?? '10') ?>"></div>
    <div class="form-group">
      <label>Status</label>
      <select name="status" class="form-control select2" data-placeholder="Status">
        <option value="draft" <?= ($paket['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
        <option value="publish" <?= ($paket['status'] ?? '') === 'publish' ? 'selected' : '' ?>>Publish</option>
      </select>
    </div>
    <div class="form-group">
      <label>Gambar cover</label>
      <input type="file" name="gambar_cover" accept="image/*" class="dropify"
        data-default-file="<?= ! empty($paket['gambar_cover']) ? esc(media_url($paket['gambar_cover']), 'attr') : '' ?>">
    </div>
    <div class="actions">
      <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
      <a href="<?= site_url('admin/paket-wisata') ?>" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  (() => {
    const jenis = document.getElementById('paket-jenis');
    const satuan = document.getElementById('satuan_harga');
    const sync = () => {
      const isMenginap = jenis.value === 'homestay' || jenis.value === 'camping';
      satuan.value = isMenginap ? 'per_rumah' : 'per_orang';
    };
    $(jenis).on('change', sync);
    sync();
  })();
</script>
<?= $this->endSection() ?>
