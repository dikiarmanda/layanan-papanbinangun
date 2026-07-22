<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<p><a href="<?= site_url('admin/reservasi') ?>">&larr; Kembali</a></p>
<div
  style="background:var(--white);padding:1.25rem;border-radius:6px;border:1px solid var(--cream-dark);max-width:640px">
  <p><strong>Kode:</strong> <?= esc($reservasi['kode_reservasi']) ?></p>
  <p><strong>Pelanggan:</strong> <?= esc($reservasi['pelanggan_nama']) ?> (<?= esc($reservasi['no_hp']) ?>)</p>
  <p><strong>Paket:</strong> <?= esc($reservasi['paket_nama']) ?>
    <small>(<?= esc($reservasi['paket_jenis'] ?? '-') ?>)</small></p>
  <?php if (!empty($reservasi['check_in'])): ?>
    <p><strong>Check-in:</strong> <?= esc($reservasi['check_in']) ?></p>
    <p><strong>Check-out:</strong> <?= esc($reservasi['check_out']) ?></p>
    <p><strong>Jumlah malam:</strong> <?= (int) ($reservasi['jumlah_malam'] ?? 0) ?></p>
  <?php endif; ?>
  <p><strong>Jumlah tamu:</strong> <?= (int) $reservasi['jumlah_tamu'] ?></p>
  <p><strong>Total:</strong> <?= format_rupiah($reservasi['total_harga']) ?></p>
  <p><strong>Bayar:</strong> <?= badge_status($reservasi['status_pembayaran']) ?></p>
  <p><strong>Status:</strong> <?= badge_status($reservasi['status_reservasi']) ?></p>
  <p><strong>Midtrans order:</strong> <?= esc($reservasi['midtrans_order_id'] ?? '-') ?></p>

  <form method="post" action="<?= site_url('admin/reservasi/' . $reservasi['id'] . '/status') ?>"
    style="margin-top:1.25rem">
    <?= csrf_field() ?>
    <div class="form-group">
      <label>Ubah status reservasi</label>
      <select name="status_reservasi" class="form-control">
        <option value="dikonfirmasi">Dikonfirmasi</option>
        <option value="selesai">Selesai</option>
        <option value="dibatalkan">Dibatalkan</option>
      </select>
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
  </form>
</div>
<?= $this->endSection() ?>