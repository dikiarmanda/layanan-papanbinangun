<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin — <?= esc(pengaturan()['nama_desa'] ?? 'Wisata Binangun') ?></title>
  <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/favicon.ico') ?>">
  <link rel="apple-touch-icon" href="<?= base_url('assets/images/apple-touch-icon.png') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/fonts.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body class="login-body">
  <main class="login-card">
    <div class="login-brand">
      <img src="<?= brand_logo_url() ?>" alt="" class="login-brand-logo">
      <h1>Login Admin</h1>
      <p class="login-sub">Layanan <?= esc(pengaturan()['nama_desa'] ?? 'Wisata Binangun') ?></p>
    </div>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('admin/login') ?>">
      <?= csrf_field() ?>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" required autofocus
               placeholder="admin@example.com" value="<?= esc(old('email')) ?>">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" required
               placeholder="••••••••">
      </div>
      <button class="btn btn-primary btn-block" type="submit">Masuk</button>
    </form>
    <a class="login-back" href="<?= site_url('/') ?>">← Kembali ke layanan</a>
  </main>
</body>
</html>
