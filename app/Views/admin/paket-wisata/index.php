<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p>Kelola paket wisata, homestay, dan camping ground.</p>
  <a href="<?= site_url('admin/paket-wisata/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Tambah Paket
  </a>
</div>

<div class="card card-table">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Nama</th>
          <th>Jenis</th>
          <th>Harga</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($paket)): ?>
          <tr>
            <td colspan="5" class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              Belum ada paket.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($paket as $p): ?>
            <tr>
              <td><?= esc($p['nama']) ?></td>
              <td><?= esc(label_jenis_paket($p['jenis'] ?? 'wisata')) ?></td>
              <td>
                <?= format_rupiah($p['harga']) ?>
                <small class="text-muted">/
                  <?= esc(satuan_label($p['jenis'] ?? 'wisata', $p['satuan_harga'] ?? null)) ?></small>
              </td>
              <td><?= badge_status($p['status']) ?></td>
              <td class="actions">
                <a href="<?= site_url('admin/paket-wisata/' . $p['id'] . '/jadwal') ?>" class="btn btn-sm">
                  <i class="fa-solid fa-calendar-days"></i> Jadwal
                </a>
                <a href="<?= site_url('admin/paket-wisata/' . $p['id'] . '/edit') ?>" class="btn btn-sm">
                  <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form method="post" action="<?= site_url('admin/paket-wisata/' . $p['id'] . '/delete') ?>"
                  class="inline-form js-swal-confirm" data-swal-title="Hapus paket?"
                  data-swal-text="Data paket akan dihapus." data-swal-confirm="Hapus" data-swal-icon="warning">
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
