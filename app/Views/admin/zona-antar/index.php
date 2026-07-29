<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p>Zona antar lokal untuk ongkir catering (tanpa ekspedisi).</p>
  <a href="<?= site_url('admin/zona-antar/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Tambah Zona
  </a>
</div>

<div class="card card-table">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Nama</th>
          <th>Ongkir</th>
          <th>Estimasi</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($zona)): ?>
          <tr>
            <td colspan="5" class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              Belum ada zona.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($zona as $z): ?>
            <tr>
              <td>
                <strong><?= esc($z['nama']) ?></strong>
                <?php if (! empty($z['deskripsi'])): ?>
                  <div class="text-muted" style="font-size:0.82rem"><?= esc($z['deskripsi']) ?></div>
                <?php endif; ?>
              </td>
              <td><?= format_rupiah($z['ongkir']) ?></td>
              <td><?= esc($z['estimasi']) ?></td>
              <td><?= badge_status($z['status']) ?></td>
              <td class="actions">
                <a href="<?= site_url('admin/zona-antar/' . $z['id'] . '/edit') ?>" class="btn btn-sm">
                  <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form method="post" action="<?= site_url('admin/zona-antar/' . $z['id'] . '/delete') ?>"
                  class="inline-form js-swal-confirm" data-swal-title="Hapus zona?"
                  data-swal-text="Zona antar akan dihapus." data-swal-confirm="Hapus" data-swal-icon="warning">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-trash"></i> Hapus
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
