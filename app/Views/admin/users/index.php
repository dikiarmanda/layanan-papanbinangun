<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<p><a class="btn btn-primary btn-sm" href="<?= site_url('admin/users/create') ?>">+ Tambah Admin</a></p>
<table class="table">
  <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= esc($u['nama']) ?></td>
        <td><?= esc($u['email']) ?></td>
        <td><?= esc($u['role']) ?></td>
        <td><?= badge_status($u['status']) ?></td>
        <td><a href="<?= site_url('admin/users/' . $u['id'] . '/edit') ?>">Edit</a></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= $this->endSection() ?>
