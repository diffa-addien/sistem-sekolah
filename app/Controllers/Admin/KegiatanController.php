<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KegiatanModel;
use App\Models\SiswaModel;
use App\Models\ActivityNameModel;
use App\Models\KelasModel;
use App\Models\EnrollmentModel;
use App\Models\TahunAjaranModel;

class KegiatanController extends BaseController
{
    /**
     * Helper function untuk mendapatkan class_id guru yang sedang login.
     */
    private function getTeacherClassId()
    {
        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();
        if (!$activeYear || session()->get('role') !== 'Guru') {
            return null;
        }

        $assigned_class = (new KelasModel())
            ->where('teacher_id', session()->get('user_id'))
            ->where('academic_year_id', $activeYear['id'])
            ->first();

        return $assigned_class ? $assigned_class['id'] : null;
    }

    public function index()
    {
        // Panggil semua model yang dibutuhkan
        $kelasModel = new \App\Models\KelasModel();
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $kegiatanModel = new \App\Models\KegiatanModel();
        $activeYear = (new \App\Models\TahunAjaranModel())->where('status', 'Aktif')->first();

        $userRole = session()->get('role');
        $userId = session()->get('user_id');

        // Ambil filter dari URL, default ke hari ini
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $class_id = $this->request->getGet('class_id');

        $data = [
            'classes' => [],
            'students' => [],
            'activityData' => [],
            'selected_class_id' => null,
            'selected_date' => $date,
            'is_teacher' => ($userRole === 'Guru'),
        ];

        if (!$activeYear) {
            return view('pages/kegiatan/index', $data); // Tampilkan halaman kosong jika T/A tidak aktif
        }

        // Tentukan kelas mana yang akan ditampilkan
        if ($userRole === 'Admin') {
            $data['classes'] = $kelasModel->where('academic_year_id', $activeYear['id'])->findAll();
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

        // Jika sudah ada kelas yang dipilih, ambil data siswa dan kegiatannya
        if ($data['selected_class_id']) {
            // Ambil daftar siswa di kelas tersebut
            $data['students'] = $enrollmentModel
                ->select('students.*')
                ->join('students', 'students.id = enrollments.student_id')
                ->where('enrollments.class_id', $data['selected_class_id'])
                ->where('enrollments.academic_year_id', $activeYear['id'])
                ->findAll();

            // Ambil data kegiatan untuk siswa-siswa tersebut pada tanggal yang dipilih
            $student_ids = array_column($data['students'], 'id');
            if (!empty($student_ids)) {
                $raw_activities = $kegiatanModel
                    ->select('activities.student_id, activities.created_at, activity_names.name as activity_name')
                    ->join('activity_names', 'activity_names.id = activities.activity_name_id')
                    ->whereIn('activities.student_id', $student_ids)
                    ->where('activities.activity_date', $date)
                    ->findAll();

                // Proses dan kelompokkan data kegiatan per siswa
                $activityData = [];
                foreach ($raw_activities as $activity) {
                    $activityData[$activity['student_id']]['details'][] = [
                        'name' => $activity['activity_name'],
                        'time' => $activity['created_at']
                    ];
                }
                // Hitung total kegiatan per siswa
                foreach ($activityData as &$studentData) {
                    $studentData['count'] = count($studentData['details']);
                }
                $data['activityData'] = $activityData;
            }
        }

        return view('pages/kegiatan/index', $data);
    }

    private function getFormData()
    {
        $role = session()->get('role');
        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();

        $data = [
            'classes' => [],
            'students' => [],
            'activity_names' => (new ActivityNameModel())->whereNotIn('type', ['Masuk', 'Pulang'])->orderBy('name', 'ASC')->findAll(),
        ];

        if ($activeYear) {
            if ($role === 'Admin') {
                $data['classes'] = (new KelasModel())->where('academic_year_id', $activeYear['id'])->findAll();
                $data['students'] = (new SiswaModel())->select('students.id, students.full_name, en.class_id')
                    ->join('enrollments en', 'en.student_id = students.id')
                    ->where('en.academic_year_id', $activeYear['id'])
                    ->orderBy('students.full_name', 'ASC')->findAll();
            } elseif ($role === 'Guru') {
                $teacherClassId = $this->getTeacherClassId();
                if ($teacherClassId) {
                    $data['students'] = (new EnrollmentModel())
                        ->select('students.id, students.full_name')
                        ->join('students', 'students.id = enrollments.student_id')
                        ->where('enrollments.class_id', $teacherClassId)
                        ->orderBy('students.full_name', 'ASC')->findAll();
                }
            }
        }
        return $data;
    }

    public function new()
    {
        $data = $this->getFormData();
        return view('pages/kegiatan/form', $data);
    }

    public function create()
    {
        $rules = [
            'student_id' => 'required|is_not_unique[students.id]',
            'activity_name_id' => 'required|is_not_unique[activity_names.id]|is_activity_recorded[student_id,activity_date]',
            'activity_date' => 'required',
            'description' => 'permit_empty|max_length[500]',
        ];
        if (!$this->validate($rules, ['activity_name_id' => ['is_activity_recorded' => 'Kegiatan ini sudah dicatat untuk siswa tsb di tanggal yang sama.']])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $student_id = $this->request->getPost('student_id');

        // Security Check untuk Guru
        if (session()->get('role') === 'Guru') {
            $teacherClassId = $this->getTeacherClassId();
            $enrollment = (new EnrollmentModel())->where(['student_id' => $student_id, 'class_id' => $teacherClassId])->first();
            if (!$enrollment) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mencatat kegiatan siswa ini.');
            }
        }

        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();
        $enrollment = (new EnrollmentModel())->where(['student_id' => $student_id, 'academic_year_id' => $activeYear['id']])->first();

        $dataToSave = $this->request->getPost();
        $dataToSave['class_id'] = $enrollment['class_id'];
        $dataToSave['academic_year_id'] = $activeYear['id'];

        (new KegiatanModel())->save($dataToSave);

        return redirect()->to('admin/kegiatan')->with('success', 'Data kegiatan berhasil dicatat!');
    }

    public function edit($id = null)
    {
        $model = new KegiatanModel();
        $data = $this->getFormData();
        $data['activity'] = $model->find($id);

        if (empty($data['activity'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Kegiatan tidak ditemukan.');
        }
        return view('pages/kegiatan/form', $data);
    }

    public function update($id = null)
    {
        $rules = [
            'student_id' => 'required|is_not_unique[students.id]',
            'activity_name_id' => 'required|is_not_unique[activity_names.id]',
            'activity_date' => 'required',
            'description' => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $student_id = $this->request->getPost('student_id');

        // Security Check untuk Guru
        if (session()->get('role') === 'Guru') {
            $teacherClassId = $this->getTeacherClassId();
            $enrollment = (new EnrollmentModel())->where(['student_id' => $student_id, 'class_id' => $teacherClassId])->first();
            if (!$enrollment) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah kegiatan siswa ini.');
            }
        }

        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();
        $enrollment = (new EnrollmentModel())->where(['student_id' => $student_id, 'academic_year_id' => $activeYear['id']])->first();

        $dataToUpdate = $this->request->getPost();
        $dataToUpdate['class_id'] = $enrollment['class_id'];
        $dataToUpdate['academic_year_id'] = $activeYear['id'];

        (new KegiatanModel())->update($id, $dataToUpdate);

        return redirect()->to('admin/kegiatan')->with('success', 'Data kegiatan berhasil diperbarui!');
    }

    public function delete($id = null)
    {
        (new KegiatanModel())->delete($id);
        return redirect()->to('admin/kegiatan')->with('success', 'Data kegiatan berhasil dihapus!');
    }
}