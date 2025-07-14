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
    
     protected $allowedFields    = ['student_id', 'attendance_date', 'status', 'description', 'check_in_time', 'check_out_time'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function saveAttendance(int $student_id, string $date, string $status, ?string $time_field = null, ?string $time_value = null)
    {
        $existing = $this->where([
            'student_id'      => $student_id,
            'attendance_date' => $date
        ])->first();

        // Siapkan data dasar untuk disimpan/diupdate
        $data = ['status' => $status];

        // Jika ada data waktu yang dikirim
        if ($time_field && $time_value) {
            // Jika data sudah ada, hanya update waktu jika kolom waktunya masih kosong
            if ($existing) {
                if (empty($existing[$time_field])) {
                    $data[$time_field] = $time_value;
                }
            } else {
                // Jika data belum ada, langsung masukkan waktu
                $data[$time_field] = $time_value;
            }
        }

        if ($existing) {
            // Jika data sudah ada, UPDATE
            return $this->update($existing['id'], $data);
        } else {
            // Jika belum ada, INSERT data baru
            $data['student_id'] = $student_id;
            $data['attendance_date'] = $date;
            return $this->insert($data);
        }
    }
}