<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p>Daftar reservasi wisata, homestay, dan camping.</p>
</div>

<div class="card card-table">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Kode</th>
          <th>Pelanggan</th>
          <th>Paket</th>
          <th>Total</th>
          <th>Bayar</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($reservasi)): ?>
          <tr>
            <td colspan="6" class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              Belum ada reservasi.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($reservasi as $r): ?>
            <tr>
              <td><a href="<?= site_url('admin/reservasi/' . $r['id']) ?>"><?= esc($r['kode_reservasi']) ?></a></td>
              <td>
                <?= esc($r['pelanggan_nama']) ?>
                <div class="text-muted" style="font-size:0.82rem"><?= esc($r['no_hp']) ?></div>
              </td>
              <td><?= esc($r['paket_nama']) ?></td>
              <td><?= format_rupiah($r['total_harga']) ?></td>
              <td><?= badge_status($r['status_pembayaran']) ?></td>
              <td><?= badge_status($r['status_reservasi']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
