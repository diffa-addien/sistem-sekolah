<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'     => 'Administrator',
            'username' => 'admin',
            'password' => password_hash('password123', PASSWORD_BCRYPT), // Password di-hash
            'role'     => 'Admin',
        ];

        // Using Query Builder
        $this->db->table('users')->insert($data);
    }
}