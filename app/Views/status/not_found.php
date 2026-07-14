<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container" style="max-width:480px;margin:0 auto;text-align:center">
  <h1>Transaksi Tidak Ditemukan</h1>
  <p style="color:var(--sepia)">Kode <code><?= esc($kode) ?></code> tidak ada di sistem. Periksa kembali kode RSV-… atau ORD-… Anda.</p>
  <a href="<?= site_url('/') ?>" class="btn btn-primary">Ke Beranda</a>
</section>
<?= $this->endSection() ?>
