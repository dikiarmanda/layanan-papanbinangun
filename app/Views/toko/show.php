<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$isCatering = ($produk['jenis'] ?? '') === 'catering';
$fotoTambahan = !empty($produk['foto_tambahan']) ? json_decode($produk['foto_tambahan'], true) : [];
$allPhotos = [];
if (!empty($produk['gambar'])) {
    $allPhotos[] = $produk['gambar'];
}
foreach ($fotoTambahan as $ft) {
    if (!empty($ft)) {
        $allPhotos[] = $ft;
    }
}
$varianList = !empty($produk['opsi_varian']) ? json_decode($produk['opsi_varian'], true) : [];
$minBeli = max(1, (int) ($produk['min_beli'] ?? 1));
$hargaAwal = harga_produk_akhir($produk['harga']);
if (!empty($varianList) && !empty($varianList[0]['harga']) && (float) $varianList[0]['harga'] > 0) {
    $hargaAwal = (float) $varianList[0]['harga'];
}
$stokAwal = (int) $produk['stok'];
if (!empty($varianList) && isset($varianList[0]['stok'])) {
    $stokAwal = (int) $varianList[0]['stok'];
}
?>

<section class="section container">
  <div class="booking-layout">
    <div>
      <?php if (!empty($allPhotos)): ?>
        <div class="product-gallery">
          <img id="main-product-img" src="<?= media_url($allPhotos[0]) ?>" alt="<?= esc($produk['nama']) ?>"
               style="width:100%;max-height:420px;object-fit:cover;border-radius:8px;border:1px solid var(--cream-dark)">
          <?php if (count($allPhotos) > 1): ?>
            <div class="gallery-thumbs" style="display:flex;gap:0.6rem;margin-top:0.75rem;overflow-x:auto;padding-bottom:0.25rem">
              <?php foreach ($allPhotos as $idx => $photo): ?>
                <img src="<?= media_url($photo) ?>" alt=""
                     class="thumb-item <?= $idx === 0 ? 'active' : '' ?>"
                     onclick="switchProductImg(this, '<?= media_url($photo) ?>')"
                     style="width:70px;height:70px;object-fit:cover;border-radius:6px;cursor:pointer;border:2px solid <?= $idx === 0 ? 'var(--gold, #c5a059)' : 'var(--cream-dark, #e8dfd0)' ?>;transition:border-color .2s">
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <span class="badge-jenis <?= $isCatering ? 'badge-catering' : 'badge-umkm' ?>" style="margin-top:1.25rem">
        <?= $isCatering ? 'Catering' : 'Produk UMKM' ?>
      </span>
      <h1 style="margin-top:0.5rem"><?= esc($produk['nama']) ?></h1>
      <div class="price" id="display-price" style="margin-bottom:0.75rem"><?= format_rupiah($hargaAwal) ?></div>
      <p style="color:var(--sepia)">Stok: <span id="display-stok"><?= $stokAwal ?></span></p>

      <?php if ($minBeli > 1): ?>
        <div class="alert alert-info" style="margin:0.75rem 0;padding:0.6rem 0.85rem">
          <i class="fa-solid fa-circle-info"></i> Minimal pembelian produk ini adalah <strong><?= $minBeli ?> pcs</strong>.
        </div>
      <?php endif; ?>

      <?php if ($isCatering): ?>
        <div class="alert alert-info" style="margin-top:1rem">
          Catering hanya tersedia untuk ambil di tempat atau antar lokal. Wajib mengisi tanggal &amp; waktu acara saat
          checkout.
        </div>
      <?php endif; ?>

      <div class="deskripsi-editor" style="margin:1rem 0"><?= sanitize_html($produk['deskripsi']) ?></div>
    </div>

    <div class="booking-card">
      <h2>Tambah ke keranjang</h2>
      <p class="hint">Keranjang tidak boleh mencampur UMKM dan catering.</p>

      <form method="post" action="<?= site_url('keranjang/add') ?>" id="form-add-cart">
        <?= csrf_field() ?>
        <input type="hidden" name="produk_id" value="<?= (int) $produk['id'] ?>">

        <?php if (!empty($varianList)): ?>
          <div class="form-group" style="margin-bottom:1.15rem">
            <label style="font-weight:600;margin-bottom:0.4rem;display:block">Pilih Ukuran / Model:</label>
            <div style="display:flex;flex-direction:column;gap:0.4rem">
              <?php foreach ($varianList as $idx => $vl): ?>
                <?php $vPrice = (float) (!empty($vl['harga']) && $vl['harga'] > 0 ? $vl['harga'] : $produk['harga']); ?>
                <label class="varian-item" style="display:flex;justify-content:space-between;align-items:center;padding:0.55rem 0.75rem;border:1px solid var(--cream-dark);border-radius:6px;cursor:pointer;background:<?= $idx === 0 ? 'var(--cream, #faf6ee)' : '#fff' ?>">
                  <span style="display:flex;align-items:center;gap:0.5rem">
                    <input type="radio" name="varian" value="<?= esc($vl['nama']) ?>"
                           data-harga="<?= $vPrice ?>"
                           data-harga-fmt="<?= format_rupiah($vPrice) ?>"
                           data-stok="<?= (int) $vl['stok'] ?>"
                           <?= $idx === 0 ? 'checked' : '' ?> required>
                    <strong><?= esc($vl['nama']) ?></strong>
                  </span>
                  <span style="font-size:0.9rem;font-weight:600;color:var(--terracotta)">
                    <?= format_rupiah($vPrice) ?>
                  </span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <div class="form-group">
          <label>Jumlah</label>
          <input type="number" name="jumlah" id="input-jumlah" class="form-control"
                 min="<?= $minBeli ?>" max="<?= $stokAwal ?>" value="<?= $minBeli ?>" required>
          <?php if ($minBeli > 1): ?>
            <small style="color:var(--sepia)">Minimal <?= $minBeli ?> pcs</small>
          <?php endif; ?>
        </div>

        <div id="btn-wrap">
          <?php if ($stokAwal > 0): ?>
            <button type="submit" class="btn btn-primary" id="btn-submit-cart" style="width:100%">
              <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
            </button>
          <?php else: ?>
            <div class="alert alert-error" style="text-align:center">Stok habis</div>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
function switchProductImg(el, src) {
  var main = document.getElementById('main-product-img');
  if (main) main.src = src;
  document.querySelectorAll('.thumb-item').forEach(function(img) {
    img.style.borderColor = 'var(--cream-dark, #e8dfd0)';
  });
  el.style.borderColor = 'var(--gold, #c5a059)';
}

document.addEventListener('DOMContentLoaded', function() {
  var radioVarians = document.querySelectorAll('input[name="varian"]');
  var displayPrice = document.getElementById('display-price');
  var displayStok = document.getElementById('display-stok');
  var inputJumlah = document.getElementById('input-jumlah');
  var btnWrap = document.getElementById('btn-wrap');

  radioVarians.forEach(function(radio) {
    radio.addEventListener('change', function() {
      document.querySelectorAll('.varian-item').forEach(function(item) {
        item.style.background = '#fff';
      });
      this.closest('.varian-item').style.background = 'var(--cream, #faf6ee)';

      var hargaFmt = this.getAttribute('data-harga-fmt');
      var stok = parseInt(this.getAttribute('data-stok')) || 0;

      if (displayPrice && hargaFmt) displayPrice.textContent = hargaFmt;
      if (displayStok) displayStok.textContent = stok;

      if (inputJumlah) {
        inputJumlah.max = stok;
        if (parseInt(inputJumlah.value) > stok) {
          inputJumlah.value = Math.max(parseInt(inputJumlah.min) || 1, stok);
        }
      }

      if (btnWrap) {
        if (stok <= 0) {
          btnWrap.innerHTML = '<div class="alert alert-error" style="text-align:center">Stok varian ini habis</div>';
        } else {
          btnWrap.innerHTML = '<button type="submit" class="btn btn-primary" style="width:100%"><i class="fas fa-cart-plus"></i> Tambah ke Keranjang</button>';
        }
      }
    });
  });
});
</script>

<?= $this->endSection() ?>
