<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container">
  <div class="akun-layout">
    <?= view('akun/_sidebar', ['pelanggan' => $pelanggan ?? [], 'aktifMenu' => $aktifMenu ?? 'order']) ?>
    <div class="akun-main">
      <h1 class="section-title" style="text-align:left;margin-bottom:1rem">Pesanan Saya</h1>
      <?php if (empty($list)): ?>
        <p class="akun-empty">Belum ada pesanan. <a href="<?= site_url('toko') ?>">Belanja di toko</a></p>
      <?php else: ?>
        <div class="akun-table-wrap">
          <table class="akun-table">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($list as $o): ?>
                <tr>
                  <td><?= esc($o['kode_order']) ?></td>
                  <td><?= date('d M Y', strtotime((string) $o['created_at'])) ?></td>
                  <td><?= format_rupiah($o['total_harga']) ?></td>
                  <td>
                    <span class="akun-badge"><?= esc($o['status_pembayaran']) ?></span>
                    <div class="akun-meta"><?= esc($o['status_order']) ?></div>
                  </td>
                  <td><a class="btn btn-sm btn-outline" href="<?= site_url('akun/order/' . $o['id']) ?>">Detail</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?= $this->endSection() ?>