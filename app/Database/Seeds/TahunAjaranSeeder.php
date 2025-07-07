<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'year'   => '2025/2026',
            'status' => 'Aktif',
        ];
        $this->db->table('academic_years')->insert($data);
    }
}