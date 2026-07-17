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
