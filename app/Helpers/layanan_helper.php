<?php

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
