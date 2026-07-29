<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seed akun admin default.
 *
 * Login:
 * - superadmin@papanbinangun.id / admin123
 * - admin@papanbinangun.id / admin123
 *
 * Ganti password sebelum production.
 */
class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $password = password_hash('admin123', PASSWORD_DEFAULT);

        $users = [
            [
                'nama'          => 'Super Admin',
                'email'         => 'superadmin@papanbinangun.id',
                'password'      => $password,
                'role'          => 'superadmin',
                'status'        => 'aktif',
                'last_login_at' => null,
            ],
            [
                'nama'          => 'Admin Desa',
                'email'         => 'admin@papanbinangun.id',
                'password'      => $password,
                'role'          => 'admin',
                'status'        => 'aktif',
                'last_login_at' => null,
            ],
        ];

        $table = $this->db->table('admin_users');

        foreach ($users as $user) {
            $existing = $table->where('email', $user['email'])->get()->getRowArray();

            if ($existing) {
                $table->where('id', (int) $existing['id'])->update([
                    'nama'       => $user['nama'],
                    'password'   => $user['password'],
                    'role'       => $user['role'],
                    'status'     => $user['status'],
                    'updated_at' => $now,
                ]);

                continue;
            }

            $table->insert(array_merge($user, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
