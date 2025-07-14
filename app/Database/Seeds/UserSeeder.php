<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $hashed_password = password_hash('testing', PASSWORD_BCRYPT);
        $users = [];

        // 1 Admin
        $users[] = [
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => $hashed_password,
            'role' => 'Admin',
        ];

        // 6 Guru
        for ($i = 1; $i <= 6; $i++) {
            $users[] = [
                'name' => 'Guru ' . $i,
                'username' => 'guru' . $i,
                'password' => $hashed_password,
                'role' => 'Guru',
            ];
        }

        // 10 Wali Murid
        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'name' => 'Wali Murid ' . $i,
                'username' => 'wali' . $i,
                'password' => $hashed_password,
                'role' => 'Wali Murid',
            ];
        }

        $this->db->table('users')->insertBatch($users);
    }
}