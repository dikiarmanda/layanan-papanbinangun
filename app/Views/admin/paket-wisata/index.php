<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<p><a class="btn btn-primary btn-sm" href="<?= site_url('admin/paket-wisata/create') ?>">+ Tambah Paket</a></p>
<table class="table">
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
    <?php foreach ($paket as $p): ?>
      <tr>
        <td><?= esc($p['nama']) ?></td>
        <td><?= esc(label_jenis_paket($p['jenis'] ?? 'wisata')) ?></td>
        <td><?= format_rupiah($p['harga']) ?> <small>/
            <?= esc(satuan_label($p['jenis'] ?? 'wisata', $p['satuan_harga'] ?? null)) ?></small></td>
        <td><?= badge_status($p['status']) ?></td>
        <td style="white-space:nowrap">
          <a href="<?= site_url('admin/paket-wisata/' . $p['id'] . '/jadwal') ?>">Jadwal</a> |
          <a href="<?= site_url('admin/paket-wisata/' . $p['id'] . '/edit') ?>">Edit</a> |
          <form method="post" action="<?= site_url('admin/paket-wisata/' . $p['id'] . '/delete') ?>"
            style="display:inline" class="js-swal-confirm" data-swal-title="Hapus paket?"
            data-swal-text="Data paket akan dihapus." data-swal-confirm="Hapus" data-swal-icon="warning">
            <?= csrf_field() ?><button type="submit" class="btn btn-sm btn-danger">Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= $this->endSection() ?>