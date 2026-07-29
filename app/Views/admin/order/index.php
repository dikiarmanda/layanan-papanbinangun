<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p>Daftar order produk UMKM dan catering.</p>
</div>

<div class="card card-table">
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
        <?php if (empty($orders)): ?>
          <tr>
            <td colspan="5" class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              Belum ada order.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td><a href="<?= site_url('admin/order/' . $o['id']) ?>"><?= esc($o['kode_order']) ?></a></td>
              <td>
                <?= esc($o['pelanggan_nama']) ?>
                <div class="text-muted" style="font-size:0.82rem"><?= esc($o['no_hp']) ?></div>
              </td>
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

<?= $this->endSection() ?>
