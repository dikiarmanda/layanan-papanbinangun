<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$hargaSatuan = (float) $paket['harga'];
$isHomestay = ($paket['jenis'] ?? '') === 'homestay';
$labelSatuan = $isHomestay ? 'malam' : 'orang';
?>
<section class="section container">
  <div class="booking-layout">
    <div>
      <?php if (!empty($paket['gambar_cover'])): ?>
        <img src="<?= media_url($paket['gambar_cover']) ?>" alt="<?= esc($paket['nama']) ?>"
          style="width:100%;max-height:420px;object-fit:cover;border-radius:8px">
      <?php endif; ?>
      <span class="badge-jenis <?= $isHomestay ? 'badge-homestay' : 'badge-wisata' ?>" style="margin-top:1.25rem">
        <?= $isHomestay ? 'Homestay' : 'Paket Wisata' ?>
      </span>
      <h1 style="margin-top:0.5rem"><?= esc($paket['nama']) ?></h1>
      <div class="price" style="margin-bottom:1rem">
        <?= format_rupiah($hargaSatuan) ?>
        <small style="font-weight:400;color:var(--sepia)">/ <?= esc($labelSatuan) ?></small>
      </div>
      <div style="white-space:pre-wrap"><?= esc($paket['deskripsi']) ?></div>

      <div class="policy-section" id="kebijakan">
        <h2 class="policy-title">Kebijakan</h2>

        <?php if ($isHomestay): ?>
          <div class="policy-item">
            <h3>Anak-anak</h3>
            <ul>
              <li>Tamu dari segala usia diperbolehkan menginap.</li>
              <li>Anak usia 12 tahun ke atas dihitung sebagai orang dewasa.</li>
              <li>Pastikan usia anak sesuai dengan data reservasi. Jika tidak sesuai, mungkin dikenakan biaya tambahan
                saat check-in.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Deposit</h3>
            <ul>
              <li>Tamu tidak perlu membayar deposit saat check-in.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Usia</h3>
            <ul>
              <li>Tamu dari segala usia diperbolehkan menginap.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Sarapan</h3>
            <ul>
              <li>Sarapan tersedia pukul 07:00 – 10:00 waktu setempat (jika termasuk dalam paket).</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Hewan peliharaan</h3>
            <ul>
              <li>Hewan peliharaan tidak diperbolehkan.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Merokok</h3>
            <ul>
              <li>Kamar bebas asap rokok. Merokok di dalam ruangan tidak diperbolehkan.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Alkohol</h3>
            <ul>
              <li>Minuman beralkohol diperbolehkan dengan tetap menjaga ketertiban.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Check-in &amp; check-out</h3>
            <ul>
              <li>Check-in mulai pukul 14:00. Check-out paling lambat pukul 12:00.</li>
              <li>Harga dihitung per malam per rumah sesuai tanggal yang dipilih.</li>
            </ul>
          </div>
        <?php else: ?>
          <div class="policy-item">
            <h3>Peserta</h3>
            <ul>
              <li>Peserta dari segala usia diperbolehkan mengikuti kegiatan, kecuali ada ketentuan khusus di deskripsi
                paket.</li>
              <li>Anak usia 12 tahun ke atas dihitung sebagai peserta dewasa.</li>
              <li>Pastikan jumlah dan usia peserta sesuai data reservasi. Jika tidak sesuai, mungkin dikenakan biaya
                tambahan.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Pembayaran</h3>
            <ul>
              <li>Reservasi dikonfirmasi setelah pembayaran berhasil.</li>
              <li>Tidak ada deposit tambahan di lokasi, kecuali disebutkan di deskripsi paket.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Jadwal &amp; kehadiran</h3>
            <ul>
              <li>Harap hadir sesuai tanggal dan waktu yang dipilih.</li>
              <li>Keterlambatan signifikan dapat mengurangi durasi kegiatan tanpa pengembalian biaya.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Cuaca &amp; kondisi lapangan</h3>
            <ul>
              <li>Kegiatan outdoor dapat menyesuaikan cuaca dengan tetap mengutamakan keselamatan peserta.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Hewan peliharaan</h3>
            <ul>
              <li>Hewan peliharaan tidak diperbolehkan selama kegiatan, kecuali diatur khusus oleh pengelola.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Merokok &amp; alkohol</h3>
            <ul>
              <li>Merokok hanya di area yang diizinkan pengelola.</li>
              <li>Minuman beralkohol diperbolehkan dengan tetap menjaga ketertiban.</li>
            </ul>
          </div>
          <div class="policy-item">
            <h3>Pembatalan</h3>
            <ul>
              <li>Pembatalan mengikuti ketentuan pengelola desa. Hubungi kontak resmi untuk bantuan.</li>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="booking-card">
      <h2>Pesan sekarang</h2>
      <p class="hint">
        <?php if ($isHomestay): ?>
          Pilih check-in &amp; check-out. Harga dihitung per malam × harga rumah.
        <?php else: ?>
          Pilih tanggal jadwal dan jumlah tamu. Harga dihitung per orang.
        <?php endif; ?>
      </p>

      <?php if (empty($jadwal)): ?>
        <div class="alert alert-info">Belum ada jadwal tersedia. Hubungi pengelola desa.</div>
      <?php else: ?>
        <form method="post" action="<?= site_url('checkout-reservasi') ?>" id="form-reservasi">
          <?= csrf_field() ?>
          <input type="hidden" name="paket_wisata_id" value="<?= (int) $paket['id'] ?>">

          <?php if ($isHomestay): ?>
            <div class="form-group">
              <label>Check-in</label>
              <input type="text" name="check_in" id="check_in" class="form-control datepicker" required
                placeholder="Pilih tanggal check-in" data-min="<?= date('Y-m-d') ?>"
                value="<?= esc(old('check_in') ?: '') ?>" autocomplete="off">
            </div>
            <div class="form-group">
              <label>Check-out</label>
              <input type="text" name="check_out" id="check_out" class="form-control datepicker" required
                placeholder="Pilih tanggal check-out" data-min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                value="<?= esc(old('check_out') ?: '') ?>" autocomplete="off">
            </div>
            <p id="avail-msg" class="availability-msg" aria-live="polite"></p>
            <div class="form-group">
              <label>Jumlah tamu (opsional)</label>
              <input type="number" name="jumlah_tamu" id="jumlah_tamu" class="form-control" min="1"
                value="<?= esc(old('jumlah_tamu') ?: '1') ?>">
            </div>
          <?php else: ?>
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
              <input type="number" name="jumlah_tamu" id="jumlah_tamu" class="form-control" min="1"
                value="<?= esc(old('jumlah_tamu') ?: '1') ?>" required>
            </div>
          <?php endif; ?>

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
            <textarea name="catatan" class="form-control" rows="2"><?= esc(old('catatan')) ?></textarea>
          </div>

          <div class="estimasi-box">
            <div class="estimasi-row">
              <span>Harga satuan</span>
              <span><?= format_rupiah($hargaSatuan) ?> / <?= esc($labelSatuan) ?></span>
            </div>
            <div class="estimasi-row">
              <span id="estimasi-label"><?= $isHomestay ? 'Jumlah malam' : 'Jumlah tamu' ?></span>
              <span id="estimasi-qty">1</span>
            </div>
            <div class="estimasi-row estimasi-total">
              <strong>Total estimasi</strong>
              <strong class="price" id="estimasi-total" style="margin:0"><?= format_rupiah($hargaSatuan) ?></strong>
            </div>
          </div>

          <label class="policy-agree">
            <input type="checkbox" name="setuju_kebijakan" id="setuju_kebijakan" value="1" required>
            <span>Saya telah membaca dan menyetujui <a href="#kebijakan">kebijakan dan persyaratan</a> di atas.</span>
          </label>

          <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem" id="btn-submit" disabled>
            Lanjut ke Pembayaran
          </button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  (() => {
    const hargaSatuan = <?= json_encode($hargaSatuan) ?>;
    const isHomestay = <?= $isHomestay ? 'true' : 'false' ?>;
    const paketId = <?= (int) $paket['id'] ?>;
    const elTotal = document.getElementById('estimasi-total');
    const elQty = document.getElementById('estimasi-qty');
    const elLabel = document.getElementById('estimasi-label');
    const btn = document.getElementById('btn-submit');
    const agreeEl = document.getElementById('setuju_kebijakan');
    let availabilityOk = !isHomestay;

    function formatRp(n) {
      return 'Rp ' + Math.round(Number(n)).toLocaleString('id-ID');
    }

    function nightsBetween(a, b) {
      if (!a || !b) return 0;
      const start = new Date(a + 'T00:00:00');
      const end = new Date(b + 'T00:00:00');
      const diff = (end - start) / 86400000;
      return diff > 0 ? Math.floor(diff) : 0;
    }

    function syncSubmit() {
      if (!btn) return;
      const agreed = !!(agreeEl && agreeEl.checked);
      btn.disabled = !(agreed && availabilityOk);
    }

    async function updateHomestay() {
      const checkIn = document.getElementById('check_in')?.value;
      const checkOut = document.getElementById('check_out')?.value;
      const nights = nightsBetween(checkIn, checkOut);
      const msg = document.getElementById('avail-msg');

      elLabel.textContent = 'Jumlah malam';
      elQty.textContent = nights > 0 ? (nights + ' malam') : '—';
      elTotal.textContent = nights > 0 ? formatRp(hargaSatuan * nights) : '—';

      if (!checkIn || !checkOut || nights < 1) {
        if (msg) { msg.textContent = ''; msg.className = 'availability-msg'; }
        availabilityOk = false;
        syncSubmit();
        return;
      }

      availabilityOk = false;
      syncSubmit();
      if (msg) { msg.textContent = 'Memeriksa ketersediaan…'; msg.className = 'availability-msg'; }

      try {
        const url = '<?= site_url('api/homestay-availability') ?>'
          + '?paket_id=' + paketId
          + '&check_in=' + encodeURIComponent(checkIn)
          + '&check_out=' + encodeURIComponent(checkOut);
        const res = await fetch(url);
        const data = await res.json();
        if (msg) {
          msg.textContent = data.message || '';
          msg.className = 'availability-msg ' + (data.ok ? 'ok' : 'err');
        }
        availabilityOk = !!data.ok;
      } catch (e) {
        if (msg) { msg.textContent = 'Gagal cek ketersediaan'; msg.className = 'availability-msg err'; }
        availabilityOk = false;
      }
      syncSubmit();
    }

    function updateWisata() {
      const jumlah = Math.max(1, parseInt(document.getElementById('jumlah_tamu')?.value || '1', 10) || 1);
      elLabel.textContent = 'Jumlah tamu';
      elQty.textContent = jumlah + ' orang';
      elTotal.textContent = formatRp(hargaSatuan * jumlah);
      availabilityOk = true;
      syncSubmit();
    }

    agreeEl?.addEventListener('change', syncSubmit);

    if (isHomestay) {
      window.__onHomestayDatesChange = updateHomestay;
      document.getElementById('check_in')?.addEventListener('change', updateHomestay);
      document.getElementById('check_out')?.addEventListener('change', updateHomestay);
      updateHomestay();
    } else {
      document.getElementById('jumlah_tamu')?.addEventListener('input', updateWisata);
      updateWisata();
    }

    syncSubmit();
  })();
</script>
<?= $this->endSection() ?>