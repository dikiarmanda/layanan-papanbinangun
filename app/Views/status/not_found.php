<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container" style="max-width:560px;margin:0 auto;text-align:center">
  <div class="status-card" style="text-align:center">
    <span class="akun-back" style="margin-bottom:1rem"><a href="<?= site_url('/') ?>">← Kembali ke Beranda</a></span>
    <h1 style="margin-bottom:0.25rem">Transaksi Tidak Ditemukan</h1>
    <p style="color:var(--sepia);margin-bottom:1.25rem">
      Kode <code><?= esc($kode) ?></code> tidak ditemukan di sistem kami. Kemungkinan kode salah, atau transaksi
      tersebut belum pernah dibuat.
    </p>
    <div class="alert alert-info" style="text-align:left">
      <strong>Tips:</strong>
      <ul style="margin:0.5rem 0 0;padding-left:1.15rem;color:inherit">
        <li>Salin kode langsung dari email/WhatsApp konfirmasi agar tidak salah ketik.</li>
        <li>Kode reservasi diawali <code>RSV-</code>, pesanan toko diawali <code>ORD-</code>.</li>
        <li>Jika sudah membayar namun status belum muncul, hubungi pengelola desa.</li>
      </ul>
    </div>
    <div class="home-estimate-result" style="justify-content:center;padding-top:1.25rem;border-top:1px solid var(--cream-dark)">
      <a href="<?= site_url('/') ?>#cek-status" class="btn btn-primary">Coba Cek Ulang</a>
      <a href="<?= site_url('paket-wisata') ?>" class="btn btn-outline">Jelajahi Paket</a>
    </div>
  </div>
</section>
<?= $this->endSection() ?>