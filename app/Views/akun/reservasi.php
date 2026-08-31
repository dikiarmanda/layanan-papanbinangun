<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container">
  <div class="akun-layout">
    <?= view('akun/_sidebar', ['pelanggan' => $pelanggan ?? [], 'aktifMenu' => $aktifMenu ?? 'reservasi']) ?>
    <div class="akun-main">
      <h1 class="section-title" style="text-align:left;margin-bottom:1rem">Reservasi Saya</h1>
      <?php if (empty($list)): ?>
        <p class="akun-empty">Belum ada reservasi. <a href="<?= site_url('paket-wisata') ?>">Pesan paket sekarang</a></p>
      <?php else: ?>
        <div class="akun-table-wrap">
          <table class="akun-table akun-table--cards">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Paket</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($list as $r): ?>
                <tr>
                  <td data-label="Kode"><?= esc($r['kode_reservasi']) ?></td>
                  <td data-label="Paket">
                    <?= esc($r['paket_nama']) ?>
                    <div class="akun-meta"><?= esc(label_jenis_paket($r['paket_jenis'] ?? 'wisata')) ?></div>
                  </td>
                  <td data-label="Tanggal">
                    <?php if (!empty($r['check_in'])): ?>
                      <?= esc(format_tanggal($r['check_in'])) ?> →
                      <?= esc(format_tanggal($r['check_out'] ?? null)) ?>
                    <?php else: ?>
                      <?= date('d M Y', strtotime((string) $r['created_at'])) ?>
                    <?php endif; ?>
                  </td>
                  <td data-label="Total"><?= format_rupiah($r['total_harga']) ?></td>
                  <td data-label="Status">
                    <span class="akun-badge"><?= esc(label_status_pembayaran((string) $r['status_pembayaran'])) ?></span>
                    <div class="akun-meta"><?= esc(label_status_reservasi((string) $r['status_reservasi'])) ?></div>
                  </td>
                  <td data-label=""><a class="btn btn-sm btn-outline"
                      href="<?= site_url('akun/reservasi/' . $r['id']) ?>">Detail</a></td>
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