<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container" style="max-width:720px;margin:0 auto">
  <h1>Checkout Produk</h1>

  <h3>Ringkasan</h3>
  <ul>
    <?php foreach ($items as $item): ?>
      <li><?= esc($item['produk']['nama']) ?> × <?= (int) $item['jumlah'] ?> — <?= format_rupiah($item['subtotal']) ?></li>
    <?php endforeach; ?>
  </ul>
  <p class="price">Subtotal: <?= format_rupiah($subtotal) ?></p>

  <?php if (! $ongkirReady): ?>
    <div class="alert alert-info">
      RajaOngkir belum siap. Pastikan <code>rajaongkir.shippingKey</code>, <code>rajaongkir.url</code>, dan <code>rajaongkir.origin</code> terisi di .env
      (lihat <a href="https://rajaongkir.com/docs" target="_blank" rel="noopener">dokumentasi RajaOngkir</a>).
    </div>
  <?php endif; ?>

  <form method="post" action="<?= site_url('checkout-produk') ?>" id="checkout-form">
    <?= csrf_field() ?>
    <div class="form-group">
      <label>Nama</label>
      <input type="text" name="nama" class="form-control" required value="<?= esc(old('nama')) ?>">
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required value="<?= esc(old('email')) ?>">
    </div>
    <div class="form-group">
      <label>No. HP</label>
      <input type="text" name="no_hp" class="form-control" required value="<?= esc(old('no_hp')) ?>">
    </div>
    <div class="form-group">
      <label>Alamat lengkap</label>
      <textarea name="alamat_kirim" class="form-control" rows="3" required><?= esc(old('alamat_kirim')) ?></textarea>
    </div>

    <?php if ($ongkirReady): ?>
      <div class="form-group">
        <label>Cari kecamatan / kelurahan tujuan</label>
        <input type="text" id="dest-search" class="form-control" placeholder="Contoh: Surabaya, Menteng, Pandaan…" autocomplete="off">
        <select id="destination" name="kota_tujuan_id" class="form-control" required style="margin-top:0.5rem">
          <option value="">— Ketik pencarian di atas, lalu pilih —</option>
        </select>
        <input type="hidden" name="kota_tujuan_nama" id="kota_tujuan_nama">
      </div>

      <div class="form-group">
        <label>Pilihan Ongkir</label>
        <select id="ongkir" class="form-control" required>
          <option value="">— Pilih destinasi dulu —</option>
        </select>
        <input type="hidden" name="kurir" id="kurir">
        <input type="hidden" name="layanan_kurir" id="layanan_kurir">
        <input type="hidden" name="estimasi_ongkir" id="estimasi_ongkir">
        <input type="hidden" name="ongkos_kirim" id="ongkos_kirim" value="0">
      </div>
    <?php else: ?>
      <input type="hidden" name="kota_tujuan_id" value="1">
      <input type="hidden" name="kota_tujuan_nama" value="Manual">
      <input type="hidden" name="kurir" value="manual">
      <input type="hidden" name="layanan_kurir" value="Ambil di tempat / menyesuaikan">
      <input type="hidden" name="estimasi_ongkir" value="-">
      <input type="hidden" name="ongkos_kirim" value="0">
      <div class="alert alert-info">Mode tanpa RajaOngkir: ongkir Rp 0.</div>
    <?php endif; ?>

    <p>Total (estimasi): <strong id="total-display"><?= format_rupiah($subtotal) ?></strong></p>
    <button type="submit" class="btn btn-primary" style="width:100%">Bayar via Midtrans</button>
  </form>
</section>

<?php if ($ongkirReady): ?>
<script>
const weight = <?= (int) $weight ?>;
const subtotal = <?= (float) $subtotal ?>;
const searchEl = document.getElementById('dest-search');
const destEl = document.getElementById('destination');
const ongkirEl = document.getElementById('ongkir');
let searchTimer = null;

function formatRp(n) {
  return 'Rp ' + Number(n).toLocaleString('id-ID');
}

searchEl.addEventListener('input', () => {
  clearTimeout(searchTimer);
  const q = searchEl.value.trim();
  if (q.length < 2) return;
  searchTimer = setTimeout(async () => {
    destEl.innerHTML = '<option value="">Mencari…</option>';
    try {
      const res = await fetch('<?= site_url('api/destinations') ?>?q=' + encodeURIComponent(q));
      const list = await res.json();
      destEl.innerHTML = '<option value="">— Pilih destinasi —</option>';
      (list || []).forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.label;
        opt.dataset.nama = d.label;
        destEl.appendChild(opt);
      });
      if (!list || !list.length) {
        destEl.innerHTML = '<option value="">Tidak ditemukan — coba kata lain</option>';
      }
    } catch (e) {
      destEl.innerHTML = '<option value="">Gagal memuat destinasi</option>';
    }
  }, 400);
});

destEl.addEventListener('change', async () => {
  const selected = destEl.options[destEl.selectedIndex];
  document.getElementById('kota_tujuan_nama').value = selected.dataset.nama || selected.textContent;
  if (!destEl.value) return;

  ongkirEl.innerHTML = '<option value="">Menghitung ongkir…</option>';
  try {
    const res = await fetch('<?= site_url('api/ongkir') ?>?destination=' + destEl.value + '&weight=' + weight);
    const list = await res.json();
    ongkirEl.innerHTML = '<option value="">— Pilih layanan —</option>';
    (list || []).forEach(row => {
      const opt = document.createElement('option');
      opt.value = JSON.stringify({
        kurir: row.code,
        layanan: row.service,
        etd: row.etd || '-',
        value: row.cost
      });
      const etd = row.etd ? (' (' + row.etd + ')') : '';
      opt.textContent = (row.code + ' ' + row.service + ' — ' + formatRp(row.cost) + etd).toUpperCase();
      ongkirEl.appendChild(opt);
    });
    if (!list || !list.length) {
      ongkirEl.innerHTML = '<option value="">Tidak ada layanan untuk rute ini</option>';
    }
  } catch (e) {
    ongkirEl.innerHTML = '<option value="">Gagal menghitung ongkir</option>';
  }
});

ongkirEl.addEventListener('change', () => {
  if (!ongkirEl.value) return;
  const d = JSON.parse(ongkirEl.value);
  document.getElementById('kurir').value = d.kurir;
  document.getElementById('layanan_kurir').value = d.layanan;
  document.getElementById('estimasi_ongkir').value = d.etd;
  document.getElementById('ongkos_kirim').value = d.value;
  document.getElementById('total-display').textContent = formatRp(subtotal + Number(d.value));
});
</script>
<?php endif; ?>
<?= $this->endSection() ?>
