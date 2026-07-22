<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php $isCatering = ($cartJenis ?? '') === 'catering'; ?>
<section class="section container">
  <h1><?= $isCatering ? 'Checkout Catering' : 'Checkout Produk UMKM' ?></h1>

  <div class="checkout-layout" style="margin-top:1.25rem">
    <div>
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

        <?php if ($isCatering): ?>
          <div class="form-group">
            <label>Tanggal acara / pengiriman</label>
            <input type="text" name="tanggal_acara" class="form-control datepicker" required
              placeholder="Pilih tanggal acara" data-min="<?= date('Y-m-d') ?>" value="<?= esc(old('tanggal_acara')) ?>"
              autocomplete="off">
          </div>
          <div class="form-group">
            <label>Waktu acara</label>
            <input type="time" name="waktu_acara" class="form-control" required
              value="<?= esc(old('waktu_acara') ?: '10:00') ?>">
          </div>

          <div class="form-group">
            <label>Metode pengambilan</label>
            <div class="method-options">
              <label class="method-option">
                <input type="radio" name="metode_pengiriman" value="ambil_di_tempat" <?= old('metode_pengiriman', 'ambil_di_tempat') === 'ambil_di_tempat' ? 'checked' : '' ?>>
                <span>
                  <strong>Ambil di tempat</strong>
                  <small>Tanpa ongkir. Ambil langsung di lokasi desa.</small>
                </span>
              </label>
              <label class="method-option">
                <input type="radio" name="metode_pengiriman" value="antar_lokal"
                  <?= old('metode_pengiriman') === 'antar_lokal' ? 'checked' : '' ?>>
                <span>
                  <strong>Antar lokal</strong>
                  <small>Hanya area sekitar desa. Ongkir sesuai zona (tanpa ekspedisi).</small>
                </span>
              </label>
            </div>
          </div>

          <div id="zona-wrap" class="form-group" style="display:none">
            <label>Pilih zona antar</label>
            <select name="zona_antar_id" id="zona_antar_id" class="form-control">
              <option value="">— Pilih zona —</option>
              <?php foreach ($zona as $z): ?>
                <option value="<?= (int) $z['id'] ?>" data-ongkir="<?= (float) $z['ongkir'] ?>"
                  data-estimasi="<?= esc($z['estimasi']) ?>" <?= (string) old('zona_antar_id') === (string) $z['id'] ? 'selected' : '' ?>>
                  <?= esc($z['nama']) ?> — <?= format_rupiah($z['ongkir']) ?> (<?= esc($z['estimasi']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
            <?php if (empty($zona)): ?>
              <small style="color:#991b1b">Belum ada zona antar aktif. Hubungi pengelola atau pilih ambil di tempat.</small>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label id="alamat-label">Alamat / lokasi acara</label>
            <textarea name="alamat_kirim" class="form-control" rows="3"
              required><?= esc(old('alamat_kirim')) ?></textarea>
          </div>
        <?php else: ?>
          <div class="form-group">
            <label>Alamat lengkap</label>
            <textarea name="alamat_kirim" class="form-control" rows="3"
              required><?= esc(old('alamat_kirim')) ?></textarea>
          </div>

          <?php if ($ongkirReady): ?>
            <div class="form-group">
              <label>Cari kecamatan / kelurahan tujuan</label>
              <input type="text" id="dest-search" class="form-control" placeholder="Contoh: Surabaya, Menteng, Pandaan…"
                autocomplete="off">
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
        <?php endif; ?>

        <button type="submit" class="btn btn-primary" style="width:100%">Bayar via Midtrans</button>
      </form>
    </div>

    <aside class="checkout-summary">
      <h2 style="font-size:1.25rem;margin-top:0">Ringkasan</h2>
      <ul style="padding-left:1.1rem;margin:0 0 1rem">
        <?php foreach ($items as $item): ?>
          <li style="margin-bottom:0.35rem"><?= esc($item['produk']['nama']) ?> × <?= (int) $item['jumlah'] ?> —
            <?= format_rupiah($item['subtotal']) ?>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="estimasi-box" style="margin-top:0;border-top:none;padding-top:0">
        <div class="estimasi-row"><span>Subtotal</span><span><?= format_rupiah($subtotal) ?></span></div>
        <div class="estimasi-row"><span>Ongkir</span><span id="ongkir-display">Rp 0</span></div>
        <div class="estimasi-row estimasi-total">
          <strong>Total</strong>
          <strong class="price" id="total-display" style="margin:0"><?= format_rupiah($subtotal) ?></strong>
        </div>
      </div>
    </aside>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  (() => {
    const subtotal = <?= (float) $subtotal ?>;
    const isCatering = <?= $isCatering ? 'true' : 'false' ?>;
    const ongkirReady = <?= !empty($ongkirReady) ? 'true' : 'false' ?>;

    function formatRp(n) {
      return 'Rp ' + Math.round(Number(n)).toLocaleString('id-ID');
    }

    function setTotal(ongkir) {
      document.getElementById('ongkir-display').textContent = formatRp(ongkir);
      document.getElementById('total-display').textContent = formatRp(subtotal + Number(ongkir));
    }

    if (isCatering) {
      const zonaWrap = document.getElementById('zona-wrap');
      const zonaEl = document.getElementById('zona_antar_id');
      const radios = document.querySelectorAll('input[name="metode_pengiriman"]');

      function syncMetode() {
        const metode = document.querySelector('input[name="metode_pengiriman"]:checked')?.value;
        const antar = metode === 'antar_lokal';
        if (zonaWrap) zonaWrap.style.display = antar ? 'block' : 'none';
        if (zonaEl) zonaEl.required = antar;
        if (!antar) {
          setTotal(0);
          return;
        }
        const opt = zonaEl?.options[zonaEl.selectedIndex];
        setTotal(opt?.dataset?.ongkir || 0);
      }

      radios.forEach(r => r.addEventListener('change', syncMetode));
      zonaEl?.addEventListener('change', syncMetode);
      syncMetode();
      return;
    }

    if (!ongkirReady) {
      setTotal(0);
      return;
    }

    const weight = <?= (int) $weight ?>;
    const searchEl = document.getElementById('dest-search');
    const destEl = document.getElementById('destination');
    const ongkirEl = document.getElementById('ongkir');
    let searchTimer = null;

    searchEl?.addEventListener('input', () => {
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

    destEl?.addEventListener('change', async () => {
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

    ongkirEl?.addEventListener('change', () => {
      if (!ongkirEl.value) return;
      const d = JSON.parse(ongkirEl.value);
      document.getElementById('kurir').value = d.kurir;
      document.getElementById('layanan_kurir').value = d.layanan;
      document.getElementById('estimasi_ongkir').value = d.etd;
      document.getElementById('ongkos_kirim').value = d.value;
      setTotal(d.value);
    });
  })();
</script>
<?= $this->endSection() ?>