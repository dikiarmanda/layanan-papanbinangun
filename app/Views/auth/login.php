<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container auth-section">
  <div class="auth-card">
    <h1 class="section-title" style="margin-bottom:0.35rem">Masuk</h1>
    <p class="auth-lead">Kelola reservasi<?= fitur_produk_aktif() ? ' &amp; pesanan' : '' ?> Anda di satu tempat.</p>

    <form method="post" action="<?= site_url('masuk') ?>">
      <?= csrf_field() ?>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" required autofocus
          value="<?= esc(old('email')) ?>" placeholder="nama@email.com">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" required minlength="6"
          placeholder="••••••••">
      </div>
      <button class="btn btn-primary" type="submit" style="width:100%">Masuk</button>
    </form>

    <p class="auth-switch">Belum punya akun? <a href="<?= site_url('daftar') ?>">Daftar</a></p>
  </div>
</section>
<?= $this->endSection() ?>