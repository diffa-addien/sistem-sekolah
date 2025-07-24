<?php

namespace App\Models;

use CodeIgniter\Model;

class KehadiranModel extends Model
{
    protected $table = 'attendances';
    protected $primaryKey = 'id';
    protected $allowedFields = ['student_id', 'attendance_date', 'status', 'description', 'check_in_time', 'check_out_time'];
    protected $useTimestamps = true;

    // app/Models/KehadiranModel.php

    /**
     * Metode Upsert (Update atau Insert) yang lebih kuat.
     */
    public function saveOrUpdateAttendance(int $student_id, string $date, array $data)
    {
        $existing = $this->where([
            'student_id' => $student_id,
            'attendance_date' => $date
        ])->first();

        if ($existing) {
            // UPDATE: Hanya perbarui kolom yang dikirim dalam array $data
            return $this->update($existing['id'], $data);
        } else {
            // INSERT: Buat array data baru yang lengkap untuk baris baru
            $insertData = [
                'student_id' => $student_id,
                'attendance_date' => $date,
                'status' => $data['status'] ?? 'Hadir', // Jika status tdk diset, default ke Hadir
                'check_in_time' => $data['check_in_time'] ?? null,
                'check_out_time' => $data['check_out_time'] ?? null,
                'description' => $data['description'] ?? null
            ];
            return $this->insert($insertData);
        }
    }
}
