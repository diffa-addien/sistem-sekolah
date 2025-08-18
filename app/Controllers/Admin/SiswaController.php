<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\UserModel;
use App\Models\EnrollmentModel;
use App\Models\TahunAjaranModel;

class SiswaController extends BaseController
{
    public function index()
    {
        $siswaModel = new SiswaModel();
        $activeYear = (new TahunAjaranModel())->where('status', 'Aktif')->first();

        $filter_status = $this->request->getGet('status');
        $search = $this->request->getGet('search');

        $total_enrollments_subquery = '(SELECT COUNT(*) FROM enrollments WHERE enrollments.student_id = students.id) as total_enrollments';

        $query = $siswaModel
            ->select('students.*, classes.name as class_name, users.name as parent_name, ay.year as tahun_kelas, en.status as enrollment_status, ' . $total_enrollments_subquery)
            ->join('users', 'users.id = students.user_id', 'left');

        // Logika JOIN dinamis berdasarkan filter
        if ($filter_status === 'belum_terdaftar') {
            $query->join('enrollments en', "en.student_id = students.id AND en.academic_year_id = " . ($activeYear['id'] ?? 0), 'left');
            $query->where('en.id IS NULL');
            $query->join('classes', 'classes.id = en.class_id', 'left');
            $query->join('academic_years ay', 'ay.id = en.academic_year_id', 'left');
        } else {
            $query->join('enrollments en', 'en.student_id = students.id');
            $query->join('classes', 'classes.id = en.class_id');
            $query->join('academic_years ay', 'ay.id = en.academic_year_id');
            if ($filter_status === 'aktif') {
                $query->where('en.academic_year_id', $activeYear['id'] ?? 0);
                $query->where('en.status', 'Aktif');
            } elseif ($filter_status === 'riwayat') {
                $query->whereIn('en.status', ['Lulus', 'Naik Kelas', 'Tinggal Kelas', 'Keluar']);
            }
        }

        if ($search) {
            $query->groupStart()
                ->like('students.full_name', $search)
                ->orLike('students.nis', $search)
                ->groupEnd();
        }

        $data = [
            'students' => $query->orderBy('students.full_name', 'ASC')->paginate(15, 'students'),
            'pager' => $siswaModel->pager,
            'selected_status' => $filter_status,
            'search_keyword' => $search
        ];

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
        $rules = [
            'nis' => 'required|is_unique[students.nis]',
            'full_name' => 'required',
            'class_id' => 'required|is_not_unique[classes.id]',
            'gender' => 'required|in_list[Laki-laki,Perempuan]',
            'birth_date' => 'required',
            'user_id' => 'permit_empty|is_not_unique[users.id]',
            'card_uid' => 'permit_empty|is_unique[students.card_uid]',
            'photo' => 'max_size[photo,1024]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Logika Upload Foto
        $photoFile = $this->request->getFile('photo');
        $photoName = null; // Defaultnya null
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $photoName = $photoFile->getRandomName();
            $photoFile->move(FCPATH . 'uploads/photos', $photoName);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Simpan data siswa
        $siswaModel = new SiswaModel();
        $siswaData = [
            'nis' => $this->request->getPost('nis'),
            'full_name' => $this->request->getPost('full_name'),
            'gender' => $this->request->getPost('gender'),
            'birth_date' => $this->request->getPost('birth_date'),
            'user_id' => $this->request->getPost('user_id') ?: null,
            'card_uid' => $this->request->getPost('card_uid') ?: null,
            'photo' => $photoName, // Simpan nama file foto
        ];
        $siswaModel->insert($siswaData);
        $studentId = $siswaModel->getInsertID();

        // 2. Simpan data pendaftaran (enrollment)
        $activeYear = (new \App\Models\TahunAjaranModel())->where('status', 'Aktif')->first();
        if ($this->request->getPost('class_id') && $activeYear) {
            $enrollmentModel = new \App\Models\EnrollmentModel();
            $enrollmentModel->insert([
                'student_id' => $studentId,
                'class_id' => $this->request->getPost('class_id'),
                'academic_year_id' => $activeYear['id'],
                'status' => 'Aktif'
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan data siswa.');
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
            'current_enrollment' => $activeYear ? $enrollmentModel->where(['student_id' => $id, 'academic_year_id' => $activeYear['id']])->first() : null
        ];
        return view('pages/siswa/form', $data);
    }

    public function update($id = null)
    {
        $siswaModel = new \App\Models\SiswaModel();
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $activeYear = (new \App\Models\TahunAjaranModel())->where('status', 'Aktif')->first();

        // 1. Aturan Validasi
        $rules = [
            'nis' => "required|is_unique[students.nis,id,{$id}]",
            'card_uid' => "permit_empty|is_unique[students.card_uid,id,{$id}]",
            'full_name' => 'required',
            'class_id' => 'permit_empty|is_not_unique[classes.id]',
            'gender' => 'required|in_list[Laki-laki,Perempuan]',
            'birth_date' => 'required',
            'user_id' => 'permit_empty|is_not_unique[users.id]',
            'photo' => 'max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart(); // Mulai transaksi

        // 2. Siapkan data untuk tabel 'students'
        $oldStudentData = $siswaModel->find($id);

        $dataToUpdate = [
            'nis' => $this->request->getPost('nis'),
            'full_name' => $this->request->getPost('full_name'),
            'user_id' => $this->request->getPost('user_id') ?: null,
            'gender' => $this->request->getPost('gender'),
            'birth_date' => $this->request->getPost('birth_date'),
            'card_uid' => $this->request->getPost('card_uid') ?: null,
        ];

        // 3. Logika Upload Foto Baru
        $photoFile = $this->request->getFile('photo');
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $oldPhoto = $oldStudentData['photo'];
            // Hapus foto lama jika ada dan bukan default
            if ($oldPhoto && $oldPhoto !== 'default.png' && file_exists(FCPATH . 'uploads/photos/' . $oldPhoto)) {
                unlink(FCPATH . 'uploads/photos/' . $oldPhoto);
            }
            // Pindahkan foto baru dan simpan namanya
            $newPhotoName = $photoFile->getRandomName();
            $photoFile->move(FCPATH . 'uploads/photos', $newPhotoName);
            $dataToUpdate['photo'] = $newPhotoName;
        }

        // 4. Update tabel 'students'
        $siswaModel->update($id, $dataToUpdate);

        // 5. Logika "Upsert" untuk tabel 'enrollments'
        $classId = $this->request->getPost('class_id');
        if ($classId && $activeYear) {
            $existingEnrollment = $enrollmentModel->where(['student_id' => $id, 'academic_year_id' => $activeYear['id']])->first();

            if ($existingEnrollment) {
                if ($existingEnrollment['class_id'] != $classId) {
                    $enrollmentModel->update($existingEnrollment['id'], ['class_id' => $classId]);
                }
            } else {
                $enrollmentModel->insert([
                    'student_id' => $id,
                    'class_id' => $classId,
                    'academic_year_id' => $activeYear['id'],
                    'status' => 'Aktif'
                ]);
            }
        }

        $db->transComplete(); // Selesaikan transaksi

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data siswa.');
        }

        return redirect()->to('admin/siswa')->with('success', 'Data Siswa berhasil diperbarui!');
    }

    public function delete($id = null)
    {
        // Logika delete akan menghapus data siswa dan semua enrollment terkait (via CASCADE)
        $model = new SiswaModel();
        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to('admin/siswa')->with('success', 'Data siswa dan semua riwayatnya berhasil dihapus!');
        }
        return redirect()->to('admin/siswa')->with('error', 'Data siswa tidak ditemukan.');
    }
}