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
        $kelasModel = new \App\Models\KelasModel();
        $kehadiranModel = new \App\Models\KehadiranModel();
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $activeYear = (new \App\Models\TahunAjaranModel())->where('status', 'Aktif')->first();

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
            $students_in_class = $enrollmentModel
                ->select('students.id, students.full_name, students.nis')
                ->join('students', 'students.id = enrollments.student_id')
                ->where('enrollments.class_id', $class_id)->findAll();

            if (!empty($students_in_class)) {
                $student_ids = array_column($students_in_class, 'id');
                $raw_data = $kehadiranModel->whereIn('student_id', $student_ids)->where('MONTH(attendance_date)', $month)->where('YEAR(attendance_date)', $year)->findAll();

                $pivotedData = [];
                foreach ($students_in_class as $student) {
                    $pivotedData[$student['nis']]['full_name'] = $student['full_name'];
                    $pivotedData[$student['nis']]['student_id'] = $student['id'];
                    $pivotedData[$student['nis']]['attendances'] = [];
                }
                foreach ($raw_data as $row) {
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

    public function laporanSiswa($student_id, $filter_params = [])
    {
        $siswaModel = new \App\Models\SiswaModel();
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $kehadiranModel = new \App\Models\KehadiranModel();
        $kegiatanModel = new \App\Models\KegiatanModel();
        $activityNameModel = new \App\Models\ActivityNameModel();
        $tahunAjaranModel = new \App\Models\TahunAjaranModel();

        $student = $siswaModel->find($student_id);
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Siswa tidak ditemukan.');
        }

        $enrollment_history = $enrollmentModel
            // !! PERBAIKAN: Tambahkan enrollments.class_id di sini !!
            ->select('enrollments.id, enrollments.academic_year_id, enrollments.class_id, classes.name as class_name, academic_years.year as academic_year')
            ->join('classes', 'classes.id = enrollments.class_id')
            ->join('academic_years', 'academic_years.id = enrollments.academic_year_id')
            ->where('enrollments.student_id', $student_id)
            ->orderBy('academic_years.year', 'DESC')
            ->findAll();

        if (empty($enrollment_history)) {
            return redirect()->to('admin/siswa')->with('error', 'Siswa "' . esc($student['full_name']) . '" belum memiliki riwayat pendaftaran kelas.');
        }

        $selected_enrollment_id = $filter_params['enrollment_id'] ?? $this->request->getGet('enrollment_id') ?? ($enrollment_history[0]['id'] ?? null);

        $data = [
            'student' => $student,
            'enrollment_history' => $enrollment_history,
            'selected_enrollment_id' => $selected_enrollment_id,
            'attendances' => [],
            'activities_by_day' => [],
            'summary' => ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'kegiatan' => 0, 'hari_aktif_tertinggi' => 0]
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
                $academicYearData = $tahunAjaranModel->find($selected_enrollment['academic_year_id']);

                if ($academicYearData && !empty($academicYearData['start_date']) && !empty($academicYearData['end_date'])) {
                    $start_date = $academicYearData['start_date'];
                    $end_date = $academicYearData['end_date'];

                    $attendances = $kehadiranModel
                        ->where('student_id', $student_id)
                        ->where('attendance_date >=', $start_date)
                        ->where('attendance_date <=', $end_date)
                        ->orderBy('attendance_date', 'DESC')
                        ->findAll();
                    $data['attendances'] = $attendances;

                    $raw_activities = $kegiatanModel
                        ->select('activities.*, activity_names.name as activity_name')
                        ->join('activity_names', 'activity_names.id = activities.activity_name_id')
                        ->where('student_id', $student_id)
                        ->where('activity_date >=', $start_date)
                        ->where('activity_date <=', $end_date)
                        ->orderBy('activity_date', 'DESC')
                        ->findAll();

                    $attendanceCounts = array_count_values(array_column($attendances, 'status'));
                    $data['summary']['hadir'] = $attendanceCounts['Hadir'] ?? 0;
                    $data['summary']['sakit'] = $attendanceCounts['Sakit'] ?? 0;
                    $data['summary']['izin'] = $attendanceCounts['Izin'] ?? 0;
                    $data['summary']['kegiatan'] = count($raw_activities);

                    $students_in_same_class = $enrollmentModel->where('class_id', $selected_enrollment['class_id'])->findAll();
                    if (!empty($students_in_same_class)) {
                        $student_ids_in_class = array_column($students_in_same_class, 'student_id');
                        $highestAttendance = $kehadiranModel->select('COUNT(id) as total_days')->whereIn('student_id', $student_ids_in_class)->where('attendance_date >=', $start_date)->where('attendance_date <=', $end_date)->groupBy('student_id')->orderBy('total_days', 'DESC')->limit(1)->first();
                        $data['summary']['hari_aktif_tertinggi'] = $highestAttendance ? $highestAttendance['total_days'] : 0;
                    }

                    $grouped_activities = [];
                    foreach ($raw_activities as $act) {
                        $grouped_activities[$act['activity_date']][] = $act;
                    }
                    $data['activities_by_day'] = $grouped_activities;
                }
            }
        }
        return view('pages/laporan/detail_siswa', $data);
    }
}
