<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin-dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $stats = $stats ?? []; ?>

<div class="admin-dashboard">
  <div class="stats-grid">
    <a class="stat-card" href="<?= site_url('admin/reservasi') ?>">
      <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
      <div class="stat-body">
        <span class="stat-label">Reservasi Pending</span>
        <span class="stat-value"><?= (int) ($stats['reservasi_pending'] ?? 0) ?></span>
        <span class="stat-meta">Menunggu pembayaran</span>
      </div>
    </a>

    <a class="stat-card" href="<?= site_url('admin/reservasi') ?>">
      <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
      <div class="stat-body">
        <span class="stat-label">Reservasi Paid</span>
        <span class="stat-value"><?= (int) ($stats['reservasi_paid'] ?? 0) ?></span>
        <span class="stat-meta">Sudah dibayar</span>
      </div>
    </a>

    <a class="stat-card" href="<?= site_url('admin/order') ?>">
      <div class="stat-icon"><i class="fa-solid fa-bag-shopping"></i></div>
      <div class="stat-body">
        <span class="stat-label">Order Pending</span>
        <span class="stat-value"><?= (int) ($stats['order_pending'] ?? 0) ?></span>
        <span class="stat-meta"><?= (int) ($stats['order_paid'] ?? 0) ?> paid</span>
      </div>
    </a>

    <a class="stat-card" href="<?= site_url('admin/order') ?>">
      <div class="stat-icon"><i class="fa-solid fa-gears"></i></div>
      <div class="stat-body">
        <span class="stat-label">Perlu Diproses</span>
        <span class="stat-value"><?= (int) ($stats['order_proses'] ?? 0) ?></span>
        <span class="stat-meta">Status diproses</span>
      </div>
    </a>
  </div>

  <div class="grid-2">
    <div class="card">
      <div class="card-header">
        <h2><i class="fa-solid fa-calendar-check"></i> Reservasi Terbaru</h2>
        <a href="<?= site_url('admin/reservasi') ?>" class="btn btn-sm btn-primary">
          <i class="fa-solid fa-list"></i> Lihat
        </a>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Kode</th>
              <th>Pelanggan</th>
              <th>Status</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($reservasiBaru)): ?>
              <tr>
                <td colspan="4" class="empty-state text-center">
                  <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-inbox" style="font-size: 2em;"></i>
                    <span>Belum ada reservasi.</span>
                  </div>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($reservasiBaru as $r): ?>
                <tr>
                  <td>
                    <a href="<?= site_url('admin/reservasi/' . $r['id']) ?>"><?= esc($r['kode_reservasi']) ?></a>
                    <div class="stat-meta"><?= esc($r['paket_nama']) ?></div>
                  </td>
                  <td><?= esc($r['pelanggan_nama']) ?></td>
                  <td><?= badge_status($r['status_pembayaran']) ?></td>
                  <td><?= esc(date('d M Y', strtotime((string) $r['created_at']))) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2><i class="fa-solid fa-list-ul"></i> Aktivitas Terbaru</h2>
      </div>
      <ul class="activity-list">
        <?php if (empty($aktivitas)): ?>
          <li class="text-muted" style="color:var(--admin-muted)">Belum ada aktivitas.</li>
        <?php else: ?>
          <?php foreach ($aktivitas as $log): ?>
            <li>
              <strong><a href="<?= esc($log['url']) ?>"><?= esc($log['judul']) ?></a></strong>
              <?= esc($log['deskripsi']) ?>
              <small><?= esc($log['waktu']) ?></small>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h2><i class="fa-solid fa-bag-shopping"></i> Order Terbaru</h2>
      <a href="<?= site_url('admin/order') ?>" class="btn btn-sm btn-primary">
        <i class="fa-solid fa-list"></i> Lihat
      </a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Kode</th>
            <th>Pelanggan</th>
            <th>Total</th>
            <th>Bayar</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($orderBaru)): ?>
            <tr>
              <td colspan="5" class="empty-state text-center">
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                  <i class="fa-solid fa-inbox" style="font-size: 2em;"></i>
                  <span>Belum ada order.</span>
                </div>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($orderBaru as $o): ?>
              <tr>
                <td><a href="<?= site_url('admin/order/' . $o['id']) ?>"><?= esc($o['kode_order']) ?></a></td>
                <td><?= esc($o['pelanggan_nama']) ?></td>
                <td><?= format_rupiah($o['total_harga']) ?></td>
                <td><?= badge_status($o['status_pembayaran']) ?></td>
                <td><?= badge_status($o['status_order']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?= $this->endSection() ?>