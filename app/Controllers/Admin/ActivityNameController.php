<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityNameModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class ActivityNameController extends BaseController
{
    public function index()
    {
        $model = new ActivityNameModel();
        $data['activityNames'] = $model->orderBy('name', 'ASC')->findAll();
        return view('pages/kegiatan_list/index', $data);
    }

    public function new()
    {
        return view('pages/kegiatan_list/form');
    }

    public function create()
    {
        $rules = [
            'name' => 'required|is_unique[activity_names.name]',
            'type' => 'required|in_list[Sekolah,Rumah]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new ActivityNameModel();
        $model->save($this->request->getPost());
        return redirect()->to('admin/nama-kegiatan')->with('success', 'Nama Kegiatan berhasil ditambahkan!');
    }

    public function edit($id = null)
    {
        $model = new ActivityNameModel();
        $data['activityName'] = $model->find($id);
        if (empty($data['activityName'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Nama Kegiatan tidak ditemukan.');
        }
        return view('pages/kegiatan_list/form', $data);
    }

    public function update($id = null)
    {
        $model = new ActivityNameModel();
        $oldData = $model->find($id);
        $nameRule = ($this->request->getPost('name') == $oldData['name']) ? 'required' : 'required|is_unique[activity_names.name]';
        
        $rules = [
            'name' => $nameRule,
            'type' => 'required|in_list[Sekolah,Rumah]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, $this->request->getPost());
        return redirect()->to('admin/nama-kegiatan')->with('success', 'Nama Kegiatan berhasil diperbarui!');
    }

    public function delete($id = null)
    {
        $model = new ActivityNameModel();
        try {
            if ($model->find($id)) {
                $model->delete($id);
                return redirect()->to('admin/nama-kegiatan')->with('success', 'Nama Kegiatan berhasil dihapus!');
            }
        } catch (DatabaseException $e) {
            // Tangani error foreign key constraint
            return redirect()->to('admin/nama-kegiatan')->with('error', 'Nama Kegiatan tidak dapat dihapus karena sudah digunakan dalam catatan kegiatan siswa.');
        }
        return redirect()->to('admin/nama-kegiatan')->with('error', 'Nama Kegiatan tidak ditemukan.');
    }
}