<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Lora&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body style="display:flex;align-items:center;justify-content:center;min-height:100vh">
  <div style="width:min(400px,92%);background:var(--white);padding:2rem;border-radius:6px;border:1px solid var(--cream-dark)">
    <h1 style="text-align:center;font-size:1.8rem">Admin Layanan</h1>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('admin/login') ?>">
      <?= csrf_field() ?>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required value="<?= esc(old('email')) ?>">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-primary" type="submit" style="width:100%">Masuk</button>
    </form>
  </div>
</body>
</html>
