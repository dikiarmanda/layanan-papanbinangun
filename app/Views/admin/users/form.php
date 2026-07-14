<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<form method="post" action="<?= $user ? site_url('admin/users/' . $user['id']) : site_url('admin/users') ?>" style="max-width:480px">
  <?= csrf_field() ?>
  <div class="form-group"><label>Nama</label><input name="nama" class="form-control" required value="<?= esc($user['nama'] ?? '') ?>"></div>
  <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required value="<?= esc($user['email'] ?? '') ?>"></div>
  <div class="form-group">
    <label>Password <?= $user ? '(kosongkan jika tidak diganti)' : '' ?></label>
    <input type="password" name="password" class="form-control" <?= $user ? '' : 'required' ?>>
  </div>
  <div class="form-group">
    <label>Role</label>
    <select name="role" class="form-control">
      <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
      <option value="superadmin" <?= ($user['role'] ?? '') === 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
    </select>
  </div>
  <?php if ($user): ?>
  <div class="form-group">
    <label>Status</label>
    <select name="status" class="form-control">
      <option value="aktif" <?= ($user['status'] ?? '') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
      <option value="nonaktif" <?= ($user['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
    </select>
  </div>
  <?php endif; ?>
  <button class="btn btn-primary" type="submit">Simpan</button>
</form>
<?= $this->endSection() ?>
