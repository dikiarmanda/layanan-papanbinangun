<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<p><a href="<?= site_url('admin/paket-wisata') ?>">&larr; Kembali</a></p>
<?php if (($paket['jenis'] ?? '') === 'homestay'): ?>
  <div class="alert alert-info">Homestay memakai jadwal harian (biasanya kuota 1). Pastikan setiap malam yang bisa dipesan
    punya baris jadwal.</div>
<?php endif; ?>
<form method="post" action="<?= site_url('admin/paket-wisata/' . $paket['id'] . '/jadwal') ?>"
  style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:1.5rem;align-items:end">
  <?= csrf_field() ?>
  <div class="form-group" style="margin:0">
    <label>Tanggal</label>
    <input type="text" name="tanggal" class="form-control datepicker" required placeholder="Pilih tanggal"
      data-min="<?= date('Y-m-d') ?>" autocomplete="off">
  </div>
  <div class="form-group" style="margin:0"><label>Kuota</label><input type="number" name="kuota" class="form-control"
      value="<?= esc($paket['kuota_default'] ?? (($paket['jenis'] ?? '') === 'homestay' ? 1 : 10)) ?>" min="1"></div>
  <button class="btn btn-primary" type="submit">Tambah Jadwal</button>
</form>
<table class="table">
  <thead>
    <tr>
      <th>Tanggal</th>
      <th>Kuota</th>
      <th>Terpakai</th>
      <th>Sisa</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($jadwal as $j): ?>
      <tr>
        <td><?= esc($j['tanggal']) ?></td>
        <td><?= (int) $j['kuota'] ?></td>
        <td><?= (int) $j['kuota_terpakai'] ?></td>
        <td><?= (int) $j['kuota'] - (int) $j['kuota_terpakai'] ?></td>
        <td>
          <form method="post" action="<?= site_url('admin/jadwal/' . $j['id'] . '/delete') ?>"
            onsubmit="return confirm('Hapus jadwal?')">
            <?= csrf_field() ?><button class="btn btn-sm btn-danger" type="submit">Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= $this->endSection() ?>