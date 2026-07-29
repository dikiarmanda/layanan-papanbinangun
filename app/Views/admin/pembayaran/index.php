<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p>Log notifikasi webhook Midtrans.</p>
</div>

<div class="card card-table">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Waktu</th>
          <th>Tipe</th>
          <th>Order ID</th>
          <th>Status</th>
          <th>Signature</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($logs)): ?>
          <tr>
            <td colspan="5" class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              Belum ada notifikasi.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($logs as $log): ?>
            <tr>
              <td><?= esc($log['created_at']) ?></td>
              <td><?= esc($log['tipe']) ?></td>
              <td><code><?= esc($log['midtrans_order_id']) ?></code></td>
              <td><?= esc($log['transaction_status']) ?></td>
              <td><?= (int) $log['signature_valid'] ? 'valid' : 'invalid' ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
