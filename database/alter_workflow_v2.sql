-- Workflow v2: wisata/homestay + umkm/catering + zona antar lokal
-- Database: layanan_desa

-- 1) paket_wisata: jenis + satuan_harga per_rumah
ALTER TABLE paket_wisata
ADD COLUMN jenis ENUM('wisata', 'homestay') NOT NULL DEFAULT 'wisata' AFTER slug;

UPDATE paket_wisata
SET
    jenis = 'homestay'
WHERE
    satuan_harga = 'per_paket';

UPDATE paket_wisata
SET
    jenis = 'wisata'
WHERE
    satuan_harga = 'per_orang';

ALTER TABLE paket_wisata
MODIFY COLUMN satuan_harga ENUM(
    'per_orang',
    'per_rumah',
    'per_paket'
) NOT NULL DEFAULT 'per_orang';

UPDATE paket_wisata
SET
    satuan_harga = 'per_rumah'
WHERE
    satuan_harga = 'per_paket';

ALTER TABLE paket_wisata
MODIFY COLUMN satuan_harga ENUM('per_orang', 'per_rumah') NOT NULL DEFAULT 'per_orang';

-- 2) reservasi: check-in / check-out homestay
ALTER TABLE reservasi
ADD COLUMN check_in DATE NULL AFTER jumlah_tamu,
ADD COLUMN check_out DATE NULL AFTER check_in,
ADD COLUMN jumlah_malam INT UNSIGNED NULL AFTER check_out;

-- 3) produk: jenis umkm / catering
ALTER TABLE produk
ADD COLUMN jenis ENUM('umkm', 'catering') NOT NULL DEFAULT 'umkm' AFTER slug;

-- 4) zona antar lokal (catering)
CREATE TABLE IF NOT EXISTS zona_antar_lokal (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nama VARCHAR(150) NOT NULL,
    deskripsi TEXT NULL,
    ongkir DECIMAL(12, 2) NOT NULL DEFAULT 0,
    estimasi VARCHAR(50) NOT NULL DEFAULT '1-2 jam',
    status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_zona_status (status)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    zona_antar_lokal (
        nama,
        deskripsi,
        ongkir,
        estimasi,
        status
    )
SELECT 'Desa Binangun', 'Antar dalam wilayah desa', 15000, '30-60 menit', 'aktif'
WHERE
    NOT EXISTS (
        SELECT 1
        FROM zona_antar_lokal
        LIMIT 1
    );

INSERT INTO
    zona_antar_lokal (
        nama,
        deskripsi,
        ongkir,
        estimasi,
        status
    )
SELECT 'Kecamatan sekitar', 'Antar ke kecamatan sekitar desa', 25000, '1-2 jam', 'aktif'
WHERE (
        SELECT COUNT(*)
        FROM zona_antar_lokal
    ) < 2;

-- 5) order: field catering
ALTER TABLE `order`
ADD COLUMN tanggal_acara DATE NULL AFTER alamat_kirim,
ADD COLUMN waktu_acara TIME NULL AFTER tanggal_acara,
ADD COLUMN metode_pengiriman ENUM(
    'ekspedisi',
    'ambil_di_tempat',
    'antar_lokal'
) NOT NULL DEFAULT 'ekspedisi' AFTER waktu_acara,
ADD COLUMN zona_antar_id INT UNSIGNED NULL AFTER metode_pengiriman,
ADD KEY idx_order_zona (zona_antar_id),
ADD CONSTRAINT fk_order_zona_antar FOREIGN KEY (zona_antar_id) REFERENCES zona_antar_lokal (id) ON DELETE SET NULL;