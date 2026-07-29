<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p>Kelola produk UMKM dan catering desa.</p>
  <a href="<?= site_url('admin/produk/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Tambah Produk
  </a>
</div>

<div class="card card-table">
  <div class="table-wrap">
    <table>
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
        <?php if (empty($produk)): ?>
          <tr>
            <td colspan="7" class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              Belum ada produk.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($produk as $p): ?>
            <tr>
              <td><?= esc($p['nama']) ?></td>
              <td><?= esc($p['jenis'] ?? 'umkm') ?></td>
              <td><?= esc($p['kategori_nama'] ?? '-') ?></td>
              <td><?= format_rupiah($p['harga']) ?></td>
              <td><?= (int) $p['stok'] ?></td>
              <td><?= badge_status($p['status']) ?></td>
              <td class="actions">
                <a href="<?= site_url('admin/produk/' . $p['id'] . '/edit') ?>" class="btn btn-sm">
                  <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form method="post" action="<?= site_url('admin/produk/' . $p['id'] . '/delete') ?>"
                  class="inline-form js-swal-confirm" data-swal-title="Hapus produk?"
                  data-swal-text="Produk akan dihapus." data-swal-confirm="Hapus" data-swal-icon="warning">
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
