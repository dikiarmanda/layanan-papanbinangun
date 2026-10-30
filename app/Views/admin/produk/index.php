<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
  <div>
    <p style="margin:0">Kelola produk UMKM, varian ukuran, dan catering desa.</p>
  </div>
  <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap">
    <div style="position:relative">
      <input type="text" id="filter-produk-input" class="form-control" placeholder="Cari produk cepat..."
             value="<?= esc($q ?? '') ?>" style="width:260px;padding-left:2.2rem">
      <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);color:#888"></i>
    </div>
    <a href="<?= site_url('admin/produk/create') ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i> Tambah Produk
    </a>
  </div>
</div>

<div class="card card-table">
  <div class="table-wrap">
    <table id="produk-table">
      <thead>
        <tr>
          <th>Nama Produk</th>
          <th>Jenis</th>
          <th>Kategori</th>
          <th>Harga Dasar</th>
          <th>Stok</th>
          <th>Min Beli</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($produk)): ?>
          <tr id="empty-row">
            <td colspan="8" class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              Belum ada produk.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($produk as $p): ?>
            <?php
            $varianArr = !empty($p['opsi_varian']) ? json_decode($p['opsi_varian'], true) : [];
            $fotoTambahan = !empty($p['foto_tambahan']) ? json_decode($p['foto_tambahan'], true) : [];
            $totalFoto = 1 + count($fotoTambahan);
            ?>
            <tr class="produk-row" data-search="<?= esc(strtolower($p['nama'] . ' ' . ($p['jenis'] ?? '') . ' ' . ($p['kategori_nama'] ?? ''))) ?>">
              <td>
                <div style="display:flex;align-items:center;gap:0.6rem">
                  <?php if (!empty($p['gambar'])): ?>
                    <img src="<?= media_url($p['gambar']) ?>" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:4px;flex-shrink:0">
                  <?php else: ?>
                    <div style="width:40px;height:40px;background:#eee;border-radius:4px;display:flex;align-items:center;justify-content:center;color:#aaa;flex-shrink:0">
                      <i class="fa-solid fa-image"></i>
                    </div>
                  <?php endif; ?>
                  <div>
                    <strong><?= esc($p['nama']) ?></strong>
                    <div style="display:flex;gap:0.35rem;flex-wrap:wrap;margin-top:0.2rem">
                      <?php if (!empty($varianArr)): ?>
                        <span class="badge" style="background:#0d6efd;color:#fff;font-size:0.7rem">
                          <?= count($varianArr) ?> Ukuran/Varian
                        </span>
                      <?php endif; ?>
                      <?php if ($totalFoto > 1): ?>
                        <span class="badge" style="background:#17a2b8;color:#fff;font-size:0.7rem">
                          <i class="fa-solid fa-camera"></i> <?= $totalFoto ?> Foto
                        </span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </td>
              <td><?= esc($p['jenis'] ?? 'umkm') ?></td>
              <td><?= esc($p['kategori_nama'] ?? '-') ?></td>
              <td><?= format_rupiah($p['harga']) ?></td>
              <td><?= (int) $p['stok'] ?></td>
              <td>
                <?php if ((int) ($p['min_beli'] ?? 1) > 1): ?>
                  <span class="badge" style="background:#ffc107;color:#212529;font-weight:600">
                    Min <?= (int) $p['min_beli'] ?> pcs
                  </span>
                <?php else: ?>
                  1 pcs
                <?php endif; ?>
              </td>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  var searchInput = document.getElementById('filter-produk-input');
  var rows = document.querySelectorAll('.produk-row');

  if (searchInput && rows.length > 0) {
    searchInput.addEventListener('input', function() {
      var query = this.value.toLowerCase().trim();
      var visibleCount = 0;

      rows.forEach(function(row) {
        var text = row.getAttribute('data-search') || '';
        if (query === '' || text.indexOf(query) !== -1) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });
    });
  }
});
</script>

<?= $this->endSection() ?>
