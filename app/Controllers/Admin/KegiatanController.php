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
        $model = new \App\Models\KegiatanModel();

        // Query dasar untuk mengambil data kegiatan
        $query = $model
            ->select('activities.*, students.full_name, activity_names.name as activity_name')
            ->join('students', 'students.id = activities.student_id')
            ->join('activity_names', 'activity_names.id = activities.activity_name_id');

        // Filter jika yang login adalah Guru
        if (session()->get('role') === 'Guru') {
            $teacherClassId = $this->getTeacherClassId(); // Menggunakan helper function

            if ($teacherClassId) {
                // 1. Dapatkan semua ID siswa yang ada di kelas guru tersebut
                $enrollmentModel = new \App\Models\EnrollmentModel();
                $studentsInClass = $enrollmentModel->where('class_id', $teacherClassId)->findAll();

                if (!empty($studentsInClass)) {
                    $student_ids = array_column($studentsInClass, 'student_id');
                    // 2. Filter kegiatan berdasarkan daftar ID siswa tersebut
                    $query->whereIn('activities.student_id', $student_ids);
                } else {
                    // Jika kelas guru tersebut kosong, jangan tampilkan kegiatan apa pun
                    $query->where('activities.id', 0);
                }
            } else {
                // Jika guru tidak ditugaskan ke kelas mana pun, jangan tampilkan kegiatan apa pun
                $query->where('activities.id', 0);
            }
        }

        $data['activities'] = $query->orderBy('activities.activity_date', 'DESC')->paginate(20, 'activities');
        $data['pager'] = (new \App\Models\KegiatanModel())->pager;

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