<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php $r = $reservasi;
$paket = $paket ?? null; ?>
<section class="section container">
  <div class="akun-layout">
    <?= view('akun/_sidebar', ['pelanggan' => $pelanggan ?? [], 'aktifMenu' => $aktifMenu ?? 'reservasi']) ?>
    <div class="akun-main">
      <p class="akun-back"><a href="<?= site_url('akun/reservasi') ?>">← Kembali</a></p>
      <h1 class="section-title" style="text-align:left;margin-bottom:0.35rem"><?= esc($r['kode_reservasi']) ?></h1>
      <p class="akun-lead"><?= esc($paket['nama'] ?? 'Paket') ?></p>

      <div class="akun-detail-grid">
        <div class="akun-panel">
          <h2>Detail booking</h2>
          <dl class="akun-dl">
            <div>
              <dt>Pembayaran</dt>
              <dd><span
                  class="badge <?= esc((string) $r['status_pembayaran']) ?>"><?= esc(label_status_pembayaran((string) $r['status_pembayaran'])) ?></span>
              </dd>
            </div>
            <div>
              <dt>Status reservasi</dt>
              <dd><span
                  class="badge badge-reservasi"><?= esc(label_status_reservasi((string) $r['status_reservasi'])) ?></span>
              </dd>
            </div>
            <div>
              <dt>Jumlah tamu</dt>
              <dd><?= (int) $r['jumlah_tamu'] ?> orang</dd>
            </div>
            <?php if (!empty($r['check_in'])): ?>
              <div>
                <dt>Check-in</dt>
                <dd><?= esc(format_tanggal($r['check_in'])) ?></dd>
              </div>
              <div>
                <dt>Check-out</dt>
                <dd><?= esc(format_tanggal($r['check_out'] ?? null)) ?></dd>
              </div>
              <div>
                <dt>Malam</dt>
                <dd><?= (int) ($r['jumlah_malam'] ?? 0) ?></dd>
              </div>
            <?php else: ?>
              <?php if (!empty($jadwal['tanggal'])): ?>
                <div>
                  <dt>Tanggal kegiatan</dt>
                  <dd><?= esc(format_tanggal($jadwal['tanggal'])) ?></dd>
                </div>
              <?php endif; ?>
            <?php endif; ?>
            <div>
              <dt>Total</dt>
              <dd><?= format_rupiah($r['total_harga']) ?></dd>
            </div>
            <?php if (!empty($r['catatan'])): ?>
              <div>
                <dt>Catatan</dt>
                <dd><?= esc($r['catatan']) ?></dd>
              </div>
            <?php endif; ?>
            <div>
              <dt>Dibuat</dt>
              <dd><?= date('d M Y H:i', strtotime((string) $r['created_at'])) ?></dd>
            </div>
          </dl>
        </div>
        <div class="akun-panel">
          <h2>Aksi</h2>
          <p class="akun-meta" style="margin-bottom:1rem">Gunakan kode di atas untuk cek status publik atau lanjut
            pembayaran.</p>
          <div class="akun-actions">
            <a class="btn btn-primary" href="<?= site_url('status/' . $r['kode_reservasi']) ?>">Lihat status publik</a>
            <?php if (!empty($paket['slug'])): ?>
              <a class="btn btn-outline" href="<?= site_url('paket-wisata/' . $paket['slug']) ?>">Lihat paket</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>