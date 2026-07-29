<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p>Jadwal paket: <strong><?= esc($paket['nama']) ?></strong></p>
  <a href="<?= site_url('admin/paket-wisata') ?>" class="btn btn-outline btn-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<?php if (is_paket_menginap($paket['jenis'] ?? '')): ?>
  <div class="card" style="margin-bottom:1rem;padding:0.9rem 1.1rem;background:#fff8e8;border-color:#e8d9a8">
    <p class="text-muted" style="margin:0">Homestay &amp; camping memakai jadwal harian (biasanya kuota 1). Pastikan
      setiap malam yang bisa dipesan punya baris jadwal.</p>
  </div>
<?php endif; ?>

<div class="card card-form" style="margin-bottom:1.25rem">
  <div class="card-header" style="margin-top:0;padding-top:0">
    <h2><i class="fa-solid fa-plus"></i> Tambah jadwal</h2>
  </div>
  <form method="post" action="<?= site_url('admin/paket-wisata/' . $paket['id'] . '/jadwal') ?>"
    class="actions" style="align-items:end">
    <?= csrf_field() ?>
    <div class="form-group" style="margin:0;min-width:180px">
      <label>Tanggal</label>
      <input type="text" name="tanggal" class="form-control datepicker" required placeholder="Pilih tanggal"
        data-min="<?= date('Y-m-d') ?>" autocomplete="off">
    </div>
    <div class="form-group" style="margin:0;min-width:120px">
      <label>Kuota</label>
      <input type="number" name="kuota" class="form-control"
        value="<?= esc($paket['kuota_default'] ?? (is_paket_menginap($paket['jenis'] ?? '') ? 1 : 10)) ?>" min="1">
    </div>
    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-plus"></i> Tambah</button>
  </form>
</div>

<div class="card card-table">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Kuota</th>
          <th>Terpakai</th>
          <th>Sisa</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($jadwal)): ?>
          <tr>
            <td colspan="5" class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              Belum ada jadwal.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($jadwal as $j): ?>
            <tr>
              <td><?= esc($j['tanggal']) ?></td>
              <td><?= (int) $j['kuota'] ?></td>
              <td><?= (int) $j['kuota_terpakai'] ?></td>
              <td><?= (int) $j['kuota'] - (int) $j['kuota_terpakai'] ?></td>
              <td class="actions">
                <form method="post" action="<?= site_url('admin/jadwal/' . $j['id'] . '/delete') ?>"
                  class="inline-form js-swal-confirm" data-swal-title="Hapus jadwal?" data-swal-confirm="Hapus"
                  data-swal-icon="warning">
                  <?= csrf_field() ?>
                  <button class="btn btn-sm btn-danger" type="submit">
                    <i class="fa-solid fa-trash"></i> Hapus
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
