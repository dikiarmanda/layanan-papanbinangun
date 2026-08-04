<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php $o = $order;
$items = $items ?? []; ?>
<section class="section container">
  <div class="akun-layout">
    <?= view('akun/_sidebar', ['pelanggan' => $pelanggan ?? [], 'aktifMenu' => $aktifMenu ?? 'order']) ?>
    <div class="akun-main">
      <p class="akun-back"><a href="<?= site_url('akun/order') ?>">← Kembali</a></p>
      <h1 class="section-title" style="text-align:left;margin-bottom:0.35rem"><?= esc($o['kode_order']) ?></h1>
      <p class="akun-lead"><?= format_rupiah($o['total_harga']) ?></p>

      <div class="akun-detail-grid">
        <div class="akun-panel">
          <h2>Ringkasan</h2>
          <dl class="akun-dl">
            <div>
              <dt>Pembayaran</dt>
              <dd><?= esc($o['status_pembayaran']) ?></dd>
            </div>
            <div>
              <dt>Status order</dt>
              <dd><?= esc($o['status_order']) ?></dd>
            </div>
            <?php if (!empty($o['metode_pengiriman'])): ?>
              <div>
                <dt>Pengiriman</dt>
                <dd><?= esc($o['metode_pengiriman']) ?></dd>
              </div>
            <?php endif; ?>
            <?php if (!empty($o['alamat_kirim'])): ?>
              <div>
                <dt>Alamat</dt>
                <dd><?= esc($o['alamat_kirim']) ?></dd>
              </div>
            <?php endif; ?>
            <?php if (!empty($o['tanggal_acara'])): ?>
              <div>
                <dt>Acara</dt>
                <dd><?= esc($o['tanggal_acara']) ?>   <?= esc($o['waktu_acara'] ?? '') ?></dd>
              </div>
            <?php endif; ?>
            <?php if (!empty($o['no_resi'])): ?>
              <div>
                <dt>Resi</dt>
                <dd><?= esc($o['no_resi']) ?></dd>
              </div>
            <?php endif; ?>
            <div>
              <dt>Ongkir</dt>
              <dd><?= format_rupiah($o['ongkos_kirim'] ?? 0) ?></dd>
            </div>
            <div>
              <dt>Dibuat</dt>
              <dd><?= date('d M Y H:i', strtotime((string) $o['created_at'])) ?></dd>
            </div>
          </dl>
        </div>
        <div class="akun-panel">
          <h2>Item</h2>
          <?php if (empty($items)): ?>
            <p class="akun-empty">Tidak ada item.</p>
          <?php else: ?>
            <ul class="akun-item-list">
              <?php foreach ($items as $it): ?>
                <li>
                  <span><?= esc($it['nama_produk'] ?? 'Item') ?> × <?= (int) $it['jumlah'] ?></span>
                  <strong><?= format_rupiah($it['subtotal'] ?? (($it['harga_satuan'] ?? 0) * ($it['jumlah'] ?? 0))) ?></strong>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <a class="btn btn-primary" style="margin-top:1rem" href="<?= site_url('status/' . $o['kode_order']) ?>">Lihat
            status publik</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>