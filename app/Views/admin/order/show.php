<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<p><a href="<?= site_url('admin/order') ?>">&larr; Kembali</a></p>
<div
  style="background:var(--white);padding:1.25rem;border-radius:6px;border:1px solid var(--cream-dark);max-width:720px">
  <p><strong>Kode:</strong> <?= esc($order['kode_order']) ?></p>
  <p><strong>Pelanggan:</strong> <?= esc($order['pelanggan_nama']) ?> (<?= esc($order['no_hp']) ?>)</p>
  <p><strong>Alamat:</strong> <?= esc($order['alamat_kirim']) ?></p>
  <?php if (!empty($order['tanggal_acara'])): ?>
    <p><strong>Tanggal acara:</strong> <?= esc($order['tanggal_acara']) ?>   <?= esc($order['waktu_acara'] ?? '') ?></p>
    <p><strong>Metode:</strong> <?= esc(str_replace('_', ' ', (string) ($order['metode_pengiriman'] ?? ''))) ?></p>
  <?php endif; ?>
  <p><strong>Kurir:</strong> <?= esc($order['kurir']) ?> <?= esc($order['layanan_kurir']) ?> —
    <?= format_rupiah($order['ongkos_kirim']) ?></p>
  <p><strong>Resi:</strong> <?= esc($order['no_resi'] ?? '-') ?></p>
  <p><strong>Bayar:</strong> <?= badge_status($order['status_pembayaran']) ?></p>
  <p><strong>Status:</strong> <?= badge_status($order['status_order']) ?></p>
  <ul>
    <?php foreach ($items as $item): ?>
      <li><?= esc($item['nama_produk']) ?> × <?= (int) $item['jumlah'] ?> = <?= format_rupiah($item['subtotal']) ?></li>
    <?php endforeach; ?>
  </ul>
  <p class="price">Total: <?= format_rupiah($order['total_harga']) ?></p>

  <form method="post" action="<?= site_url('admin/order/' . $order['id'] . '/status') ?>" style="margin-top:1.25rem">
    <?= csrf_field() ?>
    <div class="form-group">
      <label>Ubah status order</label>
      <select name="status_order" class="form-control" id="status_order">
        <option value="diproses">Diproses</option>
        <option value="dikirim">Dikirim</option>
        <option value="selesai">Selesai</option>
        <option value="dibatalkan">Dibatalkan</option>
      </select>
    </div>
    <div class="form-group" id="resi-group">
      <label>No. Resi (wajib jika dikirim)</label>
      <input type="text" name="no_resi" class="form-control" value="<?= esc($order['no_resi'] ?? '') ?>">
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
  </form>
</div>
<?= $this->endSection() ?>