<?php

namespace App\Validation;

use App\Models\ActivityNameModel;

class CustomRules
{
    /**
     * Cek jika ada jadwal yang konflik.
     * Rule: is_schedule_conflict[id_to_ignore]
     */
    public function is_schedule_conflict(string $str, string $fields, array $data): bool
    {
        $model = new ActivityNameModel();
        
        // Ambil start_time dan end_time dari data yang disubmit
        $startTime = $data['start_time'];
        $endTime = $data['end_time'];

        // Jika salah satu waktu kosong, anggap tidak ada konflik (jadwal tidak diatur)
        if (empty($startTime) || empty($endTime)) {
            return true;
        }

        // Ambil ID yang sedang di-edit (jika ada) untuk diabaikan
        list($idToIgnore) = explode(',', $fields);

        $query = $model->where('id !=', $idToIgnore)
                       ->groupStart()
                           ->where('start_time <', $endTime)
                           ->where('end_time >', $startTime)
                       ->groupEnd();
        
        $conflict = $query->first();

        return $conflict === null;
    }
}