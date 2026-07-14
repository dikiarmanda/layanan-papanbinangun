# Layanan Reservasi & E-commerce — Desa Wisata Papanbinangun

Subdomain target: `layanan.papanbinangun.id`  
Stack: CodeIgniter 4 + MySQL + Midtrans Snap + RajaOngkir + Tailwind (tema vintage)

## Setup lokal (XAMPP)

1. Pastikan Apache + MySQL aktif.
2. Database sudah diimpor dari `database-layanan.sql` (nama DB: `layanan_desa`).
3. Salin/atur `.env`:
   - `app.baseURL` → `http://localhost/layanan-papanbinangun/public/`
   - DB: user `root`, password kosong (default XAMPP)
   - `midtrans.serverKey` / `midtrans.clientKey` (Sandbox)
   - RajaOngkir ([docs](https://rajaongkir.com/docs)):
     - `rajaongkir.url` = `https://rajaongkir.komerce.id/api/v1`
     - `rajaongkir.shippingKey`
     - `rajaongkir.origin` = destination ID asal (contoh Pandaan: `60224`)
     - `rajaongkir.couriers` = `jne:jnt:pos`
4. Buka: http://localhost/layanan-papanbinangun/public/
5. Admin: http://localhost/layanan-papanbinangun/public/admin/login  
   - Email: `superadmin@layanan.papanbinangun.id`  
   - Password: `admin123` (**ganti sebelum production**)

## Webhook Midtrans

URL notifikasi (Sandbox → Production setelah go-live):

`https://layanan.papanbinangun.id/midtrans/notification`

(atau URL ngrok saat development lokal)

## Fitur v1

- Guest checkout reservasi & produk (terpisah)
- Lock kuota/stok saat checkout pending; release jika expired/failed
- Snap Midtrans + verifikasi signature webhook
- Ongkir RajaOngkir (JNE/J&T/POS)
- WhatsApp stub → log ke `whatsapp_log`
- Role Superadmin (kelola user) & Admin

## Build CSS

```bash
npm run build
```
