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
        <td><?= esc($p['jenis'] ?? 'wisata') ?></td>
        <td><?= format_rupiah($p['harga']) ?> <small>/
            <?= ($p['jenis'] ?? '') === 'homestay' ? 'malam' : 'orang' ?></small></td>
        <td><?= badge_status($p['status']) ?></td>
        <td style="white-space:nowrap">
          <a href="<?= site_url('admin/paket-wisata/' . $p['id'] . '/jadwal') ?>">Jadwal</a> |
          <a href="<?= site_url('admin/paket-wisata/' . $p['id'] . '/edit') ?>">Edit</a> |
          <form method="post" action="<?= site_url('admin/paket-wisata/' . $p['id'] . '/delete') ?>"
            style="display:inline" onsubmit="return confirm('Hapus?')">
            <?= csrf_field() ?><button type="submit" class="btn btn-sm btn-danger">Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= $this->endSection() ?>