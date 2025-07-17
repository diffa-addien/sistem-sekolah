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
        
        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();
        $enrollmentModel = new EnrollmentModel();
        $kehadiranModel = new KehadiranModel();

        if (empty($date) || empty($statuses) || !$activeYear) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        foreach ($statuses as $student_id => $status) {
            $enrollment = $enrollmentModel->where(['student_id' => $student_id, 'academic_year_id' => $activeYear['id']])->first();
            
            if ($enrollment) {
                $dataToSave = [
                    'status' => $status,
                    'class_id' => $enrollment['class_id'],
                    'academic_year_id' => $activeYear['id']
                ];
                $kehadiranModel->saveOrUpdateAttendance($student_id, $date, $dataToSave);
            }
        }
        
        return redirect()->to("admin/kehadiran?class_id={$class_id_redirect}&date={$date}")->with('success', 'Data kehadiran berhasil disimpan!');
    }
}