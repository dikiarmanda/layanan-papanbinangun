<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php
$brand = $site['nama_desa'] ?: 'Wisata Binangun';
$updated = date('d F Y');
?>
<section class="section container legal-page">
  <header class="legal-header">
    <p class="legal-kicker">Dokumen hukum</p>
    <h1>Syarat &amp; Ketentuan</h1>
    <p class="legal-meta">Terakhir diperbarui: <?= esc($updated) ?> · Berlaku untuk layanan <?= esc($brand) ?></p>
  </header>

  <article class="legal-content">
    <p>
      Dengan menggunakan situs dan layanan <?= esc($brand) ?>, Anda menyetujui Syarat &amp; Ketentuan berikut.
      Jika Anda tidak setuju, mohon tidak menggunakan layanan ini.
    </p>

    <h2>1. Definisi layanan</h2>
    <p>Layanan mencakup:</p>
    <ul>
      <li><strong>Paket wisata</strong> — reservasi kegiatan wisata dengan harga per orang</li>
      <li><strong>Homestay</strong> — reservasi penginapan dengan harga per malam</li>
      <li><strong>Produk UMKM</strong> — pembelian barang dengan pengiriman ekspedisi</li>
      <li><strong>Catering</strong> — pemesanan katering dengan ambil di tempat atau antar lokal</li>
    </ul>

    <h2>2. Akun dan pemesanan</h2>
    <ul>
      <li>Pemesanan dapat dilakukan sebagai tamu tanpa membuat akun pelanggan.</li>
      <li>Anda wajib mengisi data yang benar, lengkap, dan dapat dihubungi.</li>
      <li>Reservasi/order dikonfirmasi setelah pembayaran berhasil (kecuali dinyatakan lain).</li>
    </ul>

    <h2>3. Harga dan pembayaran</h2>
    <ul>
      <li>Harga yang berlaku adalah harga yang ditampilkan saat checkout.</li>
      <li>Pembayaran diproses melalui Midtrans. Kegagalan/kedaluwarsa pembayaran dapat membatalkan pesanan dan
        melepaskan kuota/stok yang dikunci.</li>
      <li>Biaya ongkir UMKM mengikuti layanan ekspedisi yang dipilih. Ongkir catering antar lokal mengikuti zona yang
        tersedia.</li>
    </ul>

    <h2>4. Kuota, ketersediaan, dan stok</h2>
    <ul>
      <li>Ketersediaan jadwal wisata/homestay dan stok produk dapat berubah sewaktu-waktu.</li>
      <li>Sistem dapat mengunci kuota/stok sementara selama proses pembayaran.</li>
      <li>Homestay hanya dapat dipesan pada rentang tanggal yang tersedia di jadwal pengelola.</li>
    </ul>

    <h2>5. Kebijakan layanan khusus</h2>
    <h3>Wisata</h3>
    <ul>
      <li>Peserta diharapkan hadir sesuai jadwal. Keterlambatan signifikan dapat mengurangi durasi kegiatan tanpa
        pengembalian biaya.</li>
      <li>Kegiatan outdoor dapat menyesuaikan kondisi cuaca untuk keselamatan peserta.</li>
    </ul>
    <h3>Homestay</h3>
    <ul>
      <li>Check-in umumnya mulai pukul 14:00; check-out paling lambat pukul 12:00, kecuali diatur lain oleh pengelola.
      </li>
      <li>Hewan peliharaan tidak diperbolehkan. Area dalam ruangan bebas asap rokok.</li>
    </ul>
    <h3>Catering</h3>
    <ul>
      <li>Wajib mengisi tanggal dan waktu acara.</li>
      <li>Pengiriman jauh di luar zona antar lokal tidak tersedia; pilih ambil di tempat atau zona yang diizinkan.</li>
    </ul>

    <h2>6. Pembatalan dan pengembalian</h2>
    <p>
      Pembatalan dan pengembalian dana mengikuti kebijakan pengelola desa serta ketentuan mitra pembayaran.
      Untuk permintaan pembatalan, hubungi kontak resmi dengan menyertakan kode transaksi (RSV/ORD).
    </p>

    <h2>7. Kewajiban pengguna</h2>
    <ul>
      <li>Tidak menyalahgunakan layanan untuk aktivitas ilegal atau menyesatkan.</li>
      <li>Menjaga ketertiban selama kegiatan wisata, menginap, atau penerimaan catering.</li>
      <li>Memastikan usia anak/peserta sesuai data yang dicantumkan pada reservasi.</li>
    </ul>

    <h2>8. Batasan tanggung jawab</h2>
    <p>
      Kami berupaya menjaga keakuratan informasi dan kelancaran layanan. Namun, kami tidak bertanggung jawab atas
      kerugian tidak langsung yang timbul dari keterlambatan pihak ketiga (kurir, payment gateway), gangguan jaringan,
      atau force majeure di luar kendali wajar pengelola.
    </p>

    <h2>9. Perubahan ketentuan</h2>
    <p>
      Syarat &amp; Ketentuan dapat diperbarui sewaktu-waktu. Penggunaan layanan setelah pembaruan berarti Anda
      menyetujui ketentuan terbaru yang ditampilkan di halaman ini.
    </p>

    <h2>10. Kontak</h2>
    <p>
      Pertanyaan terkait syarat layanan dapat ditujukan kepada pengelola <?= esc($brand) ?>
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
    <a href="<?= site_url('kebijakan-privasi') ?>">Baca Kebijakan Privasi →</a>
  </p>
</section>
<?= $this->endSection() ?>