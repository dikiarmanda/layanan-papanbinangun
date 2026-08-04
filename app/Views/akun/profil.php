<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php $p = $pelanggan ?? []; ?>
<section class="section container">
  <div class="akun-layout">
    <?= view('akun/_sidebar', ['pelanggan' => $p, 'aktifMenu' => $aktifMenu ?? 'profil']) ?>
    <div class="akun-main">
      <h1 class="section-title" style="text-align:left;margin-bottom:1rem">Profil</h1>
      <div class="akun-panel" style="max-width:32rem">
        <form method="post" action="<?= site_url('akun/profil') ?>">
          <?= csrf_field() ?>
          <div class="form-group">
            <label for="nama">Nama lengkap</label>
            <input type="text" id="nama" name="nama" class="form-control" required
              value="<?= esc(old('nama', $p['nama'] ?? '')) ?>">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required
              value="<?= esc(old('email', $p['email'] ?? '')) ?>">
          </div>
          <div class="form-group">
            <label for="no_hp">No. HP / WhatsApp</label>
            <input type="text" id="no_hp" name="no_hp" class="form-control" required
              value="<?= esc(old('no_hp', $p['no_hp'] ?? '')) ?>">
          </div>
          <div class="form-group">
            <label for="password">Password baru (opsional)</label>
            <input type="password" id="password" name="password" class="form-control" minlength="6"
              placeholder="Kosongkan jika tidak diganti">
          </div>
          <div class="form-group">
            <label for="password_confirm">Ulangi password baru</label>
            <input type="password" id="password_confirm" name="password_confirm" class="form-control" minlength="6">
          </div>
          <button class="btn btn-primary" type="submit">Simpan profil</button>
        </form>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>