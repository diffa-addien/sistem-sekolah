<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $activeYear = $this->db->table('academic_years')->where('status', 'Aktif')->get()->getRowArray();
        $teachers = $this->db->table('users')->where('role', 'Guru')->limit(6)->get()->getResultArray();

        if (!$activeYear || count($teachers) < 6) {
            echo "Pastikan ada 1 tahun ajaran aktif dan minimal 6 guru.\n";
            return;
        }

        $classes = [];
        $classNames = ['1A', '2A', '3A', '4A', '5A', '6A']; // Nama kelas tanpa prefix
        foreach ($classNames as $index => $name) {
            $classes[] = [
                'name' => $name,
                'academic_year_id' => $activeYear['id'],
                'teacher_id' => $teachers[$index]['id'],
            ];
        }

        $this->db->table('classes')->insertBatch($classes);
    }
}