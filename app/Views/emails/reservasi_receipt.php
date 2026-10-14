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
            <h1 style="margin:0;color:#ffffff;font-size:20px;">Reservasi Dikonfirmasi</h1>
        </td>
    </tr>

    <tr>
        <td style="padding:32px;">
            <p style="margin:0 0 16px;font-size:15px;color:#374151;">
                Halo <strong><?= esc($nama) ?></strong>,
            </p>
            <p style="margin:0 0 24px;font-size:15px;color:#374151;">
                Terima kasih! Pembayaran reservasi Anda telah berhasil. Berikut rincian reservasi Anda:
            </p>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
                <tr style="background:#f9fafb;">
                    <td style="padding:12px 16px;font-size:13px;color:#6b7280;">Kode Reservasi</td>
                    <td style="padding:12px 16px;font-size:13px;font-weight:bold;color:#111827;"><?= esc($reservasi['kode_reservasi']) ?></td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;font-size:13px;color:#6b7280;border-top:1px solid #e5e7eb;">Paket</td>
                    <td style="padding:12px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;"><?= esc($paketNama) ?></td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;font-size:13px;color:#6b7280;border-top:1px solid #e5e7eb;">Jumlah Tamu</td>
                    <td style="padding:12px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;"><?= (int)$reservasi['jumlah_tamu'] ?> orang</td>
                </tr>
                <?php if (!empty($reservasi['check_in']) && !empty($reservasi['check_out'])): ?>
                <tr>
                    <td style="padding:12px 16px;font-size:13px;color:#6b7280;border-top:1px solid #e5e7eb;">Check-in</td>
                    <td style="padding:12px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;"><?= format_tanggal($reservasi['check_in']) ?></td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;font-size:13px;color:#6b7280;border-top:1px solid #e5e7eb;">Check-out</td>
                    <td style="padding:12px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;"><?= format_tanggal($reservasi['check_out']) ?></td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;font-size:13px;color:#6b7280;border-top:1px solid #e5e7eb;">Jumlah Malam</td>
                    <td style="padding:12px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;"><?= (int)$reservasi['jumlah_malam'] ?> malam</td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td style="padding:12px 16px;font-size:13px;color:#6b7280;border-top:1px solid #e5e7eb;">Tanggal Bayar</td>
                    <td style="padding:12px 16px;font-size:13px;color:#111827;border-top:1px solid #e5e7eb;"><?= format_tanggal($reservasi['paid_at'] ?? $reservasi['updated_at'] ?? '', true) ?></td>
                </tr>
                <tr style="background:#f0fdf4;">
                    <td style="padding:12px 16px;font-size:14px;font-weight:bold;color:#16a34a;border-top:2px solid #16a34a;">Total Bayar</td>
                    <td style="padding:12px 16px;font-size:14px;font-weight:bold;color:#16a34a;border-top:2px solid #16a34a;"><?= format_rupiah($reservasi['total_harga']) ?></td>
                </tr>
            </table>

            <?php if (!empty($reservasi['catatan'])): ?>
            <p style="margin:0 0 24px;font-size:13px;color:#6b7280;">
                <strong>Catatan:</strong> <?= esc($reservasi['catatan']) ?>
            </p>
            <?php endif; ?>

            <p style="margin:0 0 8px;font-size:13px;color:#6b7280;">
                Anda dapat memantau status reservasi melalui halaman akun Anda atau melalui tautan berikut:
            </p>
            <p style="margin:0 0 24px;">
                <a href="<?= site_url('status/' . $reservasi['kode_reservasi']) ?>" style="display:inline-block;background:#16a34a;color:#ffffff;padding:10px 24px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:bold;">Lihat Status Reservasi</a>
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
