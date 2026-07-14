<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table            = 'pelanggan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama', 'email', 'no_hp', 'password'];
    protected $useTimestamps    = true;

    /**
     * Buat pelanggan baru setiap checkout (guest); mudah di-upgrade nanti.
     *
     * @param array{nama:string,email:string,no_hp:string} $data
     */
    public function createGuest(array $data): int
    {
        $this->insert([
            'nama'  => $data['nama'],
            'email' => $data['email'],
            'no_hp' => $data['no_hp'],
        ]);

        return (int) $this->getInsertID();
    }
}
