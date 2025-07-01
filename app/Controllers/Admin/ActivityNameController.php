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
        $model = new ActivityNameModel();
        $type = $this->request->getPost('type');

        // Aturan validasi dasar
        $rules = [
            'name' => 'required|is_unique[activity_names.name]',
            'type' => 'required|in_list[Sekolah,Rumah,Masuk,Pulang]',
            'start_time' => 'permit_empty|regex_match[/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/]',
            'end_time' => 'permit_empty|regex_match[/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/]|is_schedule_conflict[0]', // 0 = tidak ada id yg diabaikan
        ];

        // Validasi tambahan untuk tipe Masuk/Pulang
        if ($type === 'Masuk' || $type === 'Pulang') {
            $existing = $model->where('type', $type)->first();
            if ($existing) {
                return redirect()->back()->withInput()->with('error', "Tipe '{$type}' hanya boleh ada satu.");
            }
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

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
        $type = $this->request->getPost('type');

        $rules = [
            'name' => "required|is_unique[activity_names.name,id,{$id}]",
            'type' => 'required|in_list[Sekolah,Rumah,Masuk,Pulang]',
            'start_time' => 'permit_empty|regex_match[/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/]',
            'end_time' => "permit_empty|regex_match[/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/]|is_schedule_conflict[{$id}]", // Abaikan id saat ini
        ];

        if ($type === 'Masuk' || $type === 'Pulang') {
            $existing = $model->where('type', $type)->where('id !=', $id)->first();
            if ($existing) {
                return redirect()->back()->withInput()->with('error', "Tipe '{$type}' hanya boleh ada satu.");
            }
        }

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
            return redirect()->to('admin/nama-kegiatan')->with('error', 'Nama Kegiatan tidak dapat dihapus karena sudah digunakan.');
        }
        return redirect()->to('admin/nama-kegiatan')->with('error', 'Nama Kegiatan tidak ditemukan.');
    }
}