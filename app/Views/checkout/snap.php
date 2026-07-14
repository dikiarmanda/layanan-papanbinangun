<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="section container" style="max-width:640px;margin:0 auto">
  <h1><?= esc($title) ?></h1>
  <p>Kode transaksi: <strong><?= esc($kode) ?></strong></p>

  <?php if (! empty($error)): ?>
    <div class="alert alert-info"><?= esc($error) ?></div>
    <a class="btn btn-primary" href="<?= esc($statusUrl) ?>">Lihat Status</a>
  <?php elseif (! empty($snapToken)): ?>
    <div class="alert alert-info">Mengalihkan ke pembayaran Midtrans…</div>
    <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
    <p style="margin-top:1rem"><a href="<?= esc($statusUrl) ?>">Atau cek status nanti</a></p>
    <script src="<?= ! empty($isProduction) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' ?>"
            data-client-key="<?= esc($clientKey) ?>"></script>
    <script>
      document.getElementById('pay-button').onclick = function () {
        window.snap.pay('<?= esc($snapToken, 'js') ?>', {
          onSuccess: function(){ location.href = '<?= esc($statusUrl, 'js') ?>'; },
          onPending: function(){ location.href = '<?= esc($statusUrl, 'js') ?>'; },
          onError: function(){ location.href = '<?= esc($statusUrl, 'js') ?>'; },
          onClose: function(){ location.href = '<?= esc($statusUrl, 'js') ?>'; }
        });
      };
      document.getElementById('pay-button').click();
    </script>
  <?php endif; ?>
</section>
<?= $this->endSection() ?>
