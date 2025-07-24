<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TahunAjaranModel; // 1. Panggil Model

class TahunAjaranController extends BaseController
{
    public function index()
    {
        $model = new \App\Models\TahunAjaranModel();

        // Ambil filter dan pencarian dari URL
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');

        // Siapkan query dasar
        $query = $model;

        // Terapkan filter status jika ada
        if ($status && in_array($status, ['Aktif', 'Tidak Aktif'])) {
            $query->where('status', $status);
        }

        // Terapkan pencarian jika ada
        if ($search) {
            $query->like('year', $search);
        }

        $data = [
            'academicYears' => $query->orderBy('year', 'DESC')->paginate(10, 'academic_years'),
            'pager' => $model->pager,
            'selected_status' => $status,
            'search_keyword' => $search
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

    public function create()
    {
        $rules = [
            'year' => 'required|is_unique[academic_years.year]',
            'status' => 'required|in_list[Aktif,Tidak Aktif]',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date|gte_date[start_date]|is_date_range_conflict[0]',
        ];
        $messages = [
            'end_date' => [
                'gte_date' => 'Tanggal Selesai harus setelah atau sama dengan Tanggal Mulai.',
                'is_date_range_conflict' => 'Rentang tanggal ini bertabrakan dengan tahun ajaran lain yang sudah ada.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new TahunAjaranModel();
        $status = $this->request->getPost('status');
        if ($status === 'Aktif') {
            $model->set(['status' => 'Tidak Aktif'])->update();
        }

        $model->save($this->request->getPost());
        return redirect()->to('admin/tahun-ajaran')->with('success', 'Data Tahun Ajaran berhasil ditambahkan!');
    }

    public function update($id = null)
    {
        $model = new TahunAjaranModel();

        $rules = [
            'year' => "required|is_unique[academic_years.year,id,{$id}]",
            'status' => 'required|in_list[Aktif,Tidak Aktif]',
            'start_date' => 'required|valid_date',
            'end_date' => "required|valid_date|gte_date[start_date]|is_date_range_conflict[{$id}]",
        ];
        $messages = [
            'end_date' => [
                'gte_date' => 'Tanggal Selesai harus setelah atau sama dengan Tanggal Mulai.',
                'is_date_range_conflict' => 'Rentang tanggal ini bertabrakan dengan tahun ajaran lain yang sudah ada.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $status = $this->request->getPost('status');
        if ($status === 'Aktif') {
            $model->where('id !=', $id)->set(['status' => 'Tidak Aktif'])->update();
        }

        $model->update($id, $this->request->getPost());
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
