<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\KehadiranModel;
use App\Models\TahunAjaranModel;
use App\Models\SiswaModel; // Tambahkan
use App\Models\KegiatanModel;
use App\Models\EnrollmentModel;
use App\Models\ActivityNameModel;


class LaporanController extends BaseController
{
    public function kehadiran()
    {
        $kelasModel = new KelasModel();
        $kehadiranModel = new KehadiranModel();
        $enrollmentModel = new EnrollmentModel();
        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();

        // Ambil filter dari URL
        $class_id = $this->request->getGet('class_id');
        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');

        $data = [
            'classes' => $activeYear ? $kelasModel->where('academic_year_id', $activeYear['id'])->findAll() : [],
            'selected_class_id' => $class_id,
            'selected_month' => $month,
            'selected_year' => $year,
            'reportData' => [],
            'dateHeaders' => []
        ];

        if ($class_id) {
            // 1. Ambil dulu daftar siswa yang terdaftar di kelas ini
            $students_in_class = $enrollmentModel
                ->select('students.id, students.full_name, students.nis')
                ->join('students', 'students.id = enrollments.student_id')
                ->where('enrollments.class_id', $class_id)
                ->findAll();

            if (!empty($students_in_class)) {
                $student_ids = array_column($students_in_class, 'id');

                // 2. Ambil data kehadiran untuk semua siswa tersebut pada periode yang dipilih
                $raw_data = $kehadiranModel
                    ->whereIn('student_id', $student_ids)
                    ->where('MONTH(attendance_date)', $month)
                    ->where('YEAR(attendance_date)', $year)
                    ->findAll();

                // 3. Proses data menjadi format pivot
                $pivotedData = [];
                // Inisialisasi dengan semua siswa di kelas agar siswa yang alfa tetap muncul
                foreach ($students_in_class as $student) {
                    $pivotedData[$student['nis']]['full_name'] = $student['full_name'];
                    $pivotedData[$student['nis']]['attendances'] = [];
                }
                foreach ($raw_data as $row) {
                    // Cari nis siswa berdasarkan id
                    $student_nis = '';
                    foreach ($students_in_class as $student) {
                        if ($student['id'] == $row['student_id']) {
                            $student_nis = $student['nis'];
                            break;
                        }
                    }
                    if ($student_nis) {
                        $pivotedData[$student_nis]['attendances'][$row['attendance_date']] = $row['status'];
                    }
                }
                $data['reportData'] = $pivotedData;
            }

            // 4. Buat header tanggal
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $data['dateHeaders'][] = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($d, 2, '0', STR_PAD_LEFT);
            }
        }

        return view('pages/laporan/kehadiran', $data);
    }

    public function kegiatanSiswaSelector()
    {
        $tahunAjaranModel = new TahunAjaranModel();

        // Kirim semua tahun ajaran ke view untuk filter pertama
        $data['academic_years'] = $tahunAjaranModel->orderBy('year', 'DESC')->findAll();

        return view('pages/laporan/kegiatan_selector', $data);
    }

    public function getClassesByYear($year_id)
    {
        $kelasModel = new \App\Models\KelasModel();
        $classes = $kelasModel->where('academic_year_id', $year_id)->orderBy('name', 'ASC')->findAll();
        return $this->response->setJSON($classes);
    }

    public function getStudentsByClass($class_id)
    {
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $students = $enrollmentModel->select('students.id, students.full_name')
            ->join('students', 'students.id = enrollments.student_id')
            ->where('enrollments.class_id', $class_id)->findAll();
        return $this->response->setJSON($students);
    }

    public function kegiatanSiswa($student_id)
    {
        $siswaModel = new SiswaModel();
        $kegiatanModel = new KegiatanModel();
        $enrollmentModel = new EnrollmentModel();
        $activityNameModel = new ActivityNameModel();

        $student = $siswaModel->find($student_id);
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Siswa tidak ditemukan.');
        }

        // Ambil riwayat pendaftaran siswa untuk filter
        $enrollment_history = $enrollmentModel
            ->select('enrollments.class_id, classes.name as class_name, academic_years.year as academic_year')
            ->join('classes', 'classes.id = enrollments.class_id')
            ->join('academic_years', 'academic_years.id = enrollments.academic_year_id')
            ->where('enrollments.student_id', $student_id)
            ->orderBy('academic_years.year', 'DESC')
            ->findAll();

        // Tentukan filter tanggal (selalu ada)
        $filter_class_id = $this->request->getGet('filter_class_id');
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');

        // Bangun query kegiatan
        $kegiatanQuery = $kegiatanModel
            ->where('student_id', $student['id'])
            ->where('activity_date >=', $start_date)
            ->where('activity_date <=', $end_date);

        if ($filter_class_id) {
            $kegiatanQuery->where('class_id', $filter_class_id);
        }

        $recorded_activities = $kegiatanQuery->findAll();

        // Proses data menjadi format pivot
        $processed_records = [];
        foreach ($recorded_activities as $rec) {
            $processed_records[$rec['activity_name_id']][$rec['activity_date']] = true;
        }

        // !! PERBAIKAN: Pastikan dateHeaders selalu dibuat !!
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

    public function laporanSiswa($student_id)
    {
        $siswaModel = new SiswaModel();
        $enrollmentModel = new EnrollmentModel();
        $kehadiranModel = new KehadiranModel();
        $kegiatanModel = new KegiatanModel();

        $student = $siswaModel->find($student_id);
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Siswa tidak ditemukan.');
        }

        // Ambil riwayat pendaftaran untuk filter
        $enrollment_history = $enrollmentModel
            ->select('enrollments.id, enrollments.academic_year_id, classes.name as class_name, academic_years.year as academic_year')
            ->join('classes', 'classes.id = enrollments.class_id')
            ->join('academic_years', 'academic_years.id = enrollments.academic_year_id')
            ->where('enrollments.student_id', $student_id)
            ->orderBy('academic_years.year', 'DESC')
            ->findAll();

        // Tentukan pendaftaran mana yang akan ditampilkan (berdasarkan filter atau default yang terbaru)
        $selected_enrollment_id = $this->request->getGet('enrollment_id') ?? ($enrollment_history[0]['id'] ?? null);

        $data = [
            'student' => $student,
            'enrollment_history' => $enrollment_history,
            'selected_enrollment_id' => $selected_enrollment_id,
            'attendances' => [],
            'activities_by_day' => [] // Variabel baru untuk kegiatan yang sudah dirangkum
        ];

        if ($selected_enrollment_id) {
            $selected_enrollment = null;
            foreach ($enrollment_history as $enroll) {
                if ($enroll['id'] == $selected_enrollment_id) {
                    $selected_enrollment = $enroll;
                    break;
                }
            }

            if ($selected_enrollment) {
                // !! PERBAIKAN QUERY KEHADIRAN !!
                $data['attendances'] = $kehadiranModel
                    ->where('student_id', $student_id)
                    ->where('academic_year_id', $selected_enrollment['academic_year_id'])
                    ->orderBy('attendance_date', 'DESC') // Urutkan dari terbaru
                    ->findAll();

                // Ambil data kegiatan mentah
                $raw_activities = $kegiatanModel
                    ->select('activities.*, activity_names.name as activity_name')
                    ->join('activity_names', 'activity_names.id = activities.activity_name_id')
                    ->where('student_id', $student_id)
                    ->where('academic_year_id', $selected_enrollment['academic_year_id'])
                    ->orderBy('activity_date', 'DESC')
                    ->findAll();

                // !! LOGIKA BARU: Rangkum kegiatan per hari !!
                $grouped_activities = [];
                foreach ($raw_activities as $act) {
                    $grouped_activities[$act['activity_date']][] = $act;
                }
                $data['activities_by_day'] = $grouped_activities;
            }
        }

        return view('pages/laporan/detail_siswa', $data);
    }

}