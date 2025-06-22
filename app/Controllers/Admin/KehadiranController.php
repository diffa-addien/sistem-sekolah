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

        $class_id = $this->request->getGet('class_id');
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $data = [
            'classes' => $kelasModel->findAll(),
            'students' => [],
            'selected_class_id' => $class_id,
            'selected_date' => $date,
            'attendance_records' => [] // Ganti is_recorded
        ];

        if ($class_id) {
            $data['students'] = $siswaModel->where('class_id', $class_id)->findAll();
            if (!empty($data['students'])) {
                $student_ids = array_column($data['students'], 'id');
                $attendances = $kehadiranModel->where('attendance_date', $date)
                    ->whereIn('student_id', $student_ids)
                    ->findAll();
                // Ubah array agar mudah diakses di view dengan key student_id
                $data['attendance_records'] = array_column($attendances, null, 'student_id');
            }
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
