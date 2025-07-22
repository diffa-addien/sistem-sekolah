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

    $filter_status = $this->request->getGet('status');
    $search = $this->request->getGet('search');

    // Siapkan query dasar
    $query = $siswaModel
        ->select('students.*, classes.name as class_name, users.name as parent_name, ay.year as tahun_kelas, en.status as enrollment_status')
        ->join('users', 'users.id = students.user_id', 'left');

    // Logika JOIN dan WHERE yang dinamis berdasarkan filter
    if ($filter_status === 'belum_terdaftar') {
        // Cari siswa yang TIDAK punya enrollment di tahun ajaran aktif
        $query->join('enrollments en', "en.student_id = students.id AND en.academic_year_id = " . ($activeYear['id'] ?? 0), 'left');
        $query->where('en.id IS NULL');
        // Join tambahan untuk kolom display (akan menghasilkan NULL)
        $query->join('classes', 'classes.id = en.class_id', 'left');
        $query->join('academic_years ay', 'ay.id = en.academic_year_id', 'left');
    
    } else {
        // Untuk semua kasus lain, kita butuh data enrollment, jadi pakai INNER JOIN
        $query->join('enrollments en', 'en.student_id = students.id');
        $query->join('classes', 'classes.id = en.class_id');
        $query->join('academic_years ay', 'ay.id = en.academic_year_id');

        if ($filter_status === 'aktif') {
            $query->where('en.academic_year_id', $activeYear['id'] ?? 0);
            $query->where('en.status', 'Aktif');
        } elseif ($filter_status === 'riwayat') { // Ganti 'lulus' menjadi 'riwayat' agar lebih umum
             $query->whereIn('en.status', ['Lulus', 'Naik Kelas', 'Tinggal Kelas', 'Keluar']);
        }
        // Jika filter status kosong, akan menampilkan semua siswa yang pernah terdaftar
    }

    // Terapkan pencarian jika ada
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
