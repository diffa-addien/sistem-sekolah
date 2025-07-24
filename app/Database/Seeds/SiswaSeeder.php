<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $parents = $this->db->table('users')->where('role', 'Wali Murid')->limit(10)->get()->getResultArray();
        $activeYear = $this->db->table('academic_years')->where('status', 'Aktif')->get()->getRowArray();
        $classes = $this->db->table('classes')->where('academic_year_id', $activeYear['id'])->get()->getResultArray();

        if (count($parents) < 10 || empty($classes)) {
             echo "Pastikan ada minimal 10 wali murid dan ada kelas di tahun ajaran aktif.\n";
            return;
        }

        $students = [];
        for ($i = 1; $i <= 10; $i++) {
            $students[] = [
                'full_name'  => 'Siswa ' . $i,
                'nis'        => '100' . $i,
                'gender'     => ($i % 2 == 0) ? 'Perempuan' : 'Laki-laki',
                'birth_date' => date('Y-m-d', strtotime("-".(7+$i)." years")),
                'user_id'    => $parents[$i-1]['id'],
            ];
        }
        $this->db->table('students')->insertBatch($students);

        // Setelah siswa dibuat, buat data pendaftaran (enrollment) mereka
        $newly_created_students = $this->db->table('students')->orderBy('id', 'DESC')->limit(10)->get()->getResultArray();
        $enrollments = [];
        foreach($newly_created_students as $key => $student) {
            $enrollments[] = [
                'student_id'       => $student['id'],
                'class_id'         => $classes[$key % count($classes)]['id'], // Distribusikan siswa ke kelas
                'academic_year_id' => $activeYear['id'],
                'status'           => 'Aktif',
            ];
        }
        $this->db->table('enrollments')->insertBatch($enrollments);
    }
}