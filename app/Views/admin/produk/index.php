<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<p><a class="btn btn-primary btn-sm" href="<?= site_url('admin/produk/create') ?>">+ Tambah Produk</a></p>
<table class="table">
  <thead>
    <tr>
      <th>Nama</th>
      <th>Jenis</th>
      <th>Kategori</th>
      <th>Harga</th>
      <th>Stok</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($produk as $p): ?>
      <tr>
        <td><?= esc($p['nama']) ?></td>
        <td><?= esc($p['jenis'] ?? 'umkm') ?></td>
        <td><?= esc($p['kategori_nama'] ?? '-') ?></td>
        <td><?= format_rupiah($p['harga']) ?></td>
        <td><?= (int) $p['stok'] ?></td>
        <td><?= badge_status($p['status']) ?></td>
        <td>
          <a href="<?= site_url('admin/produk/' . $p['id'] . '/edit') ?>">Edit</a> |
          <form method="post" action="<?= site_url('admin/produk/' . $p['id'] . '/delete') ?>" style="display:inline"
            onsubmit="return confirm('Hapus?')">
            <?= csrf_field() ?><button type="submit" class="btn btn-sm btn-danger">Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= $this->endSection() ?>