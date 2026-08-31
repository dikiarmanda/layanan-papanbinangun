<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$hargaSatuan = (float) $paket['harga'];
$jenisPaket = $paket['jenis'] ?? 'wisata';
$isHomestay = is_paket_menginap($jenisPaket);
$labelSatuan = satuan_label($jenisPaket, $paket['satuan_harga'] ?? null);
$badgeClass = match ($jenisPaket) {
  'homestay' => 'badge-homestay',
  'camping' => 'badge-camping',
  default => 'badge-wisata',
};
$availabilityMap = $availabilityMap ?? [];
$jadwalByDate = $jadwalByDate ?? [];
$calendarMonths = [];
$calCursor = new DateTimeImmutable('first day of this month');
$calEnd = new DateTimeImmutable('first day of +2 months');
while ($calCursor < $calEnd) {
  $calendarMonths[] = $calCursor->format('Y-m');
  $calCursor = $calCursor->modify('first day of next month');
}
$hariNama = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

$prefillQty = (int) old('jumlah_tamu');
if ($prefillQty < 1) {
  $prefillQty = (int) (request()->getGet('qty') ?: 1);
}
$prefillQty = max(1, min(999, $prefillQty));

$prefillCheckIn = (string) (old('check_in') ?: request()->getGet('check_in') ?? '');
$prefillCheckOut = (string) (old('check_out') ?: request()->getGet('check_out') ?? '');
if ($prefillCheckIn !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $prefillCheckIn)) {
  $prefillCheckIn = '';
}
if ($prefillCheckOut !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $prefillCheckOut)) {
  $prefillCheckOut = '';
}
$hasPrefill = $prefillQty > 1 || $prefillCheckIn !== '' || $prefillCheckOut !== '';
?>
<section class="section container">
  <div class="booking-layout">
    <div>
      <img src="<?= esc(cover_url($paket['gambar_cover'] ?? null, $jenisPaket)) ?>" alt="<?= esc($paket['nama']) ?>"
        style="width:100%;max-height:420px;object-fit:cover;border-radius:8px">
      <span class="badge-jenis <?= $badgeClass ?>" style="margin-top:1.25rem">
        <?= esc(label_jenis_paket($jenisPaket)) ?>
      </span>
      <h1 style="margin-top:0.5rem"><?= esc($paket['nama']) ?></h1>
      <div class="price" style="margin-bottom:1rem">
        <?= format_rupiah($hargaSatuan) ?>
        <small style="font-weight:400;color:var(--sepia)">/ <?= esc($labelSatuan) ?></small>
      </div>
      <div style="white-space:pre-wrap"><?= esc($paket['deskripsi']) ?></div>

      <div class="avail-calendar" id="kalender-ketersediaan">
        <div class="avail-calendar-head">
          <h2 class="policy-title" style="margin:0">Kalender Ketersediaan</h2>
          <div class="avail-legend">
            <span class="avail-legend-item"><i class="dot is-available"></i>Tersedia</span>
            <span class="avail-legend-item"><i class="dot is-full"></i>Penuh</span>
            <span class="avail-legend-item"><i class="dot is-none"></i>Belum ada jadwal</span>
          </div>
        </div>

        <div class="avail-months">
          <?php foreach ($calendarMonths as $calKey): ?>
            <?php
            $firstDay = new DateTimeImmutable($calKey . '-01');
            $daysInMonth = (int) $firstDay->format('t');
            $monthLabel = format_tanggal($firstDay->format('Y-m-d'), false);
            $leading = (int) $firstDay->format('N') - 1;
            ?>
            <div class="avail-month">
              <div class="avail-month-title"><?= esc(format_tanggal($firstDay->format('Y-m') . '-01')) ?></div>
              <div class="avail-weekdays">
                <?php foreach ($hariNama as $nama): ?><span><?= $nama ?></span><?php endforeach; ?>
              </div>
              <div class="avail-grid">
                <?php for ($i = 0; $i < $leading; $i++): ?><span class="avail-day is-empty"></span><?php endfor; ?>
                <?php for ($d = 1; $d <= $daysInMonth; $d++): ?>
                  <?php
                  $tgl = $calKey . '-' . str_pad((string) $d, 2, '0', STR_PAD_LEFT);
                  $state = $availabilityMap[$tgl] ?? 'none';
                  $isClickable = $state === 'available';
                  ?>
                  <button type="button" class="avail-day is-<?= $state ?>" data-tanggal="<?= $tgl ?>"
                    data-state="<?= $state ?>" <?= $isClickable ? '' : 'disabled' ?>>
                    <span class="avail-day-num"><?= $d ?></span>
                    <?php if ($state === 'full'): ?><span class="avail-day-tag">Penuh</span><?php endif; ?>
                  </button>
                <?php endfor; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <p class="avail-hint">Klik tanggal yang <strong>tersedia</strong> untuk mengisi formulir di samping.</p>
      </div>

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
              <li>Pastikan jumlah dan usia peserta sesuai data reservasi. Jika belum sesuai, mungkin dikenakan biaya
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
              <li>Minuman beralkohol diperbolehkan dengan menjaga ketertiban.</li>
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
          Pilih check-in &amp; check-out. Harga dihitung per malam × satuan.
        <?php else: ?>
          Pilih tanggal jadwal dan jumlah tamu. Harga dihitung per orang.
        <?php endif; ?>
      </p>

      <?php if (empty($jadwal) && $isHomestay): ?>
        <div class="alert alert-info">Belum ada jadwal tersedia. Hubungi pengelola desa.</div>
      <?php elseif (empty($jadwal)): ?>
        <div class="alert alert-info">Belum ada jadwal wisata. Hubungi pengelola desa.</div>
      <?php else: ?>
        <form method="post" action="<?= site_url('checkout-reservasi') ?>" id="form-reservasi">
          <?= csrf_field() ?>
          <input type="hidden" name="paket_wisata_id" value="<?= (int) $paket['id'] ?>">

          <?php if ($isHomestay): ?>
            <div class="form-group">
              <label>Check-in</label>
              <input type="text" name="check_in" id="check_in" class="form-control datepicker" required
                placeholder="Pilih tanggal check-in" data-min="<?= date('Y-m-d') ?>" value="<?= esc($prefillCheckIn) ?>"
                autocomplete="off">
            </div>
            <div class="form-group">
              <label>Check-out</label>
              <input type="text" name="check_out" id="check_out" class="form-control datepicker" required
                placeholder="Pilih tanggal check-out" data-min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                value="<?= esc($prefillCheckOut) ?>" autocomplete="off">
            </div>
            <p id="avail-msg" class="availability-msg" aria-live="polite"></p>
            <div class="form-group">
              <label>Jumlah tamu (opsional)</label>
              <input type="number" name="jumlah_tamu" id="jumlah_tamu" class="form-control" min="1"
                value="<?= esc((string) $prefillQty) ?>">
            </div>
          <?php else: ?>
            <div class="form-group">
              <label>Pilih Tanggal</label>
              <select name="jadwal_id" id="jadwal_id" class="form-control" required>
                <?php foreach ($jadwal as $j): ?>
                  <?php $sisa = (int) $j['kuota'] - (int) $j['kuota_terpakai']; ?>
                  <option value="<?= (int) $j['id'] ?>" data-tanggal="<?= $j['tanggal'] ?>" data-sisa="<?= $sisa ?>" <?= $sisa <= 0 ? 'disabled' : '' ?>>
                    <?= esc(format_tanggal($j['tanggal'])) ?> — sisa <?= $sisa ?>/<?= (int) $j['kuota'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Jumlah Tamu</label>
              <input type="number" name="jumlah_tamu" id="jumlah_tamu" class="form-control" min="1" max="999"
                value="<?= esc((string) $prefillQty) ?>" required>
            </div>
          <?php endif; ?>

          <?php
          $pLogin = pelanggan_aktif();
          $pDb = $pLogin ? model(\App\Models\PelangganModel::class)->find($pLogin['id']) : null;
          ?>
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control"
              value="<?= esc(old('nama', $pDb['nama'] ?? $pLogin['nama'] ?? '')) ?>" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
              value="<?= esc(old('email', $pDb['email'] ?? $pLogin['email'] ?? '')) ?>" required>
          </div>
          <div class="form-group">
            <label>No. HP / WhatsApp</label>
            <input type="text" name="no_hp" class="form-control" value="<?= esc(old('no_hp', $pDb['no_hp'] ?? '')) ?>"
              required>
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
            <span>Saya telah membaca dan menyetujui
              <a href="<?= site_url('kebijakan-privasi') ?>" target="_blank" rel="noopener">kebijakan privasi</a>
              serta
              <a href="<?= site_url('persyaratan') ?>" target="_blank" rel="noopener">syarat &amp; ketentuan</a>,
              termasuk <a href="#kebijakan">kebijakan layanan</a> di atas.
            </span>
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
    const jadwalByDate = <?= json_encode($jadwalByDate) ?>;
    const elTotal = document.getElementById('estimasi-total');
    const elQty = document.getElementById('estimasi-qty');
    const elLabel = document.getElementById('estimasi-label');
    const btn = document.getElementById('btn-submit');
    const agreeEl = document.getElementById('setuju_kebijakan');
    const { formatRupiah: formatRp, nightsBetween, addDays } = window.AppUtils;
    const hasPrefill = <?= $hasPrefill ? 'true' : 'false' ?>;
    let availabilityOk = !isHomestay;

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

    // Klik tanggal tersedia di kalender → isi formulir
    document.querySelectorAll('.avail-day[data-state="available"]').forEach(function (cell) {
      cell.addEventListener('click', function () {
        const tgl = cell.getAttribute('data-tanggal');
        if (!tgl) return;

        if (isHomestay) {
          const cin = document.getElementById('check_in');
          if (cin && cin._flatpickr) cin._flatpickr.setDate(tgl, true);
          else if (cin) cin.value = tgl;

          const cout = document.getElementById('check_out');
          const currentOut = cout ? cout.value : '';
          const firstNight = addDays(tgl, 1);
          if (!currentOut || currentOut <= tgl) {
            const target = currentOut && currentOut > tgl ? currentOut : firstNight;
            if (cout && cout._flatpickr) cout._flatpickr.setDate(target, true);
            else if (cout) cout.value = target;
          }

          Array.prototype.forEach.call(document.querySelectorAll('.avail-day'), function (c) {
            c.classList.toggle('is-selected', c.getAttribute('data-tanggal') === tgl);
          });
          updateHomestay();
        } else {
          const jadwalId = jadwalByDate[tgl];
          const sel = document.getElementById('jadwal_id');
          if (!jadwalId || !sel) return;
          const option = sel.querySelector('option[value="' + jadwalId + '"]');
          if (option && !option.disabled) {
            sel.value = String(jadwalId);
          }
          // tampilkan judul di atas select sebagai feedback
          updateWisata();
        }
      });
    });

    agreeEl?.addEventListener('change', syncSubmit);

    function applyPrefillAfterDatepicker() {
      const params = new URLSearchParams(window.location.search);
      const qty = params.get('qty');
      const jumEl = document.getElementById('jumlah_tamu');
      if (qty && jumEl) {
        const n = Math.max(1, parseInt(qty, 10) || 1);
        jumEl.value = String(n);
      }

      if (isHomestay) {
        const checkIn = params.get('check_in');
        const checkOut = params.get('check_out');
        const cin = document.getElementById('check_in');
        const cout = document.getElementById('check_out');
        if (checkIn && cin) {
          if (cin._flatpickr) cin._flatpickr.setDate(checkIn, true);
          else cin.value = checkIn;
        }
        if (checkOut && cout) {
          if (cout._flatpickr) cout._flatpickr.setDate(checkOut, true);
          else cout.value = checkOut;
        }
        updateHomestay();
      } else {
        updateWisata();
      }

      if (hasPrefill || params.has('qty') || params.has('check_in')) {
        const form = document.getElementById('form-reservasi');
        if (form) {
          form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }
    }

    if (isHomestay) {
      window.__onHomestayDatesChange = updateHomestay;
      document.getElementById('check_in')?.addEventListener('change', updateHomestay);
      document.getElementById('check_out')?.addEventListener('change', updateHomestay);
    } else {
      const jumEl = document.getElementById('jumlah_tamu');
      jumEl?.addEventListener('input', updateWisata);
      document.getElementById('jadwal_id')?.addEventListener('change', updateWisata);
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', applyPrefillAfterDatepicker);
    } else {
      applyPrefillAfterDatepicker();
    }

    syncSubmit();
  })();
</script>
<?= $this->endSection() ?>