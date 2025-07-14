<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel; // Panggil model
use App\Models\UserModel; // Panggil model
use App\Models\TahunAjaranModel; // 1. Panggil model TahunAjaran

class KelasController extends BaseController
{
    /**
     * Halaman utama untuk menampilkan daftar kelas.
     */
    public function index()
    {
        $model = new KelasModel();

        // Mengambil data dengan JOIN
        $data['classes'] = $model->select('classes.id, classes.name, academic_years.year as academic_year, academic_years.status as status')
            ->join('academic_years', 'academic_years.id = classes.academic_year_id')
            ->orderBy('classes.id', 'DESC')
            ->findAll();

        // var_dump($data['classes']); die;

        return view('pages/kelas/index', $data);
    }

    public function new()
    {
        $tahunAjaranModel = new TahunAjaranModel();
        $userModel = new UserModel(); // Buat instance UserModel
        $data = [
            'academicYears' => $tahunAjaranModel->orderBy('year', 'DESC')->findAll(),
            'teachers' => $userModel->where('role', 'Guru')->findAll() // Ambil daftar guru
        ];
        return view('pages/kelas/form', $data);
    }

    public function create()
    {
        $rules = [
            'name' => 'required',
            'academic_year_id' => 'required|is_not_unique[academic_years.id]',
            'teacher_id' => 'permit_empty|is_not_unique[users.id]' // Validasi teacher_id
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new KelasModel();
        $model->save([
            'name' => $this->request->getPost('name'),
            'academic_year_id' => $this->request->getPost('academic_year_id'),
            'teacher_id' => $this->request->getPost('teacher_id') ?: null, // Simpan NULL jika kosong
        ]);

        return redirect()->to('admin/kelas')->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    public function edit($id = null)
    {
        $kelasModel = new KelasModel();
        $tahunAjaranModel = new TahunAjaranModel();
        $userModel = new UserModel(); // Buat instance UserModel

        $data = [
            'classData' => $kelasModel->find($id),
            'academicYears' => $tahunAjaranModel->orderBy('year', 'DESC')->findAll(),
            'teachers' => $userModel->where('role', 'Guru')->findAll() // Ambil daftar guru
        ];

        if (empty($data['classData'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Kelas tidak ditemukan.');
        }

        return view('pages/kelas/form', $data);
    }

    public function update($id = null)
    {
        $rules = [
            'name' => 'required',
            'academic_year_id' => 'required|is_not_unique[academic_years.id]',
            'teacher_id' => 'permit_empty|is_not_unique[users.id]' // Validasi teacher_id
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new KelasModel();
        $model->update($id, [
            'name' => $this->request->getPost('name'),
            'academic_year_id' => $this->request->getPost('academic_year_id'),
            'teacher_id' => $this->request->getPost('teacher_id') ?: null, // Simpan NULL jika kosong
        ]);

        return redirect()->to('admin/kelas')->with('success', 'Data Kelas berhasil diperbarui!');
    }
    public function delete($id = null)
    {
        $model = new KelasModel();

        $data = $model->find($id);
        if ($data) {
            $model->delete($id);
            return redirect()->to('admin/kelas')->with('success', 'Data Kelas berhasil dihapus!');
        }

        return redirect()->to('admin/kelas')->with('error', 'Data Kelas tidak ditemukan.');
    }
    public function show($id = null)
    {
        /* ... */
    }
}
