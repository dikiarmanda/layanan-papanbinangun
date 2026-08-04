<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['nama', 'email', 'no_hp', 'password'];
    protected $useTimestamps = true;

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Akun terdaftar = punya password.
     */
    public function findAkunByEmail(string $email): ?array
    {
        $row = $this->findByEmail($email);
        if (!$row || empty($row['password'])) {
            return null;
        }

        return $row;
    }

    /**
     * Buat pelanggan baru setiap checkout (guest); mudah di-upgrade nanti.
     *
     * @param array{nama:string,email:string,no_hp:string} $data
     */
    public function createGuest(array $data): int
    {
        $this->insert([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'no_hp' => $data['no_hp'],
        ]);

        return (int) $this->getInsertID();
    }

    /**
     * Selesaikan identitas checkout: pakai sesi login, atau buat guest.
     *
     * @param array{nama:string,email:string,no_hp:string} $data
     */
    public function resolveCheckout(array $data): int
    {
        $sessionId = (int) (session()->get('pelanggan_id') ?? 0);
        if ($sessionId > 0) {
            $existing = $this->find($sessionId);
            if ($existing) {
                $this->update($sessionId, [
                    'nama' => $data['nama'],
                    'email' => $data['email'],
                    'no_hp' => $data['no_hp'],
                ]);
                session()->set([
                    'pelanggan_nama' => $data['nama'],
                    'pelanggan_email' => $data['email'],
                ]);

                return $sessionId;
            }
        }

        return $this->createGuest($data);
    }

    /**
     * Daftarkan akun; reuse baris guest (tanpa password) jika email sama.
     *
     * @param array{nama:string,email:string,no_hp:string,password:string} $data
     */
    public function registerAkun(array $data): int
    {
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $existing = $this->findByEmail($data['email']);

        if ($existing) {
            if (!empty($existing['password'])) {
                throw new \RuntimeException('Email sudah terdaftar.');
            }

            $this->update((int) $existing['id'], [
                'nama' => $data['nama'],
                'no_hp' => $data['no_hp'],
                'password' => $hash,
            ]);

            return (int) $existing['id'];
        }

        $this->insert([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'no_hp' => $data['no_hp'],
            'password' => $hash,
        ]);

        return (int) $this->getInsertID();
    }
}
