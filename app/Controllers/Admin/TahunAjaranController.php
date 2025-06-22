<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TahunAjaranModel; // 1. Panggil Model

class TahunAjaranController extends BaseController
{
    public function index()
    {
        // Buat instance dari model
        $model = new TahunAjaranModel();

        // Ambil semua data dari model dan kirim ke view
        $data = [
            // 'findAll()' adalah method dari CI Model untuk mengambil semua baris data
            'academicYears' => $model->orderBy('year', 'DESC')->findAll(),
        ];

        return view('pages/tahun_ajaran/index', $data);
    }

    public function new()
    {
        $data = [];

        // Menggunakan path 'pages/tahun_ajaran/form'
        return view('pages/tahun_ajaran/form', $data);
    }

    public function edit($id = null)
    {
        $model = new TahunAjaranModel();
        $data = [
            // Ambil data spesifik berdasarkan $id
            'academicYear' => $model->find($id)
        ];

        // Jika data tidak ditemukan, tampilkan error 404
        if (!$data['academicYear']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Tahun Ajaran tidak ditemukan.');
        }

        return view('pages/tahun_ajaran/form', $data);
    }

    // Method lain kita biarkan dulu
    public function create()
    {
        $rules = [
            'year' => 'required|is_unique[academic_years.year]',
            'status' => 'required|in_list[Aktif,Tidak Aktif]'
        ];

        // 2. Lakukan Validasi
        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembali ke form dengan error dan input lama
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Jika validasi berhasil, simpan data
        $model = new TahunAjaranModel();
        $status = $this->request->getPost('status');
        $year = $this->request->getPost('year');

        if ($status === 'Aktif') {
            // Method update() tanpa 'where' akan meng-update semua baris.
            $model->set(['status' => 'Tidak Aktif'])->where('status', 'Aktif')->update();
        }

        // 4. Simpan data baru
        $model->save([
            'year'   => $year,
            'status' => $status,
        ]);

        // 4. Redirect ke halaman index dengan pesan sukses
        return redirect()->to('admin/tahun-ajaran')->with('success', 'Data Tahun Ajaran berhasil ditambahkan!');
    }
    public function update($id = null)
    {
        $model = new TahunAjaranModel();
        $oldData = $model->find($id);

        // Aturan validasi
        // Jika tahun ajaran tidak diubah, aturan 'is_unique' tidak berlaku padanya
        $yearRule = ($this->request->getPost('year') == $oldData['year']) ? 'required' : 'required|is_unique[academic_years.year]';

        $rules = [
            'year' => $yearRule,
            'status' => 'required|in_list[Aktif,Tidak Aktif]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $status = $this->request->getPost('status');
        $year = $this->request->getPost('year');

        // Logika "hanya satu yang aktif"
        if ($status === 'Aktif') {
            // Nonaktifkan semua, KECUALI baris yang sedang kita edit
            $model->where('id !=', $id)->where('status', 'Aktif')->set(['status' => 'Tidak Aktif'])->update();
        }

        // Simpan perubahan
        $model->update($id, [
            'year'   => $year,
            'status' => $status,
        ]);

        return redirect()->to('admin/tahun-ajaran')->with('success', 'Data Tahun Ajaran berhasil diperbarui!');
    }
    public function delete($id = null)
    {
        $model = new TahunAjaranModel();

        // Cek apakah data ada
        $data = $model->find($id);
        if ($data) {
            // 'delete()' adalah method bawaan CI Model untuk menghapus
            $model->delete($id);
            return redirect()->to('admin/tahun-ajaran')->with('success', 'Data Tahun Ajaran berhasil dihapus!');
        }

        // Jika data tidak ditemukan
        return redirect()->to('admin/tahun-ajaran')->with('error', 'Data Tahun Ajaran tidak ditemukan.');
    }
}
