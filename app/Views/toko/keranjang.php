<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container">
  <h1>Keranjang Belanja</h1>
  <?php if (empty($items)): ?>
    <p style="color:var(--sepia)">Keranjang masih kosong. <a href="<?= site_url('toko') ?>">Belanja sekarang</a></p>
  <?php else: ?>
    <table class="table">
      <thead>
        <tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?= esc($item['produk']['nama']) ?></td>
            <td><?= format_rupiah($item['produk']['harga']) ?></td>
            <td>
              <form method="post" action="<?= site_url('keranjang/update') ?>" style="display:flex;gap:0.4rem">
                <?= csrf_field() ?>
                <input type="hidden" name="produk_id" value="<?= (int) $item['produk']['id'] ?>">
                <input type="number" name="jumlah" value="<?= (int) $item['jumlah'] ?>" min="0" class="form-control" style="width:80px">
                <button class="btn btn-sm btn-outline" type="submit">Update</button>
              </form>
            </td>
            <td><?= format_rupiah($item['subtotal']) ?></td>
            <td><a href="<?= site_url('keranjang/remove/' . $item['produk']['id']) ?>">Hapus</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div style="margin-top:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
      <div class="price">Subtotal: <?= format_rupiah($subtotal) ?></div>
      <a class="btn btn-primary" href="<?= site_url('checkout-produk') ?>">Checkout</a>
    </div>
  <?php endif; ?>
</section>
<?= $this->endSection() ?>
