<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
/* ==========================================================================
   AESTHETIC DATATABLES & PRODUCT MANAGEMENT STYLING (Tema Admin Binangun)
   ========================================================================== */

/* Top Metrics Summary */
.product-stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}
.stat-card {
  background: var(--admin-card-bg, #fff);
  border: 1px solid var(--admin-border, #e5dccb);
  border-radius: 10px;
  padding: 1.15rem 1.25rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 2px 8px var(--shadow, rgba(74, 50, 34, 0.04));
  transition: transform .2s ease, border-color .2s ease;
}
.stat-card:hover {
  transform: translateY(-2px);
  border-color: var(--gold, #c9a66b);
}
.stat-icon {
  width: 46px;
  height: 46px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  flex-shrink: 0;
}
.stat-icon-total { background: rgba(74, 50, 34, 0.08); color: var(--deep, #4a3222); }
.stat-icon-umkm { background: rgba(193, 80, 46, 0.1); color: var(--terracotta, #c1502e); }
.stat-icon-catering { background: rgba(122, 132, 80, 0.12); color: var(--olive, #7a8450); }
.stat-icon-stok { background: rgba(201, 166, 107, 0.18); color: #9c6c19; }

.stat-info { display: flex; flex-direction: column; }
.stat-label { font-size: 0.8rem; color: var(--admin-muted, #736b5e); font-weight: 500; }
.stat-value { font-family: var(--font-heading, serif); font-size: 1.6rem; font-weight: 700; color: var(--deep, #4a3222); line-height: 1.1; margin-top: 2px; }

/* Filter Bar */
.dt-custom-filter-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1rem;
  padding: 1rem 1.25rem;
  background: var(--white, #fffbf5);
  border: 1px solid var(--admin-border, #e5dccb);
  border-bottom: none;
  border-radius: 10px 10px 0 0;
}
.filter-tabs-pills {
  display: flex;
  gap: 0.4rem;
  flex-wrap: wrap;
}
.filter-pill {
  border: 1px solid var(--admin-border, #e5dccb);
  background: #fff;
  color: var(--deep, #4a3222);
  padding: 0.4rem 0.85rem;
  border-radius: 20px;
  font-size: 0.82rem;
  font-weight: 600;
  cursor: pointer;
  transition: all .2s;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
}
.filter-pill:hover {
  background: var(--cream, #f4e9d8);
  border-color: var(--gold, #c9a66b);
}
.filter-pill.is-active {
  background: var(--terracotta, #c1502e);
  color: #fff;
  border-color: var(--terracotta, #c1502e);
}

/* DataTables Overrides */
.dataTables_wrapper {
  background: var(--admin-card-bg, #fff);
  border: 1px solid var(--admin-border, #e5dccb);
  border-radius: 0 0 10px 10px;
  box-shadow: 0 2px 10px var(--shadow, rgba(74, 50, 34, 0.05));
  padding-bottom: 0.75rem;
}
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
  padding: 0.85rem 1.25rem;
}
.dataTables_wrapper .dataTables_filter input {
  height: 36px;
  padding: 0 0.75rem;
  border: 1px solid var(--admin-border, #e5dccb);
  border-radius: 6px;
  margin-left: 0.5rem;
  font-size: 0.88rem;
}
.dataTables_wrapper .dataTables_length select {
  height: 34px;
  border: 1px solid var(--admin-border, #e5dccb);
  border-radius: 6px;
  padding: 0 0.5rem;
  margin: 0 0.4rem;
}
table.dataTable.no-footer {
  border-bottom: 1px solid var(--admin-border, #e5dccb) !important;
}
table.dataTable thead th {
  background: var(--cream, #f4e9d8);
  color: var(--deep, #4a3222);
  font-family: var(--font-heading, serif);
  font-size: 0.96rem;
  font-weight: 700;
  padding: 0.85rem 1rem !important;
  border-bottom: 1px solid var(--admin-border, #e5dccb) !important;
}
table.dataTable tbody td {
  padding: 0.85rem 1rem !important;
  vertical-align: middle;
  border-bottom: 1px solid rgba(229, 220, 203, 0.6) !important;
  font-size: 0.9rem;
}
table.dataTable tbody tr:hover {
  background-color: rgba(244, 233, 216, 0.3) !important;
}
.dataTables_wrapper .dataTables_info {
  padding: 1rem 1.25rem;
  color: var(--admin-muted, #736b5e);
  font-size: 0.86rem;
}
.dataTables_wrapper .dataTables_paginate {
  padding: 0.75rem 1.25rem;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
  border-radius: 6px !important;
  border: 1px solid transparent !important;
  padding: 0.35rem 0.75rem !important;
  font-size: 0.84rem !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
  background: var(--terracotta, #c1502e) !important;
  color: #fff !important;
  border-color: var(--terracotta, #c1502e) !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  background: var(--cream, #f4e9d8) !important;
  color: var(--deep, #4a3222) !important;
  border-color: var(--gold, #c9a66b) !important;
}

/* Custom Product Cell Components */
.product-cell {
  display: flex;
  align-items: center;
  gap: 0.85rem;
}
.product-thumb-wrap {
  position: relative;
  width: 48px;
  height: 48px;
  border-radius: 6px;
  overflow: hidden;
  background: var(--cream, #f4e9d8);
  border: 1px solid var(--cream-dark, #e8d9c0);
  flex-shrink: 0;
}
.product-thumb-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.product-thumb-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--sepia, #8b5e3c);
  font-size: 1.2rem;
}
.product-title-text {
  font-weight: 600;
  color: var(--deep, #4a3222);
  line-height: 1.3;
}
.product-meta-tags {
  display: flex;
  gap: 0.35rem;
  flex-wrap: wrap;
  margin-top: 0.25rem;
}
.tag-badge {
  font-size: 0.68rem;
  padding: 1px 6px;
  border-radius: 4px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
}
.tag-variant { background: rgba(13, 110, 253, 0.1); color: #0d6efd; border: 1px solid rgba(13, 110, 253, 0.25); }
.tag-photos { background: rgba(23, 162, 184, 0.1); color: #0f7d8d; border: 1px solid rgba(23, 162, 184, 0.25); }
.tag-min { background: rgba(255, 193, 7, 0.2); color: #856404; border: 1px solid rgba(255, 193, 7, 0.4); }

.price-text {
  font-family: var(--font-heading, serif);
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--terracotta, #c1502e);
}
.stock-val {
  font-weight: 600;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
}
.stock-ok { background: rgba(122, 132, 80, 0.12); color: var(--olive, #7a8450); }
.stock-low { background: rgba(255, 193, 7, 0.2); color: #b78103; }
.stock-empty { background: rgba(220, 53, 69, 0.1); color: #dc3545; }

.action-buttons {
  display: inline-flex;
  gap: 0.4rem;
}

@media (max-width: 768px) {
  .dt-custom-filter-bar {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
// Hitung Metrik Cepat
$totalProduk = count($produk);
$countUmkm = 0;
$countCatering = 0;
$totalStok = 0;
foreach ($produk as $item) {
    if (($item['jenis'] ?? 'umkm') === 'catering') {
        $countCatering++;
    } else {
        $countUmkm++;
    }
    $totalStok += (int) $item['stok'];
}
?>

<!-- SUMMARY METRICS -->
<div class="product-stats-grid">
  <div class="stat-card">
    <div class="stat-icon stat-icon-total">
      <i class="fa-solid fa-boxes-stacked"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Total Produk</span>
      <span class="stat-value"><?= $totalProduk ?></span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon stat-icon-umkm">
      <i class="fa-solid fa-store"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Karya UMKM</span>
      <span class="stat-value"><?= $countUmkm ?></span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon stat-icon-catering">
      <i class="fa-solid fa-utensils"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Menu Catering</span>
      <span class="stat-value"><?= $countCatering ?></span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon stat-icon-stok">
      <i class="fa-solid fa-warehouse"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Total Unit Stok</span>
      <span class="stat-value"><?= number_format($totalStok, 0, ',', '.') ?></span>
    </div>
  </div>
</div>

<!-- PAGE TOOLBAR & ADD ACTION -->
<div class="page-toolbar" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:1rem">
  <div>
    <p style="margin:0;color:var(--admin-muted)">Katalog barang kerajinan UMKM, paket catering, dan kontrol varian ukuran.</p>
  </div>
  <a href="<?= site_url('admin/produk/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-circle-plus"></i> Tambah Produk Baru
  </a>
</div>

<!-- CUSTOM QUICK FILTER PILLS -->
<div class="dt-custom-filter-bar">
  <div class="filter-tabs-pills">
    <button type="button" class="filter-pill is-active" data-filter="">
      Semua (<?= $totalProduk ?>)
    </button>
    <button type="button" class="filter-pill" data-filter="UMKM">
      <i class="fa-solid fa-store"></i> UMKM (<?= $countUmkm ?>)
    </button>
    <button type="button" class="filter-pill" data-filter="Catering">
      <i class="fa-solid fa-utensils"></i> Catering (<?= $countCatering ?>)
    </button>
    <button type="button" class="filter-pill" data-filter="Publish">
      <i class="fa-solid fa-circle-check" style="color:#28a745"></i> Aktif Publish
    </button>
    <button type="button" class="filter-pill" data-filter="Draft">
      <i class="fa-solid fa-file-pen" style="color:#ffc107"></i> Draft
    </button>
  </div>
</div>

<!-- DATATABLE CARD -->
<div class="card card-table" style="border-radius:0 0 10px 10px;overflow:hidden">
  <div class="table-wrap" style="padding:0">
    <table id="adminProdukDataTable" class="display responsive nowrap" style="width:100%">
      <thead>
        <tr>
          <th>Produk</th>
          <th>Jenis</th>
          <th>Kategori</th>
          <th>Harga Dasar</th>
          <th>Stok</th>
          <th>Min Beli</th>
          <th>Status</th>
          <th style="text-align:right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produk as $p): ?>
          <?php
          $isCatering = ($p['jenis'] ?? 'umkm') === 'catering';
          $varianArr = !empty($p['opsi_varian']) ? json_decode($p['opsi_varian'], true) : [];
          $fotoTambahan = !empty($p['foto_tambahan']) ? json_decode($p['foto_tambahan'], true) : [];
          $totalFoto = 1 + count($fotoTambahan);
          $stok = (int) $p['stok'];
          $minBeli = (int) ($p['min_beli'] ?? 1);
          ?>
          <tr>
            <td>
              <div class="product-cell">
                <div class="product-thumb-wrap">
                  <?php if (!empty($p['gambar'])): ?>
                    <img src="<?= media_url($p['gambar']) ?>" alt="" loading="lazy">
                  <?php else: ?>
                    <div class="product-thumb-placeholder">
                      <i class="<?= $isCatering ? 'fa-solid fa-utensils' : 'fa-solid fa-box' ?>"></i>
                    </div>
                  <?php endif; ?>
                </div>

                <div>
                  <div class="product-title-text"><?= esc($p['nama']) ?></div>
                  <div class="product-meta-tags">
                    <?php if (!empty($varianArr)): ?>
                      <span class="tag-badge tag-variant" title="<?= count($varianArr) ?> varian ukuran/model">
                        <i class="fa-solid fa-sliders"></i> <?= count($varianArr) ?> Varian
                      </span>
                    <?php endif; ?>
                    <?php if ($totalFoto > 1): ?>
                      <span class="tag-badge tag-photos" title="<?= $totalFoto ?> total foto tersimpan">
                        <i class="fa-solid fa-images"></i> <?= $totalFoto ?> Foto
                      </span>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </td>

            <td>
              <span class="badge-jenis <?= $isCatering ? 'badge-catering' : 'badge-umkm' ?>">
                <?= $isCatering ? 'Catering' : 'UMKM' ?>
              </span>
            </td>

            <td>
              <span style="color:var(--deep);font-weight:500"><?= esc($p['kategori_nama'] ?? 'Tanpa Kategori') ?></span>
            </td>

            <td>
              <span class="price-text"><?= format_rupiah($p['harga']) ?></span>
            </td>

            <td>
              <?php if ($stok <= 0): ?>
                <span class="stock-val stock-empty">Habis (0)</span>
              <?php elseif ($stok <= 5): ?>
                <span class="stock-val stock-low">Kritis (<?= $stok ?>)</span>
              <?php else: ?>
                <span class="stock-val stock-ok"><?= $stok ?> unit</span>
              <?php endif; ?>
            </td>

            <td>
              <?php if ($minBeli > 1): ?>
                <span class="tag-badge tag-min">Min <?= $minBeli ?> pcs</span>
              <?php else: ?>
                <span style="color:var(--admin-muted)">1 pcs</span>
              <?php endif; ?>
            </td>

            <td>
              <?= badge_status($p['status']) ?>
            </td>

            <td style="text-align:right">
              <div class="action-buttons">
                <a href="<?= site_url('admin/produk/' . $p['id'] . '/edit') ?>" class="btn btn-sm btn-outline" title="Edit Produk">
                  <i class="fa-solid fa-pen-to-square"></i> Edit
                </a>
                <form method="post" action="<?= site_url('admin/produk/' . $p['id'] . '/delete') ?>"
                  class="inline-form js-swal-confirm" data-swal-title="Hapus produk?"
                  data-swal-text="Data produk '<?= esc($p['nama'], 'attr') ?>' akan dihapus permanen." data-swal-confirm="Hapus" data-swal-icon="warning">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-danger" title="Hapus Produk">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
  var table = $('#adminProdukDataTable').DataTable({
    responsive: true,
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Cari produk, kategori, harga...",
      lengthMenu: "Tampilkan _MENU_ entri",
      info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ produk",
      infoEmpty: "Menampilkan 0 produk",
      infoFiltered: "(difilter dari _MAX_ total produk)",
      zeroRecords: "Tidak ada produk yang cocok dengan pencarian",
      paginate: {
        first: "Awal",
        last: "Akhir",
        next: "<i class='fa-solid fa-chevron-right'></i>",
        previous: "<i class='fa-solid fa-chevron-left'></i>"
      }
    },
    order: [[0, 'asc']],
    columnDefs: [
      { orderable: false, targets: [7] }
    ]
  });

  // Pill Filter Buttons
  $('.filter-pill').on('click', function() {
    $('.filter-pill').removeClass('is-active');
    $(this).addClass('is-active');
    var filterValue = $(this).data('filter');
    table.search(filterValue).draw();
  });
});
</script>
<?= $this->endSection() ?>
