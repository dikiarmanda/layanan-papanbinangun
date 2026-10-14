<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f4f4f5;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f5;padding:24px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;">

    <tr>
        <td style="background:#16a34a;padding:24px 32px;">
            <h1 style="margin:0;color:#ffffff;font-size:20px;">Pembayaran Berhasil</h1>
        </td>
    </tr>

    <tr>
        <td style="padding:32px;">
            <p style="margin:0 0 16px;font-size:15px;color:#374151;">
                Halo <strong><?= esc($nama) ?></strong>,
            </p>
            <p style="margin:0 0 24px;font-size:15px;color:#374151;">
                Terima kasih! Pembayaran pesanan Anda telah berhasil diproses. Berikut rincian pesanan Anda:
            </p>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
                <tr style="background:#f9fafb;">
                    <td style="padding:12px 16px;font-size:13px;color:#6b7280;">Kode Pesanan</td>
                    <td style="padding:12px 16px;font-size:13px;font-weight:bold;color:#111827;"><?= esc($order['kode_order']) ?></td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;font-size:13px;color:#6b7280;border-top:1px solid #e5e7eb;">Tanggal Bayar</td>
                    <td style="padding:12px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;"><?= format_tanggal($order['paid_at'] ?? $order['updated_at'] ?? '', true) ?></td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;font-size:13px;color:#6b7280;border-top:1px solid #e5e7eb;">Alamat Kirim</td>
                    <td style="padding:12px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;"><?= esc($order['alamat_kirim'] ?? '-') ?></td>
                </tr>
                <?php if (!empty($order['kurir'])): ?>
                <tr>
                    <td style="padding:12px 16px;font-size:13px;color:#6b7280;border-top:1px solid #e5e7eb;">Kurir</td>
                    <td style="padding:12px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;"><?= esc(strtoupper($order['kurir'])) ?> — <?= esc($order['layanan_kurir'] ?? '') ?></td>
                </tr>
                <?php endif; ?>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
                <tr style="background:#f9fafb;">
                    <th style="padding:10px 16px;font-size:12px;text-align:left;color:#6b7280;">Produk</th>
                    <th style="padding:10px 16px;font-size:12px;text-align:center;color:#6b7280;">Qty</th>
                    <th style="padding:10px 16px;font-size:12px;text-align:right;color:#6b7280;">Subtotal</th>
                </tr>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td style="padding:10px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;"><?= esc($item['nama_produk']) ?></td>
                    <td style="padding:10px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;text-align:center;"><?= (int)$item['jumlah'] ?></td>
                    <td style="padding:10px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;text-align:right;"><?= format_rupiah($item['subtotal']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if ((float)($order['ongkos_kirim'] ?? 0) > 0): ?>
                <tr>
                    <td colspan="2" style="padding:10px 16px;font-size:13px;color:#6b7280;border-top:1px solid #e5e7eb;">Ongkos Kirim</td>
                    <td style="padding:10px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;text-align:right;"><?= format_rupiah($order['ongkos_kirim']) ?></td>
                </tr>
                <?php endif; ?>
                <tr style="background:#f0fdf4;">
                    <td colspan="2" style="padding:12px 16px;font-size:14px;font-weight:bold;color:#16a34a;border-top:2px solid #16a34a;">Total</td>
                    <td style="padding:12px 16px;font-size:14px;font-weight:bold;color:#16a34a;border-top:2px solid #16a34a;text-align:right;"><?= format_rupiah($order['total_harga']) ?></td>
                </tr>
            </table>

            <p style="margin:0 0 8px;font-size:13px;color:#6b7280;">
                Anda dapat memantau status pesanan melalui halaman akun Anda atau melalui tautan berikut:
            </p>
            <p style="margin:0 0 24px;">
                <a href="<?= site_url('status/' . $order['kode_order']) ?>" style="display:inline-block;background:#16a34a;color:#ffffff;padding:10px 24px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:bold;">Lihat Status Pesanan</a>
            </p>
        </td>
    </tr>

    <tr>
        <td style="background:#f9fafb;padding:20px 32px;border-top:1px solid #e5e7eb;">
            <p style="margin:0;font-size:12px;color:#9ca3af;text-align:center;">
                Email ini dikirim otomatis oleh sistem Papan Binangun. Jangan membalas email ini.
            </p>
        </td>
    </tr>

</table>
</td></tr>
</table>
</body>
</html>
