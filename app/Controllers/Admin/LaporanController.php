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
        $tahunAjaranModel = new TahunAjaranModel();

        // Ambil filter dari URL
        $class_id = $this->request->getGet('class_id');
        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');

        $data = [
            'classes' => $kelasModel->findAll(), // Ambil semua kelas untuk filter
            'selected_class_id' => $class_id,
            'selected_month' => $month,
            'selected_year' => $year,
            'reportData' => [],
            'dateHeaders' => []
        ];

        if ($class_id) {
            // Query sekarang mengambil data dari attendances yang sudah memiliki class_id
            $raw_data = $kehadiranModel
                ->select('students.full_name, students.nis, attendances.attendance_date, attendances.status')
                ->join('students', 'students.id = attendances.student_id')
                // !! PERBAIKAN UTAMA: Menggunakan attendances.class_id !!
                ->where('attendances.class_id', $class_id)
                ->where('MONTH(attendances.attendance_date)', $month)
                ->where('YEAR(attendances.attendance_date)', $year)
                ->orderBy('students.full_name', 'ASC')
                ->orderBy('attendances.attendance_date', 'ASC')
                ->findAll();

            // Proses data menjadi format pivot (logika ini tidak berubah)
            $pivotedData = [];
            foreach ($raw_data as $row) {
                $pivotedData[$row['nis']]['full_name'] = $row['full_name'];
                $pivotedData[$row['nis']]['attendances'][$row['attendance_date']] = $row['status'];
            }
            $data['reportData'] = $pivotedData;

            // Buat header tanggal untuk tabel (logika ini tidak berubah)
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
}