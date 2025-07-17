<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\UserModel;
use App\Models\EnrollmentModel; // Panggil model baru
use App\Models\TahunAjaranModel; // Panggil model baru

class SiswaController extends BaseController
{
    public function index()
{
    $siswaModel = new SiswaModel();
    $activeYear = (new \App\Models\TahunAjaranModel())->where('status', 'Aktif')->first();

    // Query diubah untuk menggunakan alias 'tahun_kelas'
    $query = $siswaModel
        ->select('students.*, classes.name as class_name, users.name as parent_name, academic_years.year as tahun_kelas')
        ->join('enrollments', "enrollments.student_id = students.id", 'left')
        ->join('classes', 'classes.id = enrollments.class_id', 'left')
        ->join('academic_years', 'academic_years.id = enrollments.academic_year_id', 'left')
        ->join('users', 'users.id = students.user_id', 'left')
        ->orderBy('students.full_name', 'ASC');

    if ($activeYear) {
        $query->where('enrollments.academic_year_id', $activeYear['id']);
    } else {
        $query->where('enrollments.academic_year_id IS NULL');
    }

    $data['students'] = $query->findAll();

    return view('pages/siswa/index', $data);
}

    public function new()
    {
        $kelasModel = new KelasModel();
        $userModel = new UserModel();
        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();

        $data = [
            'classes' => $activeYear ? $kelasModel->where('academic_year_id', $activeYear['id'])->findAll() : [],
            'parents' => $userModel->where('role', 'Wali Murid')->findAll()
        ];
        return view('pages/siswa/form', $data);
    }

    public function create()
    {
        // ... (aturan validasi tidak berubah, kecuali hapus 'class_id' jika ada)
        $rules = [
            'nis' => 'required|is_unique[students.nis]',
            'full_name' => 'required',
            // 'class_id' divalidasi terpisah karena sekarang masuk ke enrollments
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart(); // Mulai transaksi

        // 1. Simpan data siswa
        $siswaModel = new SiswaModel();
        $siswaData = [
            'nis' => $this->request->getPost('nis'),
            'full_name' => $this->request->getPost('full_name'),
            'user_id' => $this->request->getPost('user_id') ?: null,
            'card_uid' => $this->request->getPost('card_uid') ?: null,
            // ... (field siswa lainnya)
        ];
        $siswaModel->save($siswaData);
        $studentId = $siswaModel->getInsertID();

        // 2. Simpan data pendaftaran (enrollment)
        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();
        if ($this->request->getPost('class_id') && $activeYear) {
            $enrollmentModel = new EnrollmentModel();
            $enrollmentModel->save([
                'student_id' => $studentId,
                'class_id' => $this->request->getPost('class_id'),
                'academic_year_id' => $activeYear['id'],
                'status' => 'Aktif'
            ]);
        }

        $db->transComplete(); // Selesaikan transaksi

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan data siswa dan pendaftaran.');
        }

        return redirect()->to('admin/siswa')->with('success', 'Data Siswa berhasil ditambahkan!');
    }

    public function edit($id = null)
    {
        $siswaModel = new SiswaModel();
        $kelasModel = new KelasModel();
        $userModel = new UserModel();
        $enrollmentModel = new EnrollmentModel();
        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();

        $data = [
            'student' => $siswaModel->find($id),
            'parents' => $userModel->where('role', 'Wali Murid')->findAll(),
            'classes' => $activeYear ? $kelasModel->where('academic_year_id', $activeYear['id'])->findAll() : [],
            // Ambil data pendaftaran siswa di tahun ajaran aktif
            'current_enrollment' => $activeYear ? $enrollmentModel->where(['student_id' => $id, 'academic_year_id' => $activeYear['id']])->first() : null
        ];
        return view('pages/siswa/form', $data);
    }

    public function update($id = null)
    {
        // ... (Validasi data siswa seperti biasa)

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Update data utama siswa
        $siswaModel = new SiswaModel();
        $siswaData = [ /* ... data dari form ... */];
        $siswaModel->update($id, $siswaData);

        // 2. Update atau buat data pendaftaran (enrollment)
        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();
        $classId = $this->request->getPost('class_id');
        if ($classId && $activeYear) {
            $enrollmentModel = new EnrollmentModel();
            $existingEnrollment = $enrollmentModel->where(['student_id' => $id, 'academic_year_id' => $activeYear['id']])->first();

            if ($existingEnrollment) {
                // Jika sudah ada, update kelasnya
                $enrollmentModel->update($existingEnrollment['id'], ['class_id' => $classId]);
            } else {
                // Jika belum ada, buat baru
                $enrollmentModel->insert(['student_id' => $id, 'class_id' => $classId, 'academic_year_id' => $activeYear['id']]);
            }
        }

        $db->transComplete();
        // ... (redirect dengan pesan sukses atau error)
    }

    // ... (method delete tidak berubah)
    public function delete($id = null)
    {
        $model = new SiswaModel();

        // 1. Temukan data siswa, termasuk nama file fotonya
        $student = $model->find($id);

        if ($student) {
            // 2. Hapus file foto jika bukan default.png
            if ($student['photo'] && $student['photo'] !== 'default.png') {
                $photoPath = FCPATH . 'uploads/photos/' . $student['photo'];
                if (file_exists($photoPath)) {
                    unlink($photoPath); // Hapus file dari server
                }
            }

            // 3. Hapus data dari database
            $model->delete($id);

            return redirect()->to('admin/siswa')->with('success', 'Data Siswa berhasil dihapus!');
        }

        return redirect()->to('admin/siswa')->with('error', 'Data Siswa tidak ditemukan.');
    }
    public function show($id = null)
    {
    }
}
