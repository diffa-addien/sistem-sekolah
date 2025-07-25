<?php

namespace App\Validation;

use App\Models\ActivityNameModel;
use App\Models\TahunAjaranModel;

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

    /**
     * Cek jika kegiatan sudah dicatat untuk siswa pada tanggal yang sama.
     * Rule: is_activity_recorded[student_id,activity_date]
     */
    public function is_activity_recorded(string $str, string $fields, array $data): bool
    {
        // $str di sini adalah activity_name_id
        $activity_name_id = $str;

        // Ambil student_id dan activity_date dari data form
        $student_id = $data['student_id'];
        $activity_date = $data['activity_date'];

        // Jika salah satu data kunci tidak ada, loloskan saja (validasi lain akan menangani)
        if (empty($student_id) || empty($activity_date) || empty($activity_name_id)) {
            return true;
        }

        $model = new \App\Models\KegiatanModel();

        $existing = $model->where([
            'student_id' => $student_id,
            'activity_name_id' => $activity_name_id,
            'activity_date' => $activity_date,
        ])->first();

        // Validasi gagal jika data sudah ada
        return $existing === null;
    }

    public function gte_date(string $str, string $field, array $data): bool
    {
        // $str adalah nilai dari kolom saat ini (end_date)
        // $field adalah nama kolom pembanding (start_date)
        if (!isset($data[$field])) {
            return false;
        }

        // Bandingkan menggunakan strtotime()
        return strtotime($str) >= strtotime($data[$field]);
    }
    
    public function is_date_range_conflict(string $str, string $fields, array $data): bool
    {
        // $str di sini adalah end_date, kita butuh start_date juga dari $data
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];

        // Jika tanggal tidak valid, loloskan saja (validasi lain akan menangani)
        if (empty($startDate) || empty($endDate) || strtotime($startDate) >= strtotime($endDate)) {
            return true;
        }

        list($idToIgnore) = explode(',', $fields);

        $model = new TahunAjaranModel();

        $conflict = $model->where('id !=', $idToIgnore)
            ->groupStart()
            ->where('start_date <', $endDate)
            ->where('end_date >', $startDate)
            ->groupEnd()
            ->first();

        return $conflict === null; // Return true (valid) jika tidak ada konflik
    }
}