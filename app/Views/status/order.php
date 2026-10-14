<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$isPaid = $order['status_pembayaran'] === 'paid';
$isPending = $order['status_pembayaran'] === 'pending';
$isFailed = in_array($order['status_pembayaran'], ['failed', 'expired'], true);
$isCatering = !empty($order['tanggal_acara']);
$statusIcon = $isPaid ? 'fa-check-circle' : ($isPending ? 'fa-clock' : ($isFailed ? 'fa-times-circle' : 'fa-info-circle'));
$statusColor = $isPaid ? 'var(--olive)' : ($isPending ? 'var(--gold)' : ($isFailed ? 'var(--terracotta)' : 'var(--sepia)'));
?>
<section class="section container" style="max-width:680px;margin:0 auto">

  <div class="status-hero">
    <i class="fas <?= $statusIcon ?>" style="font-size:2.5rem;color:<?= $statusColor ?>"></i>
    <h2 style="margin:0.5rem 0 0.15rem;font-size:1.4rem"><?= $isPaid ? 'Pembayaran Berhasil' : ($isPending ? 'Menunggu Pembayaran' : ($isFailed ? 'Pembayaran Gagal' : 'Status Pesanan')) ?></h2>
    <p style="color:var(--sepia);margin:0;font-size:0.95rem">Kode pesanan: <strong><?= esc($order['kode_order']) ?></strong></p>
  </div>

  <div class="status-badges">
    <div class="status-badge-item">
      <span style="color:var(--sepia);font-size:0.8rem">Pembayaran</span>
      <?= badge_status($order['status_pembayaran']) ?>
    </div>
    <div class="status-badge-item">
      <span style="color:var(--sepia);font-size:0.8rem">Status Order</span>
      <?= badge_status($order['status_order']) ?>
    </div>
  </div>

  <?php if (!empty($order['no_resi'])): ?>
    <div class="status-resi">
      <i class="fas fa-truck"></i>
      <div>
        <span style="font-size:0.8rem;color:var(--sepia)">No. Resi</span>
        <strong style="font-size:1.05rem;color:var(--deep);letter-spacing:0.5px"><?= esc($order['no_resi']) ?></strong>
      </div>
    </div>
  <?php endif; ?>

  <div class="status-card">
    <h3 class="status-card-title"><i class="fas fa-user"></i> Informasi Penerima</h3>
    <div class="status-card-grid">
      <div class="status-card-row">
        <span>Nama</span>
        <strong><?= esc($pelanggan['nama'] ?? '-') ?></strong>
      </div>
      <div class="status-card-row">
        <span>Alamat</span>
        <strong><?= esc($order['alamat_kirim']) ?></strong>
      </div>
    </div>
  </div>

  <?php if ($isCatering): ?>
    <div class="status-card">
      <h3 class="status-card-title"><i class="fas fa-calendar-alt"></i> Detail Catering</h3>
      <div class="status-card-grid">
        <div class="status-card-row">
          <span>Tanggal Acara</span>
          <strong><?= format_tanggal($order['tanggal_acara']) ?></strong>
        </div>
        <div class="status-card-row">
          <span>Waktu</span>
          <strong><?= esc($order['waktu_acara'] ?? '-') ?></strong>
        </div>
        <div class="status-card-row">
          <span>Metode</span>
          <strong><?= esc(ucwords(str_replace('_', ' ', (string) ($order['metode_pengiriman'] ?? '-')))) ?></strong>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <div class="status-card">
    <h3 class="status-card-title"><i class="fas fa-box-open"></i> Detail Pesanan</h3>
    <div class="status-items">
      <?php foreach ($items as $item): ?>
        <div class="status-item-row">
          <div>
            <span style="color:var(--deep)"><?= esc($item['nama_produk']) ?></span>
            <span style="color:var(--sepia);font-size:0.85rem">&times; <?= (int) $item['jumlah'] ?></span>
          </div>
          <span class="status-item-price"><?= format_rupiah($item['subtotal']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="status-card-title" style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px dashed var(--cream-dark)">
      <i class="fas fa-shipping-fast"></i> Pengiriman
    </div>
    <div class="status-card-row" style="margin-top:0.35rem">
      <span><?= esc(strtoupper((string) $order['kurir'])) ?> <?= esc($order['layanan_kurir']) ?></span>
      <strong><?= format_rupiah($order['ongkos_kirim']) ?></strong>
    </div>
  </div>

  <div class="status-total-card">
    <span style="font-size:1rem;color:var(--sepia)">Total Pembayaran</span>
    <span class="price" style="font-size:1.5rem;margin:0"><?= format_rupiah($order['total_harga']) ?></span>
  </div>

  <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;margin-top:1.5rem">
    <?php if (pelanggan_logged_in()): ?>
      <a href="<?= site_url('akun/pesanan') ?>" class="btn btn-primary">
        <i class="fas fa-list"></i> Pesanan Saya
      </a>
    <?php endif; ?>
    <a href="<?= site_url('/') ?>" class="btn btn-outline">
      <i class="fas fa-home"></i> Kembali ke Beranda
    </a>
  </div>
</section>

<style>
  .status-hero {
    text-align: center;
    padding: 1.5rem 1rem;
    background: var(--white);
    border: 1px solid var(--gold);
    border-radius: 10px;
    margin-bottom: 1rem;
  }
  .status-badges {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1rem;
  }
  .status-badge-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
    background: var(--white);
    border: 1px solid var(--cream-dark);
    border-radius: 8px;
    padding: 0.75rem 0.5rem;
  }
  .status-resi {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: var(--white);
    border: 1px solid var(--olive);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    color: var(--olive);
  }
  .status-resi div {
    display: flex;
    flex-direction: column;
  }
  .status-card {
    background: var(--white);
    border: 1px solid var(--cream-dark);
    border-radius: 8px;
    padding: 1rem 1.15rem;
    margin-bottom: 0.75rem;
  }
  .status-card:hover {
    border-color: var(--gold);
  }
  .status-card-title {
    font-family: var(--font-heading);
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--deep);
    margin: 0 0 0.65rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
  }
  .status-card-title i {
    color: var(--gold);
    font-size: 0.85rem;
  }
  .status-card-grid {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
  }
  .status-card-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    font-size: 0.9rem;
    line-height: 1.4;
  }
  .status-card-row > span:first-child {
    color: var(--sepia);
    flex-shrink: 0;
  }
  .status-card-row > strong {
    text-align: right;
    color: var(--deep);
    word-break: break-word;
  }
  .status-items {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
  }
  .status-item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    padding: 0.35rem 0;
    border-bottom: 1px dotted var(--cream-dark);
  }
  .status-item-row:last-child {
    border-bottom: none;
  }
  .status-item-price {
    font-family: var(--font-heading);
    font-weight: 600;
    color: var(--terracotta);
    white-space: nowrap;
  }
  .status-total-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--white);
    border: 2px solid var(--gold);
    border-radius: 10px;
    padding: 1rem 1.25rem;
  }
  @media (max-width: 480px) {
    .status-badges {
      flex-direction: column;
      gap: 0.5rem;
    }
    .status-card-row {
      flex-direction: column;
      gap: 0.1rem;
    }
    .status-card-row > strong {
      text-align: left;
    }
  }
</style>
<?= $this->endSection() ?>
