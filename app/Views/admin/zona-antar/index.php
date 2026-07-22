<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<p><a class="btn btn-primary btn-sm" href="<?= site_url('admin/zona-antar/create') ?>">+ Tambah Zona</a></p>
<p style="color:var(--sepia);max-width:640px">Zona dipakai untuk ongkir catering antar lokal (tanpa ekspedisi).
  Pelanggan memilih zona saat checkout.</p>
<table class="table">
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
    <?php foreach ($zona as $z): ?>
      <tr>
        <td>
          <strong><?= esc($z['nama']) ?></strong>
          <?php if (!empty($z['deskripsi'])): ?>
            <br><small style="color:var(--sepia)"><?= esc($z['deskripsi']) ?></small>
          <?php endif; ?>
        </td>
        <td><?= format_rupiah($z['ongkir']) ?></td>
        <td><?= esc($z['estimasi']) ?></td>
        <td><?= badge_status($z['status']) ?></td>
        <td>
          <a href="<?= site_url('admin/zona-antar/' . $z['id'] . '/edit') ?>">Edit</a> |
          <form method="post" action="<?= site_url('admin/zona-antar/' . $z['id'] . '/delete') ?>" style="display:inline"
            onsubmit="return confirm('Hapus?')">
            <?= csrf_field() ?><button type="submit" class="btn btn-sm btn-danger">Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= $this->endSection() ?>