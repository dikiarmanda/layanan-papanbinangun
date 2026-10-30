<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p><?= $produk ? 'Ubah data produk.' : 'Tambah produk UMKM atau catering.' ?></p>
  <a href="<?= site_url('admin/produk') ?>" class="btn btn-outline btn-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<div class="card card-form">
  <form method="post" enctype="multipart/form-data"
    action="<?= $produk ? site_url('admin/produk/' . $produk['id']) : site_url('admin/produk') ?>">
    <?= csrf_field() ?>
    <div class="form-group">
      <label>Nama Produk</label>
      <input name="nama" class="form-control" required value="<?= esc($produk['nama'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label>Jenis</label>
      <select name="jenis" class="form-control select2" required data-placeholder="Pilih jenis">
        <option value="umkm" <?= ($produk['jenis'] ?? 'umkm') === 'umkm' ? 'selected' : '' ?>>Produk UMKM (bisa dikirim ekspedisi)</option>
        <option value="catering" <?= ($produk['jenis'] ?? '') === 'catering' ? 'selected' : '' ?>>Catering (ambil / antar lokal + tanggal acara)</option>
      </select>
    </div>

    <div class="form-group">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control summernote-field"
        data-height="260" data-placeholder="Tulis deskripsi produk…" rows="5"
        required><?= esc($produk['deskripsi'] ?? '') ?></textarea>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:1rem">
      <div class="form-group">
        <label>Harga Pokok (Rp)</label>
        <input type="number" name="harga" class="form-control" required value="<?= esc($produk['harga'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Stok Keseluruhan</label>
        <input type="number" name="stok" class="form-control" required value="<?= esc($produk['stok'] ?? '0') ?>">
      </div>

      <div class="form-group">
        <label>Minimal Pembelian (Qty)</label>
        <input type="number" name="min_beli" class="form-control" min="1" required value="<?= esc($produk['min_beli'] ?? '1') ?>">
        <small class="text-muted">Misal 2 atau 3 pcs jika produk kecil/mainan ringan.</small>
      </div>

      <div class="form-group">
        <label>Berat Dasar (gram) <small>— untuk ongkir</small></label>
        <input type="number" name="berat" class="form-control" value="<?= esc($produk['berat'] ?? '1000') ?>">
      </div>
    </div>

    <!-- OPSI VARIAN UKURAN / MODEL -->
    <?php
    $varianList = !empty($produk['opsi_varian']) ? json_decode($produk['opsi_varian'], true) : [];
    $hasVarian = !empty($varianList);
    ?>
    <div class="form-group" style="background:var(--cream, #fbf9f4);padding:1.15rem;border-radius:8px;border:1px solid var(--admin-border, #e5dccb);margin:1.25rem 0">
      <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-weight:600;margin-bottom:0.25rem">
        <input type="checkbox" name="has_varian" id="has_varian" value="1" <?= $hasVarian ? 'checked' : '' ?>>
        <span>Punya Varian Ukuran / Model (Besar, Sedang, Kecil, dll.)</span>
      </label>
      <small class="text-muted" style="display:block;margin-bottom:0.75rem">
        Aktifkan jika produk seperti mainan/kerajinan ada pilihan ukuran berbeda beserta harga dan stoknya masing-masing.
      </small>

      <div id="varian_container" style="<?= $hasVarian ? '' : 'display:none' ?>">
        <div style="overflow-x:auto">
          <table style="width:100%;font-size:0.9rem;border-collapse:collapse">
            <thead>
              <tr style="text-align:left;border-bottom:1px solid #ddd">
                <th style="padding:0.5rem">Nama Ukuran / Varian</th>
                <th style="padding:0.5rem;width:150px">Harga (Rp)</th>
                <th style="padding:0.5rem;width:110px">Stok</th>
                <th style="padding:0.5rem;width:110px">Berat (gr)</th>
                <th style="padding:0.5rem;width:50px"></th>
              </tr>
            </thead>
            <tbody id="varian_table_body">
              <?php if (!empty($varianList)): ?>
                <?php foreach ($varianList as $vl): ?>
                  <tr>
                    <td style="padding:0.35rem"><input type="text" name="varian_nama[]" class="form-control" value="<?= esc($vl['nama']) ?>" placeholder="Contoh: Besar" required></td>
                    <td style="padding:0.35rem"><input type="number" name="varian_harga[]" class="form-control" value="<?= esc($vl['harga']) ?>" placeholder="Harga"></td>
                    <td style="padding:0.35rem"><input type="number" name="varian_stok[]" class="form-control" value="<?= esc($vl['stok']) ?>" placeholder="Stok"></td>
                    <td style="padding:0.35rem"><input type="number" name="varian_berat[]" class="form-control" value="<?= esc($vl['berat'] ?? '0') ?>" placeholder="Berat"></td>
                    <td style="padding:0.35rem;text-align:center"><button type="button" class="btn btn-sm btn-danger btn-remove-varian"><i class="fa-solid fa-times"></i></button></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td style="padding:0.35rem"><input type="text" name="varian_nama[]" class="form-control" placeholder="Contoh: Kecil"></td>
                  <td style="padding:0.35rem"><input type="number" name="varian_harga[]" class="form-control" placeholder="Harga"></td>
                  <td style="padding:0.35rem"><input type="number" name="varian_stok[]" class="form-control" placeholder="Stok"></td>
                  <td style="padding:0.35rem"><input type="number" name="varian_berat[]" class="form-control" placeholder="Berat"></td>
                  <td style="padding:0.35rem;text-align:center"><button type="button" class="btn btn-sm btn-danger btn-remove-varian"><i class="fa-solid fa-times"></i></button></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <button type="button" id="btn-add-varian" class="btn btn-sm btn-outline" style="margin-top:0.75rem">
          <i class="fa-solid fa-plus"></i> Tambah Ukuran / Varian
        </button>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
      <div class="form-group">
        <label>Kategori</label>
        <select name="kategori_id" class="form-control select2" data-placeholder="Pilih kategori" data-allow-clear="1">
          <option value="">—</option>
          <?php foreach ($kategori as $k): ?>
            <option value="<?= (int) $k['id'] ?>" <?= (string) ($produk['kategori_id'] ?? '') === (string) $k['id'] ? 'selected' : '' ?>><?= esc($k['nama']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control select2" data-placeholder="Status">
          <option value="draft" <?= ($produk['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
          <option value="publish" <?= ($produk['status'] ?? '') === 'publish' ? 'selected' : '' ?>>Publish</option>
        </select>
      </div>
    </div>

    <!-- FOTO UTAMA -->
    <div class="form-group">
      <label>Foto Utama Produk</label>
      <div class="image-cropper-wrap">
        <input type="file" accept="image/*" class="image-cropper-input"
          data-default="<?= ! empty($produk['gambar']) ? esc(media_url($produk['gambar']), 'attr') : '' ?>">
        <input type="hidden" name="gambar_cropped">
        <div class="image-cropper-preview">
          <img src="" alt="">
          <button type="button" class="image-cropper-remove" aria-label="Hapus">&times;</button>
        </div>
      </div>
    </div>

    <!-- FOTO TAMBAHAN (1-5 FOTO TOTAL) -->
    <?php
    $fotoTambahan = !empty($produk['foto_tambahan']) ? json_decode($produk['foto_tambahan'], true) : [];
    ?>
    <div class="form-group" style="background:#fafafa;padding:1rem;border-radius:8px;border:1px dashed #ccc">
      <label style="font-weight:600">Foto Tambahan (Maksimal 4 Foto Tambahan, Total 1–5 Foto)</label>
      <small class="text-muted" style="display:block;margin-bottom:0.75rem">
        Bisa upload 1 sampai 4 foto tambahan dari sudut berbeda (tampak depan, samping, detail ukuran).
      </small>

      <?php if (!empty($fotoTambahan)): ?>
        <p style="font-size:0.85rem;font-weight:600;margin-bottom:0.4rem">Foto Tambahan Tersimpan:</p>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:1rem">
          <?php foreach ($fotoTambahan as $ft): ?>
            <div style="border:1px solid #ddd;padding:6px;border-radius:6px;text-align:center;background:#fff">
              <img src="<?= media_url($ft) ?>" alt="" style="width:85px;height:85px;object-fit:cover;border-radius:4px;display:block">
              <label style="font-size:0.75rem;margin-top:4px;display:block;cursor:pointer;color:#c82333">
                <input type="checkbox" name="hapus_foto_tambahan[]" value="<?= esc($ft) ?>"> Hapus
              </label>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <label style="font-size:0.85rem;font-weight:600">Upload Foto Tambahan Baru:</label>
      <input type="file" name="foto_tambahan[]" class="form-control" multiple accept="image/*">
    </div>

    <div class="actions" style="margin-top:1.5rem">
      <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan Produk</button>
      <a href="<?= site_url('admin/produk') ?>" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var chkVarian = document.getElementById('has_varian');
  var container = document.getElementById('varian_container');
  var btnAdd = document.getElementById('btn-add-varian');
  var tbody = document.getElementById('varian_table_body');

  if (chkVarian) {
    chkVarian.addEventListener('change', function() {
      container.style.display = this.checked ? '' : 'none';
      var inputs = container.querySelectorAll('input');
      inputs.forEach(function(inp) {
        if (chkVarian.checked) {
          if (inp.name === 'varian_nama[]') inp.required = true;
        } else {
          inp.required = false;
        }
      });
    });
  }

  if (btnAdd && tbody) {
    btnAdd.addEventListener('click', function() {
      var tr = document.createElement('tr');
      tr.innerHTML = '<td style="padding:0.35rem"><input type="text" name="varian_nama[]" class="form-control" placeholder="Contoh: Sedang" required></td>' +
        '<td style="padding:0.35rem"><input type="number" name="varian_harga[]" class="form-control" placeholder="Harga"></td>' +
        '<td style="padding:0.35rem"><input type="number" name="varian_stok[]" class="form-control" placeholder="Stok"></td>' +
        '<td style="padding:0.35rem"><input type="number" name="varian_berat[]" class="form-control" placeholder="Berat"></td>' +
        '<td style="padding:0.35rem;text-align:center"><button type="button" class="btn btn-sm btn-danger btn-remove-varian"><i class="fa-solid fa-times"></i></button></td>';
      tbody.appendChild(tr);
    });

    tbody.addEventListener('click', function(e) {
      if (e.target.closest('.btn-remove-varian')) {
        var rows = tbody.querySelectorAll('tr');
        if (rows.length > 1) {
          e.target.closest('tr').remove();
        } else {
          alert('Minimal 1 baris varian jika varian diaktifkan.');
        }
      }
    });
  }
});
</script>

<?= $this->endSection() ?>
