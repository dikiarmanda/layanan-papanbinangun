<?php

use App\Models\PengaturanMasterModel;

if (! function_exists('pengaturan')) {
    /**
     * Data situs dari database master (desa_wisata.pengaturan_situs).
     *
     * @return array<string, mixed>
     */
    function pengaturan(): array
    {
        static $data = null;

        if ($data === null) {
            $data = (new PengaturanMasterModel())->get();
        }

        return $data;
    }
}

if (! function_exists('landing_url')) {
    /**
     * Base URL website profil (untuk logo upload di master, dll).
     */
    function landing_url(string $path = ''): string
    {
        $base = rtrim((string) (env('app.landingURL') ?: 'http://localhost/papanbinangun/public/'), '/');

        if ($path === '') {
            return $base . '/';
        }

        return $base . '/' . ltrim($path, '/');
    }
}

if (! function_exists('brand_logo_url')) {
    /**
     * Logo dari master (jika ada), fallback ke aset lokal layanan.
     */
    function brand_logo_url(): string
    {
        $logo = pengaturan()['logo'] ?? null;

        if (is_string($logo) && $logo !== '') {
            if (preg_match('#^https?://#i', $logo) === 1) {
                return $logo;
            }

            return landing_url($logo);
        }

        return base_url('assets/images/brand-logo.png');
    }
}

if (! function_exists('wa_link')) {
    function wa_link(?string $number, string $message = ''): string
    {
        $number = preg_replace('/[^0-9]/', '', $number ?? '') ?? '';
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        $url = 'https://wa.me/' . $number;
        if ($message !== '') {
            $url .= '?text=' . rawurlencode($message);
        }

        return $url;
    }
}

if (! function_exists('format_rupiah')) {
    function format_rupiah(float|int|string $amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }
}

if (! function_exists('generate_kode')) {
    function generate_kode(string $prefix): string
    {
        return strtoupper($prefix) . '-' . bin2hex(random_bytes(12));
    }
}

if (! function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text) ?? '';
        $text = preg_replace('/[\s-]+/', '-', $text) ?? '';

        return trim($text, '-');
    }
}

if (! function_exists('badge_status')) {
    function badge_status(string $status): string
    {
        $class = 'badge badge-' . esc($status, 'attr');

        return '<span class="' . $class . '">' . esc($status) . '</span>';
    }
}

if (! function_exists('media_url')) {
    /**
     * URL lokal (uploads/...) atau absolut (https://images.unsplash.com/...).
     */
    function media_url(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        return base_url($path);
    }
}

if (! function_exists('unsplash_by_jenis')) {
    /**
     * Gambar Unsplash fallback per jenis layanan (hero / kartu).
     */
    function unsplash_by_jenis(string $jenis, int $index = 0): string
    {
        $pool = [
            'wisata' => [
                'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1400&q=80',
                'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1400&q=80',
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1400&q=80',
            ],
            'homestay' => [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1400&q=80',
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1400&q=80',
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1400&q=80',
            ],
            'camping' => [
                'https://images.unsplash.com/photo-1504851149312-7a075b496cc7?w=1400&q=80',
                'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=1400&q=80',
                'https://images.unsplash.com/photo-1537905569824-f89d28b5e0e2?w=1400&q=80',
            ],
            'umkm' => [
                'https://images.unsplash.com/photo-1610701596007-11502861dcfa?w=1400&q=80',
                'https://images.unsplash.com/photo-1558171813-4c088753af8f?w=1400&q=80',
                'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=1400&q=80',
            ],
            'catering' => [
                'https://images.unsplash.com/photo-1555244162-803834f70033?w=1400&q=80',
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=1400&q=80',
                'https://images.unsplash.com/photo-1481391319762-47dff72954d9?w=1400&q=80',
            ],
        ];

        $images = $pool[$jenis] ?? $pool['wisata'];

        return $images[$index % count($images)];
    }
}

if (! function_exists('cover_url')) {
    /**
     * Gambar cover lokal/URL, fallback Unsplash jika kosong atau file lokal tidak ada.
     */
    function cover_url(?string $path, string $jenis = 'wisata', int $index = 0): string
    {
        if ($path !== null && $path !== '') {
            if (preg_match('#^https?://#i', $path) === 1) {
                return $path;
            }

            $absolute = FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($path, '/\\'));
            if (is_file($absolute)) {
                return base_url($path);
            }
        }

        return unsplash_by_jenis($jenis, $index);
    }
}

if (! function_exists('label_jenis_paket')) {
    function label_jenis_paket(string $jenis): string
    {
        return match ($jenis) {
            'homestay' => 'Homestay',
            'camping' => 'Camping Ground',
            'umkm' => 'UMKM',
            'catering' => 'Catering',
            default => 'Paket Wisata',
        };
    }
}

if (! function_exists('satuan_label')) {
    function satuan_label(string $jenis, ?string $satuan = null): string
    {
        if ($jenis === 'homestay' || $jenis === 'camping' || $satuan === 'per_rumah') {
            return 'rumah / malam';
        }

        return 'orang';
    }
}

if (! function_exists('is_paket_menginap')) {
    /** Homestay & camping ground memakai alur check-in/out per rumah × malam. */
    function is_paket_menginap(string $jenis): bool
    {
        return in_array($jenis, ['homestay', 'camping'], true);
    }
}

if (! function_exists('upload_image')) {
    /**
     * @return string|null relative path under public/uploads
     */
    function upload_image(string $field, string $folder = 'umum'): ?string
    {
        $file = service('request')->getFile($field);

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        $dir = FCPATH . 'uploads/' . $folder;
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($dir, $newName);

        return 'uploads/' . $folder . '/' . $newName;
    }
}
