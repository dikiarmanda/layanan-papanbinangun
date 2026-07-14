<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="stat-grid">
  <div class="stat-card"><div class="num"><?= (int) $stats['reservasi_pending'] ?></div><div class="label">Reservasi pending</div></div>
  <div class="stat-card"><div class="num"><?= (int) $stats['reservasi_paid'] ?></div><div class="label">Reservasi paid</div></div>
  <div class="stat-card"><div class="num"><?= (int) $stats['order_pending'] ?></div><div class="label">Order pending</div></div>
  <div class="stat-card"><div class="num"><?= (int) $stats['order_paid'] ?></div><div class="label">Order paid</div></div>
  <div class="stat-card"><div class="num"><?= (int) $stats['order_proses'] ?></div><div class="label">Perlu diproses</div></div>
</div>

<h2 style="font-size:1.3rem">Reservasi terbaru</h2>
<table class="table" style="margin-bottom:2rem">
  <thead><tr><th>Kode</th><th>Pelanggan</th><th>Paket</th><th>Bayar</th><th>Status</th></tr></thead>
  <tbody>
    <?php foreach ($reservasiBaru as $r): ?>
      <tr>
        <td><a href="<?= site_url('admin/reservasi/' . $r['id']) ?>"><?= esc($r['kode_reservasi']) ?></a></td>
        <td><?= esc($r['pelanggan_nama']) ?></td>
        <td><?= esc($r['paket_nama']) ?></td>
        <td><?= badge_status($r['status_pembayaran']) ?></td>
        <td><?= badge_status($r['status_reservasi']) ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (empty($reservasiBaru)): ?><tr><td colspan="5">Belum ada data</td></tr><?php endif; ?>
  </tbody>
</table>

<h2 style="font-size:1.3rem">Order terbaru</h2>
<table class="table">
  <thead><tr><th>Kode</th><th>Pelanggan</th><th>Total</th><th>Bayar</th><th>Status</th></tr></thead>
  <tbody>
    <?php foreach ($orderBaru as $o): ?>
      <tr>
        <td><a href="<?= site_url('admin/order/' . $o['id']) ?>"><?= esc($o['kode_order']) ?></a></td>
        <td><?= esc($o['pelanggan_nama']) ?></td>
        <td><?= format_rupiah($o['total_harga']) ?></td>
        <td><?= badge_status($o['status_pembayaran']) ?></td>
        <td><?= badge_status($o['status_order']) ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (empty($orderBaru)): ?><tr><td colspan="5">Belum ada data</td></tr><?php endif; ?>
  </tbody>
</table>
<?= $this->endSection() ?>
