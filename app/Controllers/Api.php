<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KehadiranModel;
use App\Models\KegiatanModel;
use App\Models\ActivityNameModel;

class Api extends BaseController
{
    public function __construct()
    {
        // Set zona waktu di awal
        date_default_timezone_set('Asia/Jakarta');
    }

    /**
     * Endpoint utama untuk Arduino. Menerima tap dan memprosesnya.
     */
    public function processTap()
    {
        $uid = $this->request->getPost('uid');
        if (!$uid) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'UID Kosong']);
        }

        $siswaModel = new SiswaModel();
        $siswa = $siswaModel->where('card_uid', $uid)->first();
        if (!$siswa) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Siswa Tdk Ditemukan']);
        }

        $now_time = date('H:i:s');
        $today_date = date('Y-m-d');

        $activityNameModel = new ActivityNameModel();
        $scheduled_activity = $activityNameModel
            ->where('start_time <=', $now_time)
            ->where('end_time >=', $now_time)
            ->first();

        $message = "Tap Diterima";

        if ($scheduled_activity) {
            $activity_type = $scheduled_activity['type'];
            $message = $scheduled_activity['name'];

            if ($activity_type == 'Masuk' || $activity_type == 'Pulang') {
                $kehadiranModel = new KehadiranModel();
                $status_kehadiran = ($activity_type == 'Masuk') ? 'Hadir' : 'Pulang';
                // !! PERUBAHAN: Kirim nama kolom waktu dan nilainya ke model !!
                $time_field = ($activity_type == 'Masuk') ? 'check_in_time' : 'check_out_time';
                $kehadiranModel->saveAttendance($siswa['id'], $today_date, $status_kehadiran, $time_field, $now_time);

            } else if ($activity_type == 'Sekolah') {
                $kegiatanModel = new KegiatanModel();
                // ... (logika kegiatan sekolah tidak berubah) ...
            }
        } else {
            // Jika tidak ada jadwal, catat sebagai kehadiran 'Hadir' tanpa waktu spesifik
            $kehadiranModel = new KehadiranModel();
            $kehadiranModel->saveAttendance($siswa['id'], $today_date, 'Hadir');
            $message = 'Hadir Umum';
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $message,
            'student_name' => $siswa['full_name']
        ]);
    }

    /**
     * Menerima UID dari alat scan dan menyimpannya ke file sementara.
     * Hanya untuk interaksi dengan form web.
     */
    public function storeScannedUid()
    {
        $uid = $this->request->getPost('uid');
        if (!$uid) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'UID tidak valid']);
        }
        file_put_contents(WRITEPATH . 'latest_uid.txt', $uid);
        return $this->response->setJSON(['status' => 'success', 'message' => 'UID disimpan']);
    }

    /**
     * Dipanggil oleh JavaScript (polling) dari form web untuk mendapat UID terbaru.
     */
    public function checkScannedUid()
    {
        $file = WRITEPATH . 'latest_uid.txt';
        if (!file_exists($file) || filesize($file) === 0) {
            return $this->response->setJSON(['status' => 'empty']);
        }

        $uid = file_get_contents($file);
        $timestamp = filemtime($file);

        // Cek apakah UID sudah terdaftar
        $siswaModel = new SiswaModel();
        if ($siswaModel->where('card_uid', $uid)->first()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'UID sudah terdaftar', 'timestamp' => $timestamp]);
        }

        // Kosongkan file setelah dibaca agar tidak terbaca lagi
        file_put_contents($file, '');

        return $this->response->setJSON(['status' => 'success', 'uid' => $uid, 'timestamp' => $timestamp]);
    }
}