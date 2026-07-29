<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seed paket (wisata / homestay / camping) + produk (umkm / catering)
 * dengan gambar Unsplash.
 */
class LayananDummySeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;

        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        $db->table('order_items')->truncate();
        $db->table('order')->truncate();
        $db->table('reservasi')->truncate();
        $db->table('jadwal_paket_wisata')->truncate();
        $db->table('paket_wisata')->truncate();
        $db->table('produk')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        $adminId = 1;
        $now = date('Y-m-d H:i:s');

        $paket = [
            [
                'nama' => 'Paket Jelajah Desa Binangun',
                'slug' => 'paket-jelajah-desa-binangun',
                'jenis' => 'wisata',
                'deskripsi' => 'Tur setengah hari mengelilingi desa: sawah, kerajinan warga, dan kuliner lokal. Termasuk pemandu, snack, dan dokumentasi singkat.',
                'harga' => 150000,
                'satuan_harga' => 'per_orang',
                'kuota_default' => 20,
                'gambar_cover' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&q=80',
                'status' => 'publish',
            ],
            [
                'nama' => 'Paket Sunrise & Trekking Ringan',
                'slug' => 'paket-sunrise-trekking-ringan',
                'jenis' => 'wisata',
                'deskripsi' => 'Sunrise di bukit desa dilanjutkan trekking ringan ±2 jam. Cocok untuk pemula. Termasuk air mineral dan sarapan sederhana.',
                'harga' => 200000,
                'satuan_harga' => 'per_orang',
                'kuota_default' => 15,
                'gambar_cover' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1200&q=80',
                'status' => 'publish',
            ],
            [
                'nama' => 'Paket Edukasi Kerajinan & Kuliner',
                'slug' => 'paket-edukasi-kerajinan-kuliner',
                'jenis' => 'wisata',
                'deskripsi' => 'Workshop kerajinan bersama pengrajin desa, dilanjutkan memasak dan menikmati hidangan khas bersama ibu-ibu PKK.',
                'harga' => 185000,
                'satuan_harga' => 'per_orang',
                'kuota_default' => 12,
                'gambar_cover' => 'https://images.unsplash.com/photo-1452860606245-08befc0ff44b?w=1200&q=80',
                'status' => 'publish',
            ],
            [
                'nama' => 'Homestay Keluarga Binangun',
                'slug' => 'homestay-keluarga-binangun',
                'jenis' => 'homestay',
                'deskripsi' => 'Menginap di rumah warga — kamar bersih, kamar mandi dalam, sarapan tradisional. Harga per rumah / malam.',
                'harga' => 350000,
                'satuan_harga' => 'per_rumah',
                'kuota_default' => 1,
                'gambar_cover' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200&q=80',
                'status' => 'publish',
            ],
            [
                'nama' => 'Homestay View Sawah',
                'slug' => 'homestay-view-sawah',
                'jenis' => 'homestay',
                'deskripsi' => 'Kamar menghadap sawah, cocok untuk pasangan atau keluarga kecil. Termasuk sarapan dan teh/kopi. Harga per rumah / malam.',
                'harga' => 275000,
                'satuan_harga' => 'per_rumah',
                'kuota_default' => 1,
                'gambar_cover' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&q=80',
                'status' => 'publish',
            ],
            [
                'nama' => 'Camping Ground Bukit Binangun',
                'slug' => 'camping-ground-bukit-binangun',
                'jenis' => 'camping',
                'deskripsi' => 'Area camping ground dengan view pegunungan. Termasuk lahan tenda, toilet umum, dan api unggun (kayu terbatas). Harga per unit / malam (sama seperti homestay).',
                'harga' => 250000,
                'satuan_harga' => 'per_rumah',
                'kuota_default' => 1,
                'gambar_cover' => 'https://images.unsplash.com/photo-1504851149312-7a075b496cc7?w=1200&q=80',
                'status' => 'publish',
            ],
            [
                'nama' => 'Camping Family Glamping Mini',
                'slug' => 'camping-family-glamping-mini',
                'jenis' => 'camping',
                'deskripsi' => 'Pengalaman camping nyaman dengan tenda siap pasang, lampu solar, dan area memasak bersama. Harga per unit / malam.',
                'harga' => 400000,
                'satuan_harga' => 'per_rumah',
                'kuota_default' => 1,
                'gambar_cover' => 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=1200&q=80',
                'status' => 'publish',
            ],
        ];

        foreach ($paket as $row) {
            $db->table('paket_wisata')->insert(array_merge($row, [
                'admin_id' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // Jadwal wisata: beberapa tanggal terpilih
        $wisataRows = $db->table('paket_wisata')->where('jenis', 'wisata')->get()->getResultArray();
        foreach ($wisataRows as $p) {
            $kuota = (int) ($p['kuota_default'] ?: 10);
            foreach ([3, 7, 12] as $offset) {
                $db->table('jadwal_paket_wisata')->insert([
                    'paket_wisata_id' => $p['id'],
                    'tanggal' => date('Y-m-d', strtotime("+{$offset} day")),
                    'kuota' => $kuota,
                    'kuota_terpakai' => 0,
                    'created_at' => $now,
                ]);
            }
        }

        // Homestay & camping: jadwal harian 30 malam ke depan (alur check-in/out)
        $menginapRows = $db->table('paket_wisata')->whereIn('jenis', ['homestay', 'camping'])->get()->getResultArray();
        foreach ($menginapRows as $p) {
            $kuota = (int) ($p['kuota_default'] ?: 1);
            for ($d = 0; $d < 30; $d++) {
                $db->table('jadwal_paket_wisata')->insert([
                    'paket_wisata_id' => $p['id'],
                    'tanggal' => date('Y-m-d', strtotime("+{$d} day")),
                    'kuota' => $kuota,
                    'kuota_terpakai' => 0,
                    'created_at' => $now,
                ]);
            }
        }

        $produk = [
            [
                'nama' => 'Anyaman Bambu Tempat Pensil',
                'slug' => 'anyaman-bambu-tempat-pensil',
                'jenis' => 'umkm',
                'deskripsi' => 'Tempat pensil anyaman bambu karya pengrajin desa. Finishing natural.',
                'harga' => 45000,
                'stok' => 40,
                'berat' => 300,
                'gambar' => 'https://images.unsplash.com/photo-1610701596007-11502861dcfa?w=800&q=80',
                'kategori_id' => 1,
            ],
            [
                'nama' => 'Tas Rajut Serbaguna',
                'slug' => 'tas-rajut-serbaguna',
                'jenis' => 'umkm',
                'deskripsi' => 'Tas rajut handmade warna earth tone — cocok souvenir.',
                'harga' => 85000,
                'stok' => 25,
                'berat' => 450,
                'gambar' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=800&q=80',
                'kategori_id' => 1,
            ],
            [
                'nama' => 'Keripik Singkong Original 250g',
                'slug' => 'keripik-singkong-original-250g',
                'jenis' => 'umkm',
                'deskripsi' => 'Keripik singkong renyah, kemasan standing pouch 250 gram.',
                'harga' => 28000,
                'stok' => 80,
                'berat' => 280,
                'gambar' => 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?w=800&q=80',
                'kategori_id' => 2,
            ],
            [
                'nama' => 'Kopi Bubuk Robusta Desa 200g',
                'slug' => 'kopi-bubuk-robusta-desa-200g',
                'jenis' => 'umkm',
                'deskripsi' => 'Kopi robusta sangrai medium, digiling sedang.',
                'harga' => 48000,
                'stok' => 50,
                'berat' => 220,
                'gambar' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=800&q=80',
                'kategori_id' => 2,
            ],
            [
                'nama' => 'Selendang Batik Cap Motif Desa',
                'slug' => 'selendang-batik-cap-motif-desa',
                'jenis' => 'umkm',
                'deskripsi' => 'Selendang batik cap motif khas desa, bahan katun.',
                'harga' => 95000,
                'stok' => 20,
                'berat' => 250,
                'gambar' => 'https://images.unsplash.com/photo-1558171813-4c088753af8f?w=800&q=80',
                'kategori_id' => 3,
            ],
            [
                'nama' => 'Kaos Wisata Binangun (M)',
                'slug' => 'kaos-wisata-binangun-m',
                'jenis' => 'umkm',
                'deskripsi' => 'Kaos cotton combed 30s sablon motif Wisata Binangun.',
                'harga' => 78000,
                'stok' => 25,
                'berat' => 200,
                'gambar' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80',
                'kategori_id' => 4,
            ],
            [
                'nama' => 'Paket Nasi Box Desa (per porsi)',
                'slug' => 'paket-nasi-box-desa',
                'jenis' => 'catering',
                'deskripsi' => 'Nasi, lauk pauk tradisional, sayur, dan kerupuk. Cocok meeting & gathering. Minimum 20 porsi.',
                'harga' => 28000,
                'stok' => 200,
                'berat' => 600,
                'gambar' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&q=80',
                'kategori_id' => 2,
            ],
            [
                'nama' => 'Paket Prasmanan Tradisional (50 pax)',
                'slug' => 'paket-prasmanan-tradisional-50-pax',
                'jenis' => 'catering',
                'deskripsi' => 'Menu prasmanan lengkap untuk 50 orang: nasi, 3 lauk, sayur, sambal, buah, dan air mineral.',
                'harga' => 1750000,
                'stok' => 10,
                'berat' => 25000,
                'gambar' => 'https://images.unsplash.com/photo-1555244162-803834f70033?w=800&q=80',
                'kategori_id' => 2,
            ],
            [
                'nama' => 'Snack Box UMKM (per box)',
                'slug' => 'snack-box-umkm',
                'jenis' => 'catering',
                'deskripsi' => 'Isi keripik, kue basah, dan air mineral dalam box rapi. Cocok seminar singkat.',
                'harga' => 18000,
                'stok' => 300,
                'berat' => 350,
                'gambar' => 'https://images.unsplash.com/photo-1481391319762-47dff72954d9?w=800&q=80',
                'kategori_id' => 2,
            ],
        ];

        foreach ($produk as $row) {
            $db->table('produk')->insert(array_merge($row, [
                'status' => 'publish',
                'admin_id' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
