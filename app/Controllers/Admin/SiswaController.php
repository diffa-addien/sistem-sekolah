<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel; // Panggil model
use App\Models\KelasModel;
use App\Models\UserModel;

class SiswaController extends BaseController
{
    public function index()
    {
        $model = new SiswaModel();

        // Query untuk mengambil data siswa dengan nama kelas dan nama wali murid
        $data['students'] = $model->select('students.id, students.nis, students.full_name, students.photo, classes.name as class_name, users.name as parent_name')
            ->join('classes', 'classes.id = students.class_id')
            ->join('users', 'users.id = students.user_id', 'left') // LEFT JOIN
            ->orderBy('students.id', 'DESC')
            ->findAll();

        return view('pages/siswa/index', $data);
    }

    public function new()
    {
        $kelasModel = new KelasModel();
        $userModel = new UserModel();

        $data = [
            'classes' => $kelasModel->select('classes.id, classes.name, academic_years.year as academic_year')
                ->join('academic_years', 'academic_years.id = classes.academic_year_id')
                ->where('academic_years.status', 'Aktif') // Hanya ambil kelas dari tahun ajaran aktif
                ->findAll(),
            'parents' => $userModel->where('role', 'Wali Murid')->findAll()
        ];

        return view('pages/siswa/form', $data);
    }

    // Method lain akan kita isi nanti
    public function create()
    {
        $rules = [
            'nis'       => 'required|is_unique[students.nis]',
            'full_name' => 'required',
            'class_id'  => 'required|is_not_unique[classes.id]',
            'gender'    => 'required|in_list[Laki-laki,Perempuan]',
            'birth_date' => 'required',
            'user_id'   => 'permit_empty|is_not_unique[users.id]',
            'photo'     => 'max_size[photo,1024]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Proses Upload Foto (jika ada)
        $photoFile = $this->request->getFile('photo');
        $photoName = 'default.png'; // Nama default jika tidak ada foto diupload

        if ($photoFile->isValid() && !$photoFile->hasMoved()) {
            $photoName = $photoFile->getRandomName();
            $photoFile->move(FCPATH . 'uploads/photos', $photoName);
        }

        // 3. Simpan data ke database
        $model = new SiswaModel();
        $model->save([
            'nis'        => $this->request->getPost('nis'),
            'full_name'  => $this->request->getPost('full_name'),
            'class_id'   => $this->request->getPost('class_id'),
            'user_id'    => $this->request->getPost('user_id') ?: null, // Simpan NULL jika kosong
            'gender'     => $this->request->getPost('gender'),
            'birth_date' => $this->request->getPost('birth_date'),
            'photo'      => $photoName,
        ]);

        return redirect()->to('admin/siswa')->with('success', 'Data Siswa berhasil ditambahkan!');
    }

    public function edit($id = null)
    {
        $siswaModel = new SiswaModel();
        $kelasModel = new KelasModel();
        $userModel = new UserModel();

        $data = [
            'student' => $siswaModel->find($id),
            'classes' => $kelasModel->select('classes.id, classes.name, academic_years.year as academic_year')
                ->join('academic_years', 'academic_years.id = classes.academic_year_id')
                ->where('academic_years.status', 'Aktif')
                ->findAll(),
            'parents' => $userModel->where('role', 'Wali Murid')->findAll()
        ];

        if (empty($data['student'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Siswa tidak ditemukan.');
        }

        return view('pages/siswa/form', $data);
    }
    public function update($id = null)
    {
        $siswaModel = new SiswaModel();
        $oldStudentData = $siswaModel->find($id);

        // Aturan validasi NIS: unik, tapi abaikan entri saat ini
        $nisRule = ($this->request->getPost('nis') == $oldStudentData['nis']) ? 'required' : 'required|is_unique[students.nis]';

        $rules = [
            'nis'       => $nisRule,
            'full_name' => 'required',
            'class_id'  => 'required|is_not_unique[classes.id]',
            'gender'    => 'required|in_list[Laki-laki,Perempuan]',
            'birth_date' => 'required',
            'user_id'   => 'permit_empty|is_not_unique[users.id]',
            'photo'     => 'max_size[photo,1024]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Proses Upload Foto (jika ada file baru)
        $photoFile = $this->request->getFile('photo');
        $photoName = $oldStudentData['photo']; // Defaultnya, pakai nama foto lama

        if ($photoFile->isValid() && !$photoFile->hasMoved()) {
            // Hapus foto lama jika bukan default.png
            if ($photoName !== 'default.png' && file_exists(FCPATH . 'uploads/photos/' . $photoName)) {
                unlink(FCPATH . 'uploads/photos/' . $photoName);
            }
            // Upload foto baru
            $photoName = $photoFile->getRandomName();
            $photoFile->move(FCPATH . 'uploads/photos', $photoName);
        }

        // Siapkan data untuk update
        $dataToUpdate = [
            'nis'        => $this->request->getPost('nis'),
            'full_name'  => $this->request->getPost('full_name'),
            'class_id'   => $this->request->getPost('class_id'),
            'user_id'    => $this->request->getPost('user_id') ?: null,
            'gender'     => $this->request->getPost('gender'),
            'birth_date' => $this->request->getPost('birth_date'),
            'photo'      => $photoName,
        ];

        $siswaModel->update($id, $dataToUpdate);

        return redirect()->to('admin/siswa')->with('success', 'Data Siswa berhasil diperbarui!');
    }
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
    public function show($id = null) {}
}
