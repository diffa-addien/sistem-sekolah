<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\ActivityNameModel;
use App\Models\KegiatanModel;
use App\Models\KehadiranModel;
use App\Models\EnrollmentModel;

class WaliMuridController extends BaseController
{
    public function dashboard()
    {
        $siswaModel = new SiswaModel();
        $kehadiranModel = new KehadiranModel();
        $enrollmentModel = new EnrollmentModel();

        $parent_user_id = session()->get('user_id');

        // Cari data siswa yang terhubung dengan akun wali murid ini
        $student = $siswaModel->where('user_id', $parent_user_id)->first();
        if (!$student) {
            return view('wali/no_student_linked');
        }

        // !! LOGIKA BARU: Cari kelas siswa di tahun ajaran aktif !!
        $activeYear = (new \App\Models\TahunAjaranModel())->where('status', 'Aktif')->first();
        $current_enrollment = null;
        if ($activeYear) {
            $current_enrollment = $enrollmentModel
                ->select('classes.name as class_name')
                ->join('classes', 'classes.id = enrollments.class_id')
                ->where('enrollments.student_id', $student['id'])
                ->where('enrollments.academic_year_id', $activeYear['id'])
                ->first();
        }

        // Gabungkan nama kelas ke data siswa
        $student['class_name'] = $current_enrollment['class_name'] ?? null;

        $todays_attendance = $kehadiranModel->where([
            'student_id' => $student['id'],
            'attendance_date' => date('Y-m-d')
        ])->first();

        $data['student'] = $student;
        $data['todays_attendance'] = $todays_attendance;

        return view('wali/dashboard', $data);
    }

    public function index()
    {
        $parent_user_id = session()->get('user_id');

        $siswaModel = new SiswaModel();
        $activityNameModel = new ActivityNameModel();
        $kegiatanModel = new KegiatanModel();

        $student = $siswaModel->where('user_id', $parent_user_id)->first();
        if (!$student) {
            return "Tidak ada data siswa yang terhubung dengan akun wali murid ini. Silakan tautkan di menu Manajemen Siswa.";
        }

        $home_activities = $activityNameModel->where('type', 'Rumah')->findAll();

        $recorded_activities_raw = $kegiatanModel
            ->where('student_id', $student['id'])
            ->where('activity_date >=', date('Y-m-d', strtotime('-6 days')))
            ->findAll();

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

    public function laporanKehadiran()
    {
        $siswaModel = new SiswaModel();
        $kehadiranModel = new KehadiranModel();

        $student = $siswaModel->where('user_id', session()->get('user_id'))->first();
        if (!$student) {
            return view('wali/no_student_linked');
        }

        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');

        $attendances_raw = $kehadiranModel
            ->where('student_id', $student['id'])
            ->where('MONTH(attendance_date)', $month)
            ->where('YEAR(attendance_date)', $year)
            ->findAll();

        $attendances = [];
        foreach ($attendances_raw as $att) {
            $attendances[$att['attendance_date']] = $att;
        }

        $data = [
            'student' => $student,
            'attendances' => $attendances,
            'selected_month' => $month,
            'selected_year' => $year,
        ];

        return view('wali/laporan_kehadiran', $data);
    }

    public function laporanKegiatan()
    {
        $siswaModel = new SiswaModel();
        $kegiatanModel = new KegiatanModel();
        $enrollmentModel = new EnrollmentModel();
        $activityNameModel = new ActivityNameModel();

        $student = $siswaModel->where('user_id', session()->get('user_id'))->first();
        if (!$student) {
            return view('wali/no_student_linked');
        }

        $enrollment_history = $enrollmentModel
            ->select('enrollments.class_id, classes.name as class_name, academic_years.year as academic_year')
            ->join('classes', 'classes.id = enrollments.class_id')
            ->join('academic_years', 'academic_years.id = enrollments.academic_year_id')
            ->where('enrollments.student_id', $student['id'])
            ->orderBy('academic_years.year', 'DESC')
            ->findAll();

        $filter_class_id = $this->request->getGet('filter_class_id');
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');

        $kegiatanQuery = $kegiatanModel
            ->where('student_id', $student['id'])
            ->where('activity_date >=', $start_date)
            ->where('activity_date <=', $end_date);

        if ($filter_class_id) {
            $kegiatanQuery->where('class_id', $filter_class_id);
        }

        $recorded_activities = $kegiatanQuery
            ->select('activities.*, activity_names.name as activity_name, activity_names.type')
            ->join('activity_names', 'activity_names.id = activities.activity_name_id')
            ->orderBy('activities.activity_date', 'DESC')
            ->findAll();

        $processed_records = [];
        foreach ($recorded_activities as $rec) {
            $processed_records[$rec['activity_name_id']][$rec['activity_date']] = true;
        }

        $dateHeaders = [];
        $period = new \DatePeriod(new \DateTime($start_date), new \DateInterval('P1D'), (new \DateTime($end_date))->modify('+1 day'));
        foreach ($period as $value) {
            $dateHeaders[] = $value->format('Y-m-d');
        }

        $data = [
            'student' => $student,
            'activity_names' => $activityNameModel->findAll(),
            'processed_records' => $processed_records,
            'dateHeaders' => $dateHeaders,
            'enrollment_history' => $enrollment_history,
            'selected_class_id' => $filter_class_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        return view('pages/laporan/kegiatan_siswa', $data);
    }

    public function saveActivity()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        try {
            $student_id = $this->request->getPost('student_id');
            $activity_name_id = $this->request->getPost('activity_name_id');
            $date = $this->request->getPost('date');
            $is_checked = $this->request->getPost('is_checked') === 'true';

            if (empty($student_id) || empty($activity_name_id) || empty($date)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap.']);
            }

            $kegiatanModel = new \App\Models\KegiatanModel();

            if ($is_checked) {
                $existing = $kegiatanModel->where(['student_id' => $student_id, 'activity_name_id' => $activity_name_id, 'activity_date' => $date])->first();

                if ($existing) {
                    return $this->response->setJSON(['status' => 'success']); // Data sudah ada
                }

                // !! LOGIKA BARU: Cari pendaftaran (enrollment) TERAKHIR siswa !!
                $latestEnrollment = (new \App\Models\EnrollmentModel())
                    ->join('academic_years', 'academic_years.id = enrollments.academic_year_id')
                    ->where('student_id', $student_id)
                    ->orderBy('academic_years.year', 'DESC')
                    ->select('enrollments.class_id, enrollments.academic_year_id')
                    ->first();

                if (!$latestEnrollment) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Siswa belum pernah terdaftar di kelas manapun.']);
                }

                $dataToSave = [
                    'student_id' => $student_id,
                    'activity_name_id' => $activity_name_id,
                    'activity_date' => $date,
                    'class_id' => $latestEnrollment['class_id'],
                    'academic_year_id' => $latestEnrollment['academic_year_id']
                ];

                if (!$kegiatanModel->insert($dataToSave)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
                }

            } else {
                // Hapus data jika centang dihilangkan
                $kegiatanModel->where([
                    'student_id' => $student_id,
                    'activity_name_id' => $activity_name_id,
                    'activity_date' => $date
                ])->delete();
            }

            return $this->response->setJSON(['status' => 'success']);

        } catch (\Exception $e) {
            log_message('error', '[saveActivity] ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan pada server.']);
        }
    }

    // app/Controllers/WaliMuridController.php
    public function laporanSiswa()
    {
        $student = (new \App\Models\SiswaModel())->where('user_id', session()->get('user_id'))->first();
        if (!$student) {
            return view('wali/no_student_linked');
        }

        // Panggil method dari LaporanController, TAPI inisialisasi dulu controllernya
        $laporanController = new \App\Controllers\Admin\LaporanController();
        $laporanController->initController(
            \Config\Services::request(),
            \Config\Services::response(),
            \Config\Services::logger()
        );

        return $laporanController->laporanSiswa($student['id']);
    }
}