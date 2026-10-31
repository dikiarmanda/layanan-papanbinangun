<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
/* ==========================================================================
   ORDER MANAGEMENT STYLING & METRICS (Tema Admin Binangun)
   ========================================================================== */

/* Summary Stats */
.order-stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
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
.stat-icon-all { background: rgba(74, 50, 34, 0.08); color: var(--deep, #4a3222); }
.stat-icon-paid { background: rgba(40, 167, 69, 0.12); color: #28a745; }
.stat-icon-process { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
.stat-icon-revenue { background: rgba(201, 166, 107, 0.18); color: #9c6c19; }

.stat-info { display: flex; flex-direction: column; }
.stat-label { font-size: 0.8rem; color: var(--admin-muted, #736b5e); font-weight: 500; }
.stat-value { font-family: var(--font-heading, serif); font-size: 1.55rem; font-weight: 700; color: var(--deep, #4a3222); line-height: 1.1; margin-top: 2px; }

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
  font-size: 0.95rem;
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

/* Custom Table Components */
.order-code-badge {
  font-family: monospace;
  font-size: 0.92rem;
  font-weight: 700;
  color: var(--terracotta, #c1502e);
  text-decoration: none;
}
.order-code-badge:hover {
  text-decoration: underline;
}
.customer-info-box {
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.customer-name {
  font-weight: 600;
  color: var(--deep, #4a3222);
}
.customer-meta {
  font-size: 0.8rem;
  color: var(--admin-muted, #736b5e);
  display: flex;
  align-items: center;
  gap: 0.4rem;
}
.order-amount-text {
  font-family: var(--font-heading, serif);
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--deep, #4a3222);
}
.order-date-text {
  font-size: 0.8rem;
  color: var(--admin-muted, #736b5e);
}
.shipping-badge {
  font-size: 0.76rem;
  display: inline-block;
  padding: 1px 6px;
  border-radius: 4px;
  background: rgba(74, 50, 34, 0.08);
  color: var(--deep, #4a3222);
  margin-top: 2px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
// Kalkulasi Statistik Cepat
$totalOrders = count($orders);
$paidOrders = 0;
$processingOrders = 0;
$totalRevenue = 0;

foreach ($orders as $o) {
    if (in_array($o['status_pembayaran'] ?? '', ['settlement', 'capture', 'lunas'], true)) {
        $paidOrders++;
        $totalRevenue += (float) ($o['total_harga'] ?? 0);
    }
    if (in_array($o['status_order'] ?? '', ['diproses', 'dikirim'], true)) {
        $processingOrders++;
    }
}
?>

<!-- METRICS SUMMARY -->
<div class="order-stats-grid">
  <div class="stat-card">
    <div class="stat-icon stat-icon-all">
      <i class="fa-solid fa-receipt"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Total Pesanan</span>
      <span class="stat-value"><?= $totalOrders ?></span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon stat-icon-paid">
      <i class="fa-solid fa-circle-check"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Pembayaran Berhasil</span>
      <span class="stat-value"><?= $paidOrders ?></span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon stat-icon-process">
      <i class="fa-solid fa-box-open"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Diproses / Dikirim</span>
      <span class="stat-value"><?= $processingOrders ?></span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon stat-icon-revenue">
      <i class="fa-solid fa-money-bill-wave"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Total Pendapatan Order</span>
      <span class="stat-value" style="font-size:1.35rem"><?= format_rupiah($totalRevenue) ?></span>
    </div>
  </div>
</div>

<div class="page-toolbar" style="margin-bottom:1rem">
  <p style="margin:0;color:var(--admin-muted)">Daftar riwayat transaksi belanja produk UMKM dan pemesanan menu catering desa.</p>
</div>

<!-- QUICK STATUS FILTER PILLS -->
<div class="dt-custom-filter-bar">
  <div class="filter-tabs-pills">
    <button type="button" class="filter-pill is-active" data-filter="">
      Semua (<?= $totalOrders ?>)
    </button>
    <button type="button" class="filter-pill" data-filter="Lunas">
      <i class="fa-solid fa-circle-check" style="color:#28a745"></i> Pembayaran Lunas
    </button>
    <button type="button" class="filter-pill" data-filter="Menunggu">
      <i class="fa-solid fa-clock" style="color:#ffc107"></i> Menunggu Bayar
    </button>
    <button type="button" class="filter-pill" data-filter="Diproses">
      <i class="fa-solid fa-arrows-rotate" style="color:#0d6efd"></i> Diproses
    </button>
    <button type="button" class="filter-pill" data-filter="Dikirim">
      <i class="fa-solid fa-truck" style="color:#17a2b8"></i> Dikirim
    </button>
    <button type="button" class="filter-pill" data-filter="Selesai">
      <i class="fa-solid fa-award" style="color:#28a745"></i> Selesai
    </button>
  </div>
</div>

<!-- ORDERS DATATABLE -->
<div class="card card-table" style="border-radius:0 0 10px 10px;overflow:hidden">
  <div class="table-wrap" style="padding:0">
    <table id="adminOrdersDataTable" class="display responsive nowrap" style="width:100%">
      <thead>
        <tr>
          <th>Kode Order</th>
          <th>Pelanggan</th>
          <th>Tanggal Transaksi</th>
          <th>Pengiriman</th>
          <th>Total Tagihan</th>
          <th>Pembayaran</th>
          <th>Status Order</th>
          <th style="text-align:right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <?php
          $metodeKirim = $o['metode_pengiriman'] ?? 'ekspedisi';
          $isCatering = !empty($o['tanggal_acara']) || in_array($metodeKirim, ['ambil_di_tempat', 'antar_lokal'], true);
          ?>
          <tr>
            <td>
              <a href="<?= site_url('admin/order/' . $o['id']) ?>" class="order-code-badge" title="Lihat Detail Pesanan">
                <?= esc($o['kode_order']) ?>
              </a>
              <?php if (!empty($o['no_resi'])): ?>
                <div style="font-size:0.75rem;color:var(--admin-muted);font-family:monospace">
                  Resi: <?= esc($o['no_resi']) ?>
                </div>
              <?php endif; ?>
            </td>

            <td>
              <div class="customer-info-box">
                <span class="customer-name"><?= esc($o['pelanggan_nama'] ?? 'Pelanggan') ?></span>
                <span class="customer-meta">
                  <i class="fa-brands fa-whatsapp" style="color:#25d366"></i> <?= esc($o['no_hp'] ?? '-') ?>
                </span>
              </div>
            </td>

            <td>
              <span class="order-date-text">
                <?= date('d M Y, H:i', strtotime($o['created_at'])) ?>
              </span>
            </td>

            <td>
              <div>
                <?php if ($isCatering): ?>
                  <span class="badge-jenis badge-catering" style="font-size:0.7rem">Catering</span>
                  <div class="shipping-badge"><?= esc(str_replace('_', ' ', $metodeKirim)) ?></div>
                <?php else: ?>
                  <span class="badge-jenis badge-umkm" style="font-size:0.7rem">UMKM</span>
                  <div class="shipping-badge"><?= esc($o['kurir'] ?? 'Ekspedisi') ?> <?= esc($o['layanan_kurir'] ?? '') ?></div>
                <?php endif; ?>
              </div>
            </td>

            <td>
              <span class="order-amount-text"><?= format_rupiah($o['total_harga']) ?></span>
            </td>

            <td>
              <?= badge_status($o['status_pembayaran']) ?>
            </td>

            <td>
              <?= badge_status($o['status_order']) ?>
            </td>

            <td style="text-align:right">
              <a href="<?= site_url('admin/order/' . $o['id']) ?>" class="btn btn-sm btn-primary" title="Buka Detail Order">
                <i class="fa-solid fa-eye"></i> Detail
              </a>
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
  var table = $('#adminOrdersDataTable').DataTable({
    responsive: true,
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Cari kode, pelanggan, no. hp, kurir...",
      lengthMenu: "Tampilkan _MENU_ entri",
      info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pesanan",
      infoEmpty: "Menampilkan 0 pesanan",
      infoFiltered: "(difilter dari _MAX_ total pesanan)",
      zeroRecords: "Tidak ada pesanan yang cocok dengan kriteria",
      paginate: {
        first: "Awal",
        last: "Akhir",
        next: "<i class='fa-solid fa-chevron-right'></i>",
        previous: "<i class='fa-solid fa-chevron-left'></i>"
      }
    },
    order: [[2, 'desc']], // Urutkan default berdasarkan tanggal transaksi terbaru
    columnDefs: [
      { orderable: false, targets: [7] }
    ]
  });

  // Filter Pills Interactivity
  $('.filter-pill').on('click', function() {
    $('.filter-pill').removeClass('is-active');
    $(this).addClass('is-active');
    var filterValue = $(this).data('filter');
    table.search(filterValue).draw();
  });
});
</script>
<?= $this->endSection() ?>
