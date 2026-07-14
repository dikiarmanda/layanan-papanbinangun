<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<table class="table">
  <thead><tr><th>Kode</th><th>Pelanggan</th><th>Paket</th><th>Total</th><th>Bayar</th><th>Status</th></tr></thead>
  <tbody>
    <?php foreach ($reservasi as $r): ?>
      <tr>
        <td><a href="<?= site_url('admin/reservasi/' . $r['id']) ?>"><?= esc($r['kode_reservasi']) ?></a></td>
        <td><?= esc($r['pelanggan_nama']) ?><br><small><?= esc($r['no_hp']) ?></small></td>
        <td><?= esc($r['paket_nama']) ?></td>
        <td><?= format_rupiah($r['total_harga']) ?></td>
        <td><?= badge_status($r['status_pembayaran']) ?></td>
        <td><?= badge_status($r['status_reservasi']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= $this->endSection() ?>
