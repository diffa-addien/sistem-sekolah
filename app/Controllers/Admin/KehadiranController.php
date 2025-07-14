<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\KehadiranModel;

class KehadiranController extends BaseController
{
    public function index()
    {
        $kelasModel = new KelasModel();
        $siswaModel = new SiswaModel();
        $kehadiranModel = new KehadiranModel();

        $userRole = session()->get('role');
        $userId = session()->get('user_id');

        $data = [
            'classes' => [],
            'students' => [],
            'selected_class_id' => null,
            'selected_date' => $this->request->getGet('date') ?? date('Y-m-d'),
            'is_teacher' => ($userRole === 'Guru'), // Flag untuk view
            'attendance_records' => []
        ];

        if ($userRole === 'Admin') {
            // Logika untuk Admin (tidak berubah)
            $data['classes'] = $kelasModel->findAll();
            $class_id = $this->request->getGet('class_id');
            if ($class_id) {
                $data['selected_class_id'] = $class_id;
                $data['students'] = $siswaModel->where('class_id', $class_id)->findAll();
            }
        } elseif ($userRole === 'Guru') {
            // Logika baru untuk Guru
            $assigned_class = $kelasModel->where('teacher_id', $userId)->first();
            if ($assigned_class) {
                $data['selected_class_id'] = $assigned_class['id'];
                $data['classes'][] = $assigned_class; // Kirim hanya kelas yang diajar
                $data['students'] = $siswaModel->where('class_id', $assigned_class['id'])->findAll();
            }
        }

        // Cek kehadiran jika ada siswa yang ditampilkan
        if (!empty($data['students'])) {
            $student_ids = array_column($data['students'], 'id');
            $attendances = $kehadiranModel->where('attendance_date', $data['selected_date'])
                ->whereIn('student_id', $student_ids)
                ->findAll();
            $data['attendance_records'] = array_column($attendances, null, 'student_id');
        }

        return view('pages/kehadiran/form', $data);
    }

    public function store()
    {
        // 1. Validasi dasar (tidak berubah)
        $class_id = $this->request->getPost('class_id');
        $date = $this->request->getPost('date');
        $statuses = $this->request->getPost('status');

        if (empty($class_id) || empty($date) || empty($statuses)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak lengkap atau tidak valid.');
        }

        // 2. Panggil method saveAttendance() untuk setiap siswa
        $kehadiranModel = new KehadiranModel();
        foreach ($statuses as $student_id => $status) {
            $kehadiranModel->saveAttendance($student_id, $date, $status);
        }

        // 3. Redirect dengan pesan sukses
        return redirect()->to('admin/kehadiran?class_id=' . $class_id . '&date=' . $date)
            ->with('success', 'Data kehadiran berhasil disimpan/diperbarui!');
    }
}
