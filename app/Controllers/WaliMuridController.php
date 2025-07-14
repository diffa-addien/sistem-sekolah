<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\ActivityNameModel;
use App\Models\KegiatanModel;
use App\Models\KehadiranModel;

class WaliMuridController extends BaseController
{
    /**
     * Menampilkan halaman checklist kegiatan harian.
     */

    // !! TAMBAHKAN METHOD BARU INI !!
    public function dashboard()
    {
        $siswaModel = new SiswaModel();
        $kehadiranModel = new KehadiranModel(); // Panggil KehadiranModel

        // Ambil data siswa berdasarkan user_id wali murid yang login dari session
        $student = $siswaModel
            ->select('students.*, classes.name as class_name')
            ->join('classes', 'classes.id = students.class_id', 'left')
            ->where('students.user_id', session()->get('user_id'))
            ->first();

        if (!$student) {
            return view('wali/no_student_linked');
        }

        // !! LOGIKA BARU: Ambil data kehadiran hari ini !!
        $todays_attendance = $kehadiranModel->where([
            'student_id' => $student['id'],
            'attendance_date' => date('Y-m-d')
        ])->first();

        $data['student'] = $student;
        $data['todays_attendance'] = $todays_attendance; // Kirim data ke view

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
            return "Tidak ada data siswa yang terhubung dengan akun wali murid ini.";
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

    public function laporanKehadiran()
    {
        $siswaModel = new SiswaModel();
        $kehadiranModel = new KehadiranModel();

        $student = $siswaModel->where('user_id', session()->get('user_id'))->first();
        if (!$student) {
            return view('wali/no_student_linked');
        }

        // Ambil filter bulan dan tahun, default ke bulan ini
        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');

        // Ambil semua data kehadiran siswa pada bulan dan tahun yang dipilih
        $attendances_raw = $kehadiranModel
            ->where('student_id', $student['id'])
            ->where('MONTH(attendance_date)', $month)
            ->where('YEAR(attendance_date)', $year)
            ->findAll();

        // Proses data agar mudah diakses di view dengan key tanggal
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
        $activityNameModel = new ActivityNameModel();

        $student = $siswaModel->where('user_id', session()->get('user_id'))->first();
        if (!$student) {
            return view('wali/no_student_linked');
        }

        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');

        // Logika yang sama persis dengan di LaporanController
        $recorded_activities = $kegiatanModel->where('student_id', $student['id'])->where('activity_date >=', $start_date)->where('activity_date <=', $end_date)->findAll();

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
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        return view('pages/laporan/kegiatan_siswa', $data);
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
