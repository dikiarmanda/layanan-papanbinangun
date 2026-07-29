<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p>Kelola akun admin panel layanan.</p>
  <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Tambah Admin
  </a>
</div>

<div class="card card-table">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)): ?>
          <tr>
            <td colspan="5" class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              Belum ada admin.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= esc($u['nama']) ?></td>
              <td><?= esc($u['email']) ?></td>
              <td><?= esc($u['role']) ?></td>
              <td><?= badge_status($u['status']) ?></td>
              <td class="actions">
                <a href="<?= site_url('admin/users/' . $u['id'] . '/edit') ?>" class="btn btn-sm">
                  <i class="fa-solid fa-pen"></i> Edit
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
