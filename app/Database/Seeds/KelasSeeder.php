<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        // Ambil ID tahun ajaran aktif
        $activeYear = $this->db->table('academic_years')->where('status', 'Aktif')->get()->getRowArray();
        
        // Ambil 6 guru yang sudah dibuat
        $teachers = $this->db->table('users')->where('role', 'Guru')->limit(6)->get()->getResultArray();

        if (!$activeYear || count($teachers) < 6) {
            echo "Pastikan ada 1 tahun ajaran aktif dan minimal 6 guru.\n";
            return;
        }

        $classes = [];
        for ($i = 1; $i <= 6; $i++) {
            $classes[] = [
                'name'              => 'Kelas ' . $i . 'A',
                'academic_year_id'  => $activeYear['id'],
                'teacher_id'        => $teachers[$i-1]['id'], // Assign guru ke-i ke kelas ke-i
            ];
        }

        $this->db->table('classes')->insertBatch($classes);
    }
}