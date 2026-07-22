<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$brand = $site['nama_desa'] ?: 'Wisata Binangun';
$updated = date('d F Y');
?>
<section class="section container legal-page">
  <header class="legal-header">
    <p class="legal-kicker">Dokumen hukum</p>
    <h1>Kebijakan Privasi</h1>
    <p class="legal-meta">Terakhir diperbarui: <?= esc($updated) ?> · Berlaku untuk layanan <?= esc($brand) ?></p>
  </header>

  <article class="legal-content">
    <p>
      Kebijakan Privasi ini menjelaskan bagaimana <?= esc($brand) ?> (“kami”) mengumpulkan, menggunakan,
      menyimpan, dan melindungi data pribadi Anda saat menggunakan situs layanan reservasi, toko UMKM, dan catering.
    </p>

    <h2>1. Data yang kami kumpulkan</h2>
    <p>Data yang Anda berikan secara langsung saat bertransaksi, antara lain:</p>
    <ul>
      <li>Nama lengkap</li>
      <li>Alamat email</li>
      <li>Nomor telepon / WhatsApp</li>
      <li>Alamat pengiriman atau lokasi acara (untuk order produk/catering)</li>
      <li>Detail reservasi (tanggal, jumlah tamu, check-in/out, catatan)</li>
      <li>Data transaksi pembayaran yang diproses melalui mitra payment gateway</li>
    </ul>
    <p>
      Kami juga dapat menerima data teknis terbatas seperti alamat IP, jenis perangkat, dan log akses
      yang diperlukan untuk keamanan serta perbaikan layanan.
    </p>

    <h2>2. Tujuan penggunaan data</h2>
    <ul>
      <li>Memproses reservasi, pesanan, dan pembayaran</li>
      <li>Mengirim konfirmasi, status transaksi, dan informasi terkait layanan</li>
      <li>Menghubungi Anda terkait kuota, pengiriman, atau perubahan jadwal</li>
      <li>Mencegah penipuan serta menjaga keamanan sistem</li>
      <li>Memenuhi kewajiban hukum yang berlaku</li>
    </ul>

    <h2>3. Pembayaran dan pihak ketiga</h2>
    <p>
      Pembayaran diproses melalui Midtrans. Data kartu atau metode bayar dikelola oleh Midtrans sesuai
      kebijakan mereka. Untuk pengiriman produk UMKM, kami dapat menggunakan layanan perhitungan ongkir
      (RajaOngkir) berdasarkan destinasi yang Anda pilih.
    </p>

    <h2>4. Penyimpanan dan keamanan</h2>
    <p>
      Data disimpan pada sistem yang kami kelola dengan akses terbatas untuk pengelola yang berwenang.
      Kami menerapkan langkah teknis yang wajar untuk melindungi data dari akses tidak sah.
      Namun, tidak ada transmisi data melalui internet yang sepenuhnya bebas risiko.
    </p>

    <h2>5. Retensi data</h2>
    <p>
      Data transaksi disimpan selama diperlukan untuk administrasi layanan, audit, dan kewajiban hukum.
      Apabila data tidak lagi dibutuhkan, kami dapat menghapus atau menganonimkannya.
    </p>

    <h2>6. Hak Anda</h2>
    <p>Anda dapat meminta akses, koreksi, atau penjelasan terkait data pribadi yang kami simpan dengan menghubungi kami
      melalui kontak resmi di situs ini.</p>

    <h2>7. Cookie dan teknologi serupa</h2>
    <p>
      Situs dapat menggunakan session browser untuk fitur penting seperti keranjang belanja dan login admin.
      Teknologi ini diperlukan agar layanan berfungsi dengan benar.
    </p>

    <h2>8. Perubahan kebijakan</h2>
    <p>
      Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Versi terbaru akan ditampilkan
      pada halaman ini beserta tanggal pembaruan.
    </p>

    <h2>9. Kontak</h2>
    <p>
      Untuk pertanyaan terkait privasi, hubungi pengelola <?= esc($brand) ?>
      <?php if (!empty($site['email_kontak'])): ?>
        melalui email <a href="mailto:<?= esc($site['email_kontak']) ?>"><?= esc($site['email_kontak']) ?></a>
      <?php endif; ?>
      <?php if (!empty($site['no_whatsapp'])): ?>
        <?= !empty($site['email_kontak']) ? 'atau' : 'melalui' ?>
        WhatsApp <?= esc($site['no_whatsapp']) ?>
      <?php endif; ?>.
    </p>
  </article>

  <p class="legal-nav">
    <a href="<?= site_url('persyaratan') ?>">Baca Syarat &amp; Ketentuan →</a>
  </p>
</section>
<?= $this->endSection() ?>