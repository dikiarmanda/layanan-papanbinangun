<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<form method="post" action="<?= $zona ? site_url('admin/zona-antar/' . $zona['id']) : site_url('admin/zona-antar') ?>"
  style="max-width:640px">
  <?= csrf_field() ?>
  <div class="form-group"><label>Nama zona</label><input name="nama" class="form-control" required
      value="<?= esc($zona['nama'] ?? '') ?>" placeholder="Contoh: Desa Binangun"></div>
  <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"
      rows="3"><?= esc($zona['deskripsi'] ?? '') ?></textarea></div>
  <div class="form-group"><label>Ongkir (Rp)</label><input type="number" name="ongkir" class="form-control" required
      min="0" value="<?= esc($zona['ongkir'] ?? '0') ?>"></div>
  <div class="form-group"><label>Estimasi waktu</label><input name="estimasi" class="form-control"
      value="<?= esc($zona['estimasi'] ?? '1-2 jam') ?>" placeholder="1-2 jam"></div>
  <div class="form-group">
    <label>Status</label>
    <select name="status" class="form-control">
      <option value="aktif" <?= ($zona['status'] ?? 'aktif') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
      <option value="nonaktif" <?= ($zona['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
    </select>
  </div>
  <button class="btn btn-primary" type="submit">Simpan</button>
  <a href="<?= site_url('admin/zona-antar') ?>" class="btn btn-outline">Batal</a>
</form>
<?= $this->endSection() ?>