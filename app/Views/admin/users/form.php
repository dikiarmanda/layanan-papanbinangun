<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p><?= $user ? 'Ubah akun admin.' : 'Tambah akun admin baru.' ?></p>
  <a href="<?= site_url('admin/users') ?>" class="btn btn-outline btn-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<div class="card card-form">
  <form method="post" action="<?= $user ? site_url('admin/users/' . $user['id']) : site_url('admin/users') ?>">
    <?= csrf_field() ?>
    <div class="form-group"><label>Nama</label><input name="nama" class="form-control" required
        value="<?= esc($user['nama'] ?? '') ?>"></div>
    <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required
        value="<?= esc($user['email'] ?? '') ?>"></div>
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
    <div class="actions">
      <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
      <a href="<?= site_url('admin/users') ?>" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>

<?= $this->endSection() ?>
