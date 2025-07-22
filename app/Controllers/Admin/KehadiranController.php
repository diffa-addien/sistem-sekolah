<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\KehadiranModel;
use App\Models\EnrollmentModel;
use App\Models\TahunAjaranModel;

class KehadiranController extends BaseController
{
    public function index()
    {
        $kelasModel = new KelasModel();
        $enrollmentModel = new EnrollmentModel();
        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();

        $userRole = session()->get('role');
        $userId = session()->get('user_id');

        $data = [
            'classes' => [],
            'students' => [],
            'selected_class_id' => null,
            'selected_date' => $this->request->getGet('date') ?? date('Y-m-d'),
            'is_teacher' => ($userRole === 'Guru'),
            'attendance_records' => []
        ];

        if (!$activeYear) {
            return view('pages/kehadiran/form', $data);
        }

        if ($userRole === 'Admin') {
            $data['classes'] = $kelasModel->where('academic_year_id', $activeYear['id'])->findAll();
            $class_id = $this->request->getGet('class_id');
            if ($class_id) {
                $data['selected_class_id'] = $class_id;
            }
        } elseif ($userRole === 'Guru') {
            $assigned_class = $kelasModel->where('teacher_id', $userId)->where('academic_year_id', $activeYear['id'])->first();
            if ($assigned_class) {
                $data['selected_class_id'] = $assigned_class['id'];
                $data['classes'][] = $assigned_class;
            }
        }

        if ($data['selected_class_id']) {
            $data['students'] = $enrollmentModel
                ->select('students.*')
                ->join('students', 'students.id = enrollments.student_id')
                ->where('enrollments.class_id', $data['selected_class_id'])
                ->where('enrollments.academic_year_id', $activeYear['id'])
                ->findAll();
        }

        if (!empty($data['students'])) {
            $student_ids = array_column($data['students'], 'id');
            $attendances = (new KehadiranModel())->where('attendance_date', $data['selected_date'])->whereIn('student_id', $student_ids)->findAll();
            $data['attendance_records'] = array_column($attendances, null, 'student_id');
        }

        return view('pages/kehadiran/form', $data);
    }

    public function store()
    {
        $date = $this->request->getPost('date');
        $statuses = $this->request->getPost('status');
        $class_id_redirect = $this->request->getPost('class_id');

        $activeYear = (new \App\Models\TahunAjaranModel())->where('status', 'Aktif')->first();
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $kehadiranModel = new \App\Models\KehadiranModel();

        if (empty($date) || empty($statuses) || !$activeYear) {
            return redirect()->back()->with('error', 'Data tidak lengkap atau T/A tidak aktif.');
        }

        $all_success = true; // Flag untuk melacak jika ada kegagalan

        // Gunakan transaksi database untuk memastikan semua data disimpan atau tidak sama sekali
        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($statuses as $student_id => $status) {
            $enrollment = $enrollmentModel->where(['student_id' => $student_id, 'academic_year_id' => $activeYear['id']])->first();

            if ($enrollment) {
                $dataToSave = [
                    'status' => $status,
                    'class_id' => $enrollment['class_id'],
                    'academic_year_id' => $activeYear['id']
                ];

                // Cek hasil dari setiap operasi simpan
                if (!$kehadiranModel->saveOrUpdateAttendance($student_id, $date, $dataToSave)) {
                    $all_success = false; // Jika ada satu yang gagal, tandai
                }
            } else {
                $all_success = false; // Siswa tidak terdaftar di T/A aktif
            }
        }

        $db->transComplete();

        // Redirect berdasarkan hasil akhir
        if ($all_success && $db->transStatus() !== false) {
            return redirect()->to("admin/kehadiran?class_id={$class_id_redirect}&date={$date}")->with('success', 'Data kehadiran berhasil disimpan!');
        } else {
            return redirect()->to("admin/kehadiran?class_id={$class_id_redirect}&date={$date}")->with('error', 'Terjadi kesalahan, beberapa data mungkin gagal disimpan.');
        }
    }
}