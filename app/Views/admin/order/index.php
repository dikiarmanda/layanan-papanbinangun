<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<table class="table">
  <thead><tr><th>Kode</th><th>Pelanggan</th><th>Total</th><th>Bayar</th><th>Status</th></tr></thead>
  <tbody>
    <?php foreach ($orders as $o): ?>
      <tr>
        <td><a href="<?= site_url('admin/order/' . $o['id']) ?>"><?= esc($o['kode_order']) ?></a></td>
        <td><?= esc($o['pelanggan_nama']) ?><br><small><?= esc($o['no_hp']) ?></small></td>
        <td><?= format_rupiah($o['total_harga']) ?></td>
        <td><?= badge_status($o['status_pembayaran']) ?></td>
        <td><?= badge_status($o['status_order']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= $this->endSection() ?>
