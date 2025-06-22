<?php

namespace App\Models;

use CodeIgniter\Model;

class KehadiranModel extends Model
{
    protected $table            = 'attendances';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = ['student_id', 'attendance_date', 'status', 'description'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // !! TAMBAHKAN METHOD BARU INI !!
    public function saveAttendance(int $student_id, string $date, string $status)
    {
        // 1. Cek apakah data sudah ada
        $existing = $this->where([
            'student_id'      => $student_id,
            'attendance_date' => $date
        ])->first();

        if ($existing) {
            // 2. Jika ada, UPDATE
            // Kita gunakan ID dari data yang ada untuk update
            return $this->update($existing['id'], ['status' => $status]);
        } else {
            // 3. Jika tidak ada, INSERT
            return $this->insert([
                'student_id'      => $student_id,
                'attendance_date' => $date,
                'status'          => $status,
            ]);
        }
    }
}