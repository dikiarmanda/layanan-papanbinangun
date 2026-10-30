<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="page-toolbar" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
  <p style="margin:0">Daftar akun pelanggan dan riwayat transaksi mereka.</p>
  <form method="get" action="<?= site_url('admin/pelanggan') ?>" style="display:flex;gap:0.5rem">
    <input type="text" name="q" value="<?= esc($q ?? '') ?>" class="form-control" placeholder="Cari nama, email, hp..." style="width:240px">
    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
    <?php if (!empty($q)): ?>
      <a href="<?= site_url('admin/pelanggan') ?>" class="btn btn-outline">Reset</a>
    <?php endif; ?>
  </form>
</div>

<div class="card card-table">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>No. HP</th>
          <th>Tipe Akun</th>
          <th>Total Order</th>
          <th>Total Reservasi</th>
          <th>Terdaftar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($pelanggan)): ?>
          <tr>
            <td colspan="8" class="empty-state">
              <i class="fa-solid fa-users-slash"></i>
              Belum ada data pelanggan.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($pelanggan as $p): ?>
            <tr>
              <td><strong><?= esc($p['nama']) ?></strong></td>
              <td><?= esc($p['email']) ?></td>
              <td><?= esc($p['no_hp'] ?? '-') ?></td>
              <td>
                <?php if (!empty($p['password'])): ?>
                  <span class="badge" style="background:#28a745;color:#fff">Terdaftar</span>
                <?php else: ?>
                  <span class="badge" style="background:#6c757d;color:#fff">Guest Checkout</span>
                <?php endif; ?>
              </td>
              <td><?= (int) ($p['total_order'] ?? 0) ?> pesanan</td>
              <td><?= (int) ($p['total_reservasi'] ?? 0) ?> reservasi</td>
              <td><?= date('d M Y H:i', strtotime($p['created_at'])) ?></td>
              <td class="actions">
                <a href="<?= site_url('admin/pelanggan/' . $p['id']) ?>" class="btn btn-sm btn-primary">
                  <i class="fa-solid fa-eye"></i> Detail
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
