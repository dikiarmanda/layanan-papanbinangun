<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<table class="table">
  <thead><tr><th>Waktu</th><th>Tipe</th><th>Order ID</th><th>Status</th><th>Signature</th></tr></thead>
  <tbody>
    <?php foreach ($logs as $log): ?>
      <tr>
        <td><?= esc($log['created_at']) ?></td>
        <td><?= esc($log['tipe']) ?></td>
        <td><code><?= esc($log['midtrans_order_id']) ?></code></td>
        <td><?= esc($log['transaction_status']) ?></td>
        <td><?= (int) $log['signature_valid'] ? 'valid' : 'invalid' ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (empty($logs)): ?><tr><td colspan="5">Belum ada notifikasi</td></tr><?php endif; ?>
  </tbody>
</table>
<?= $this->endSection() ?>
