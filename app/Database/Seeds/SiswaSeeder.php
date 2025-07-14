<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        // Ambil 10 wali murid
        $parents = $this->db->table('users')->where('role', 'Wali Murid')->limit(10)->get()->getResultArray();
        
        // Ambil 6 kelas
        $classes = $this->db->table('classes')->limit(6)->get()->getResultArray();

        if (count($parents) < 10 || count($classes) < 6) {
             echo "Pastikan ada minimal 10 wali murid dan 6 kelas.\n";
            return;
        }

        $students = [];
        for ($i = 1; $i <= 10; $i++) {
            $students[] = [
                'full_name'  => 'Siswa ' . $i,
                'nis'        => '100' . $i,
                'gender'     => ($i % 2 == 0) ? 'Perempuan' : 'Laki-laki',
                'birth_date' => date('Y-m-d', strtotime("-".(7+$i)." years")), // Umur antara 8-17
                'class_id'   => $classes[($i - 1) % count($classes)]['id'], // Distribusikan siswa ke kelas
                'user_id'    => $parents[$i-1]['id'], // Tautkan siswa ke-i ke wali ke-i
                'status'     => 'Aktif',
            ];
        }

        $this->db->table('students')->insertBatch($students);
    }
}