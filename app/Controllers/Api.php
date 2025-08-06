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
        // Set zona waktu dan mulai buffer
        date_default_timezone_set('Asia/Jakarta');
        ob_start();
    }

    /**
     * Endpoint utama untuk Arduino. Menerima tap dan memprosesnya.
     */
    public function processTap()
    {
        try {
            $uid = $this->request->getPost('uid');
            if (!$uid) {
                ob_clean();
                return $this->response->setJSON(['status' => 'error', 'message' => 'UID Kosong']);
            }

            $siswaModel = new SiswaModel();
            $siswa = $siswaModel->where('card_uid', $uid)->first();
            if (!$siswa) {
                ob_clean();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Siswa Tdk Ditemukan']);
            }

            $now_time = date('H:i:s');
            $today_date = date('Y-m-d');

            $activityNameModel = new ActivityNameModel();
            $scheduled_activity = $activityNameModel
                ->where('start_time IS NOT NULL')
                ->where('end_time IS NOT NULL')
                ->where('start_time <=', $now_time)
                ->where('end_time >=', $now_time)
                ->first();

            $message = "Tidak ada jadwal";
            $operation_success = true;

            if ($scheduled_activity) {
                $activity_type = $scheduled_activity['type'];
                $kehadiranModel = new KehadiranModel();
                $existingAttendance = $kehadiranModel->where([
                    'student_id' => $siswa['id'],
                    'attendance_date' => $today_date
                ])->first();

                if ($activity_type == 'Masuk') {
                    if ($existingAttendance && $existingAttendance['check_in_time'] !== null) {
                        $message = "Sudah tercatat";
                        $operation_success = true;
                    } else {
                        $dataToSave = [
                            'status' => 'Hadir',
                            'check_in_time' => $now_time
                        ];
                        if ($kehadiranModel->saveOrUpdateAttendance($siswa['id'], $today_date, $dataToSave)) {
                            $operation_success = true;
                            $message = "Presensi Masuk";
                        }
                    }
                } elseif ($activity_type == 'Pulang') {
                    if ($existingAttendance && $existingAttendance['check_out_time'] !== null) {
                        $message = "Sudah tercatat";
                        $operation_success = true;
                    } else {
                        $dataToSave = [
                            'status' => 'Hadir',
                            'check_out_time' => $now_time
                        ];
                        if ($kehadiranModel->saveOrUpdateAttendance($siswa['id'], $today_date, $dataToSave)) {
                            $operation_success = true;
                            $message = "Presensi Pulang";
                        }
                    }
                } elseif ($activity_type == 'Sekolah') {
                    $kegiatanModel = new KegiatanModel();
                    $alreadyExists = $kegiatanModel->where([
                        'student_id' => $siswa['id'],
                        'activity_name_id' => $scheduled_activity['id'],
                        'activity_date' => $today_date
                    ])->first();

                    if ($alreadyExists) {
                        $message = "Sudah tercatat";
                        $operation_success = true;
                    } else {
                        $dataToSave = [
                            'student_id' => $siswa['id'],
                            'activity_name_id' => $scheduled_activity['id'],
                            'activity_date' => $today_date,
                            'description' => 'Presensi via RFID'
                        ];
                        if ($kegiatanModel->save($dataToSave)) {
                            $operation_success = true;
                            $message = "- " . $scheduled_activity['name'];
                        }
                    }
                }
            }

            ob_clean();
            if ($operation_success) {
                return $this->response->setJSON(['status' => 'success', 'message' => $message]);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Gagal Simpan DB']);
            }
        } catch (\Exception $e) {
            ob_clean();
            log_message('error', '[API] ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Error Server']);
        }
    }

    /**
     * Menerima UID dari alat scan dan menyimpannya ke file sementara.
     */
    public function storeScannedUid()
    {
        $uid = $this->request->getPost('uid');
        if (!$uid) {
            ob_clean();
            return $this->response->setJSON(['status' => 'error', 'message' => 'UID tidak valid']);
        }
        file_put_contents(WRITEPATH . 'latest_uid.txt', $uid);
        ob_clean();
        return $this->response->setJSON(['status' => 'success', 'message' => 'UID disimpan']);
    }

    /**
     * Dipanggil oleh JavaScript (polling) untuk mendapat UID terbaru.
     */
    public function checkScannedUid()
    {
        $file = WRITEPATH . 'latest_uid.txt';
        if (!file_exists($file) || filesize($file) === 0) {
            ob_clean();
            return $this->response->setJSON(['status' => 'empty']);
        }

        $uid = file_get_contents($file);
        $timestamp = filemtime($file);

        $siswaModel = new SiswaModel();
        if ($siswaModel->where('card_uid', $uid)->first()) {
            ob_clean();
            return $this->response->setJSON(['status' => 'error', 'message' => 'UID sudah terdaftar', 'timestamp' => $timestamp]);
        }

        file_put_contents($file, '');
        ob_clean();
        return $this->response->setJSON(['status' => 'success', 'uid' => $uid, 'timestamp' => $timestamp]);
    }
}