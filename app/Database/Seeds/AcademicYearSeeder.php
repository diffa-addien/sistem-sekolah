<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'year'   => '2025/2026', // Sesuaikan jika perlu
            'status' => 'Aktif',
        ];

        // Using Query Builder
        $this->db->table('academic_years')->insert($data);
    }
}