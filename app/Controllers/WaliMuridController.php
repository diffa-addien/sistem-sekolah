<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\ActivityNameModel;
use App\Models\KegiatanModel;

class WaliMuridController extends BaseController
{
    /**
     * Menampilkan halaman checklist kegiatan harian.
     */

     // !! TAMBAHKAN METHOD BARU INI !!
    public function dashboard()
    {
        $siswaModel = new SiswaModel();
        
        // Ambil data siswa berdasarkan user_id wali murid yang login dari session
        $student = $siswaModel
            ->select('students.*, classes.name as class_name')
            ->join('classes', 'classes.id = students.class_id', 'left')
            ->where('students.user_id', session()->get('user_id'))
            ->first();

        if (!$student) {
            // Tampilkan pesan jika tidak ada data siswa yang terhubung
             return view('wali/no_student_linked');
        }

        $data['student'] = $student;
        return view('wali/dashboard', $data);
    }
    
    public function index()
    {
        // === SIMULASI LOGIN WALI MURID ===
        // Karena belum ada sistem login, kita anggap wali murid yang login
        // memiliki user_id = 3 (sesuaikan dengan data user wali murid di database Anda).
        $parent_user_id = 2;
        // ===================================

        $siswaModel = new SiswaModel();
        $activityNameModel = new ActivityNameModel();
        $kegiatanModel = new KegiatanModel();

        // Cari data siswa yang terhubung dengan akun wali murid ini
        $student = $siswaModel->where('user_id', $parent_user_id)->first();
        if (!$student) {
            return "Tidak ada data siswa yang terhubung dengan akun wali murid ini. Silakan tautkan di menu Manajemen Siswa.";
        }

        // Ambil daftar kegiatan yang tipenya 'Rumah'
        $home_activities = $activityNameModel->where('type', 'Rumah')->findAll();

        // Ambil catatan kegiatan siswa ini dalam 7 hari terakhir
        $recorded_activities_raw = $kegiatanModel
            ->where('student_id', $student['id'])
            ->where('activity_date >=', date('Y-m-d', strtotime('-6 days')))
            ->findAll();

        // Proses data agar mudah diakses di view
        $recorded_activities = [];
        foreach ($recorded_activities_raw as $rec) {
            $recorded_activities[$rec['activity_date']][$rec['activity_name_id']] = $rec;
        }

        $data = [
            'student' => $student,
            'home_activities' => $home_activities,
            'recorded_activities' => $recorded_activities,
        ];

        return view('wali/kegiatan_harian', $data);
    }

    /**
     * API untuk menyimpan checklist (via AJAX).
     */
    public function saveActivity()
    {
        // Hanya izinkan request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $student_id = $this->request->getPost('student_id');
        $activity_name_id = $this->request->getPost('activity_name_id');
        $date = $this->request->getPost('date');
        $is_checked = $this->request->getPost('is_checked') === 'true';

        $kegiatanModel = new KegiatanModel();

        // Cari record yang ada
        $existing = $kegiatanModel->where([
            'student_id' => $student_id,
            'activity_name_id' => $activity_name_id,
            'activity_date' => $date
        ])->first();

        if ($is_checked) {
            // Jika dicentang & belum ada, INSERT
            if (!$existing) {
                $kegiatanModel->insert([
                    'student_id' => $student_id,
                    'activity_name_id' => $activity_name_id,
                    'activity_date' => $date
                ]);
            }
        } else {
            // Jika tidak dicentang, DELETE record jika ada
            if ($existing) {
                $kegiatanModel->delete($existing['id']);
            }
        }

        return $this->response->setJSON(['status' => 'success']);
    }
}
