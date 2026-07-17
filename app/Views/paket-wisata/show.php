<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container">
  <div style="display:grid;grid-template-columns:1fr;gap:2rem">
    <div>
      <?php if (! empty($paket['gambar_cover'])): ?>
        <img src="<?= media_url($paket['gambar_cover']) ?>" alt="<?= esc($paket['nama']) ?>" style="width:100%;max-height:420px;object-fit:cover;border-radius:6px">
      <?php endif; ?>
      <h1 style="margin-top:1.25rem"><?= esc($paket['nama']) ?></h1>
      <div class="price" style="margin-bottom:1rem"><?= format_rupiah($paket['harga']) ?> / <?= esc(str_replace('_', ' ', $paket['satuan_harga'])) ?></div>
      <div style="white-space:pre-wrap"><?= esc($paket['deskripsi']) ?></div>
    </div>

    <div style="background:var(--white);border:1px solid var(--cream-dark);border-radius:6px;padding:1.5rem">
      <h2 style="font-size:1.4rem">Form Reservasi</h2>
      <?php if (empty($jadwal)): ?>
        <div class="alert alert-info">Belum ada jadwal tersedia. Hubungi pengelola desa.</div>
      <?php else: ?>
        <form method="post" action="<?= site_url('checkout-reservasi') ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="paket_wisata_id" value="<?= (int) $paket['id'] ?>">
          <div class="form-group">
            <label>Pilih Tanggal</label>
            <select name="jadwal_id" class="form-control" required>
              <?php foreach ($jadwal as $j): ?>
                <?php $sisa = (int) $j['kuota'] - (int) $j['kuota_terpakai']; ?>
                <option value="<?= (int) $j['id'] ?>" <?= $sisa <= 0 ? 'disabled' : '' ?>>
                  <?= esc($j['tanggal']) ?> — sisa <?= $sisa ?>/<?= (int) $j['kuota'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Jumlah Tamu</label>
            <input type="number" name="jumlah_tamu" class="form-control" min="1" value="1" required>
          </div>
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="<?= esc(old('nama')) ?>" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>" required>
          </div>
          <div class="form-group">
            <label>No. HP / WhatsApp</label>
            <input type="text" name="no_hp" class="form-control" value="<?= esc(old('no_hp')) ?>" required>
          </div>
          <div class="form-group">
            <label>Catatan (opsional)</label>
            <textarea name="catatan" class="form-control" rows="3"><?= esc(old('catatan')) ?></textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%">Lanjut ke Pembayaran</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>
<?= $this->endSection() ?>
