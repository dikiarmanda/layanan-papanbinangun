<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Baca pengaturan situs dari database master (landing page desa_wisata).
 */
class PengaturanMasterModel extends Model
{
    protected $DBGroup          = 'master';
    protected $table            = 'pengaturan_situs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [];
    protected $useTimestamps    = false;

    /**
     * @return array{
     *   id:int,
     *   nama_desa:string,
     *   tagline:string,
     *   deskripsi_singkat:string,
     *   alamat:string,
     *   no_whatsapp:string,
     *   email_kontak:string,
     *   instagram_url:string,
     *   tiktok_url:string,
     *   facebook_url:string,
     *   google_maps_embed:string,
     *   logo:?string
     * }
     */
    public function get(): array
    {
        $fallback = [
            'id'                 => 1,
            'nama_desa'          => 'Wisata Binangun',
            'tagline'            => 'Pesona Alam & Budaya yang Tak Lekang Waktu',
            'deskripsi_singkat'  => 'Wisata Binangun menghadirkan pengalaman pedesaan yang otentik.',
            'alamat'             => 'Desa Binangun, Kec. Pandaan, Kab. Pasuruan, Jawa Timur',
            'no_whatsapp'        => '',
            'email_kontak'       => '',
            'instagram_url'      => '',
            'tiktok_url'         => '',
            'facebook_url'       => '',
            'google_maps_embed'  => '',
            'logo'               => null,
        ];

        try {
            $row = $this->find(1);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal baca pengaturan master: ' . $e->getMessage());

            return $fallback;
        }

        if (! is_array($row)) {
            return $fallback;
        }

        return array_merge($fallback, $row);
    }
}
