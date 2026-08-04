<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container auth-section">
  <div class="auth-card">
    <h1 class="section-title" style="margin-bottom:0.35rem">Daftar Akun</h1>
    <p class="auth-lead">Buat akun untuk menyimpan riwayat transaksi Anda.</p>

    <form method="post" action="<?= site_url('daftar') ?>">
      <?= csrf_field() ?>
      <div class="form-group">
        <label for="nama">Nama lengkap</label>
        <input type="text" id="nama" name="nama" class="form-control" required minlength="3"
          value="<?= esc(old('nama')) ?>">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" required value="<?= esc(old('email')) ?>">
      </div>
      <div class="form-group">
        <label for="no_hp">No. HP / WhatsApp</label>
        <input type="text" id="no_hp" name="no_hp" class="form-control" required minlength="10"
          value="<?= esc(old('no_hp')) ?>" placeholder="08xxxxxxxxxx">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" required minlength="6"
          placeholder="Minimal 6 karakter">
      </div>
      <div class="form-group">
        <label for="password_confirm">Ulangi password</label>
        <input type="password" id="password_confirm" name="password_confirm" class="form-control" required
          minlength="6">
      </div>
      <button class="btn btn-primary" type="submit" style="width:100%">Daftar</button>
    </form>

    <p class="auth-switch">Sudah punya akun? <a href="<?= site_url('masuk') ?>">Masuk</a></p>
  </div>
</section>
<?= $this->endSection() ?>