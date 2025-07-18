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
        try {
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

            // Cari pendaftaran & kelas siswa saat ini di tahun ajaran aktif
            $activeYear = (new \App\Models\TahunAjaranModel())->where('status', 'Aktif')->first();
            if (!$activeYear) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'T/A Tidak Aktif']);
            }

            $enrollment = (new \App\Models\EnrollmentModel())->where(['student_id' => $siswa['id'], 'academic_year_id' => $activeYear['id']])->first();
            if (!$enrollment) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Tdk Terdaftar di Kelas']);
            }

            // Siapkan data konteks (kelas & tahun ajaran) untuk disimpan
            $context = [
                'class_id' => $enrollment['class_id'],
                'academic_year_id' => $activeYear['id'],
            ];

            $activityNameModel = new ActivityNameModel();
            $scheduled_activity = $activityNameModel->where('start_time IS NOT NULL')->where('end_time IS NOT NULL')->where('start_time <=', $now_time)->where('end_time >=', $now_time)->first();

            $message = "Tap Diterima";
            $operation_success = false;

            if ($scheduled_activity) {
                $activity_type = $scheduled_activity['type'];
                $message = $scheduled_activity['name'];

                if ($activity_type == 'Masuk' || $activity_type == 'Pulang') {
                    $kehadiranModel = new KehadiranModel();
                    $time_field = ($activity_type == 'Masuk') ? 'check_in_time' : 'check_out_time';
                    $dataToSave = array_merge($context, ['status' => 'Hadir', $time_field => $now_time]);
                    if ($kehadiranModel->saveOrUpdateAttendance($siswa['id'], $today_date, $dataToSave)) {
                        $operation_success = true;
                    }
                } elseif ($activity_type == 'Sekolah') {
                    $kegiatanModel = new KegiatanModel();
                    $alreadyExists = $kegiatanModel->where(['student_id' => $siswa['id'], 'activity_name_id' => $scheduled_activity['id'], 'activity_date' => $today_date])->first();

                    if (empty($alreadyExists)) {
                        $dataToSave = array_merge($context, ['student_id' => $siswa['id'], 'activity_name_id' => $scheduled_activity['id'], 'activity_date' => $today_date, 'description' => 'Presensi via RFID']);
                        if ($kegiatanModel->save($dataToSave)) {
                            $operation_success = true;
                        }
                    } else {
                        $message = "Sudah tercatat";
                        $operation_success = true;
                    }
                }
            } else {
                $kehadiranModel = new KehadiranModel();
                $dataToSave = array_merge($context, ['status' => 'Hadir']);
                if ($kehadiranModel->saveOrUpdateAttendance($siswa['id'], $today_date, $dataToSave)) {
                    $operation_success = true;
                    $message = 'Hadir Umum';
                }
            }

            if ($operation_success) {
                return $this->response->setJSON(['status' => 'success', 'message' => $message]);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Gagal Simpan DB']);
            }

        } catch (\Exception $e) {
            log_message('error', '[API] ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Error Server']);
        }
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
