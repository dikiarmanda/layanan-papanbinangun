<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar">
  <p>Detail profil pelanggan & riwayat aktivitas.</p>
  <a href="<?= site_url('admin/pelanggan') ?>" class="btn btn-outline btn-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
  </a>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--admin-border);padding-bottom:1rem;margin-bottom:1rem">
    <h3 style="margin:0"><i class="fa-solid fa-user-circle"></i> <?= esc($pelanggan['nama']) ?></h3>
    <?php if (!empty($pelanggan['password'])): ?>
      <span class="badge" style="background:#28a745;color:#fff">Akun Terdaftar</span>
    <?php else: ?>
      <span class="badge" style="background:#6c757d;color:#fff">Guest Checkout</span>
    <?php endif; ?>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:1rem">
    <div>
      <div class="text-muted" style="font-size:0.85rem">Nama Lengkap</div>
      <strong><?= esc($pelanggan['nama']) ?></strong>
    </div>
    <div>
      <div class="text-muted" style="font-size:0.85rem">Email</div>
      <strong><?= esc($pelanggan['email']) ?></strong>
    </div>
    <div>
      <div class="text-muted" style="font-size:0.85rem">Nomor HP / WhatsApp</div>
      <strong><?= esc($pelanggan['no_hp'] ?? '-') ?></strong>
    </div>
    <div>
      <div class="text-muted" style="font-size:0.85rem">Waktu Pendaftaran</div>
      <strong><?= date('d M Y, H:i', strtotime($pelanggan['created_at'])) ?></strong>
    </div>
  </div>
</div>

<div class="card card-table" style="margin-bottom:1.5rem">
  <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--admin-border)">
    <h4 style="margin:0"><i class="fa-solid fa-bag-shopping"></i> Riwayat Order Belanja (<?= count($orders) ?>)</h4>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Kode Order</th>
          <th>Tanggal</th>
          <th>Total Belanja</th>
          <th>Status Pembayaran</th>
          <th>Status Order</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr>
            <td colspan="6" class="empty-state">Belum pernah melakukan order produk.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td><strong><?= esc($o['kode_order']) ?></strong></td>
              <td><?= date('d M Y H:i', strtotime($o['created_at'])) ?></td>
              <td><?= format_rupiah($o['total_harga']) ?></td>
              <td><?= badge_status($o['status_pembayaran']) ?></td>
              <td><?= badge_status($o['status_order']) ?></td>
              <td>
                <a href="<?= site_url('admin/order/' . $o['id']) ?>" class="btn btn-sm btn-outline">
                  <i class="fa-solid fa-eye"></i> Lihat Order
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="card card-table">
  <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--admin-border)">
    <h4 style="margin:0"><i class="fa-solid fa-calendar-check"></i> Riwayat Reservasi Wisata & Homestay (<?= count($reservasi) ?>)</h4>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Kode Reservasi</th>
          <th>Paket Wisata</th>
          <th>Check-in</th>
          <th>Tamu</th>
          <th>Total</th>
          <th>Status Bayar</th>
          <th>Status Reservasi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($reservasi)): ?>
          <tr>
            <td colspan="8" class="empty-state">Belum pernah melakukan reservasi wisata.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($reservasi as $r): ?>
            <tr>
              <td><strong><?= esc($r['kode_reservasi']) ?></strong></td>
              <td><?= esc($r['nama_paket'] ?? '-') ?></td>
              <td><?= date('d M Y', strtotime($r['check_in'])) ?></td>
              <td><?= (int) $r['jumlah_tamu'] ?> orang</td>
              <td><?= format_rupiah($r['total_harga']) ?></td>
              <td><?= badge_status($r['status_pembayaran']) ?></td>
              <td><?= badge_status($r['status_reservasi']) ?></td>
              <td>
                <a href="<?= site_url('admin/reservasi/' . $r['id']) ?>" class="btn btn-sm btn-outline">
                  <i class="fa-solid fa-eye"></i> Lihat Reservasi
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
