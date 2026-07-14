<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container" style="max-width:640px;margin:0 auto">
  <h1>Status Pesanan</h1>
  <p>Kode: <strong><?= esc($order['kode_order']) ?></strong></p>
  <p>Pembayaran: <?= badge_status($order['status_pembayaran']) ?></p>
  <p>Order: <?= badge_status($order['status_order']) ?></p>
  <?php if (! empty($order['no_resi'])): ?>
    <p><strong>No. Resi:</strong> <?= esc($order['no_resi']) ?></p>
  <?php endif; ?>
  <hr style="border-color:var(--cream-dark);margin:1.5rem 0">
  <p><strong>Penerima:</strong> <?= esc($pelanggan['nama'] ?? '-') ?></p>
  <p><strong>Alamat:</strong> <?= esc($order['alamat_kirim']) ?></p>
  <p><strong>Kurir:</strong> <?= esc(strtoupper((string) $order['kurir'])) ?> <?= esc($order['layanan_kurir']) ?></p>
  <ul>
    <?php foreach ($items as $item): ?>
      <li><?= esc($item['nama_produk']) ?> × <?= (int) $item['jumlah'] ?> — <?= format_rupiah($item['subtotal']) ?></li>
    <?php endforeach; ?>
  </ul>
  <p>Ongkir: <?= format_rupiah($order['ongkos_kirim']) ?></p>
  <p class="price">Total: <?= format_rupiah($order['total_harga']) ?></p>
  <a href="<?= site_url('/') ?>" class="btn btn-outline">Kembali ke Beranda</a>
</section>
<?= $this->endSection() ?>
