<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KegiatanModel;
use App\Models\SiswaModel;
use App\Models\ActivityNameModel;

class KegiatanController extends BaseController
{
    public function index()
    {
        $model = new KegiatanModel();
        $data['activities'] = $model
            ->select('activities.*, students.full_name, activity_names.name as activity_name')
            ->join('students', 'students.id = activities.student_id')
            ->join('activity_names', 'activity_names.id = activities.activity_name_id')
            ->orderBy('activities.activity_date', 'DESC')
            ->findAll();

        return view('pages/kegiatan/index', $data);
    }

    public function new()
    {
        $siswaModel = new SiswaModel();
        $activityNameModel = new ActivityNameModel();
        $data = [
            'students' => $siswaModel->orderBy('full_name', 'ASC')->findAll(),
            'activity_names' => $activityNameModel->orderBy('name', 'ASC')->findAll(),
        ];
        return view('pages/kegiatan/form', $data);
    }

    public function create()
    {
        $rules = [
            'student_id' => 'required|is_not_unique[students.id]',
            'activity_name_id' => 'required|is_not_unique[activity_names.id]',
            'activity_date' => 'required|valid_date',
            'description' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new KegiatanModel();
        $model->save($this->request->getPost());

        return redirect()->to('admin/kegiatan')->with('success', 'Data kegiatan berhasil dicatat!');
    }

    public function edit($id = null)
    {
        $model = new KegiatanModel();
        $siswaModel = new SiswaModel();
        $activityNameModel = new ActivityNameModel();

        $data = [
            'activity' => $model->find($id),
            'students' => $siswaModel->orderBy('full_name', 'ASC')->findAll(),
            'activity_names' => $activityNameModel->orderBy('name', 'ASC')->findAll(),
        ];
        
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
            'activity_date' => 'required|valid_date',
            'description' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new KegiatanModel();
        $model->update($id, $this->request->getPost());

        return redirect()->to('admin/kegiatan')->with('success', 'Data kegiatan berhasil diperbarui!');
    }

    public function delete($id = null)
    {
        $model = new KegiatanModel();
        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to('admin/kegiatan')->with('success', 'Data kegiatan berhasil dihapus!');
        }
        return redirect()->to('admin/kegiatan')->with('error', 'Data kegiatan tidak ditemukan.');
    }
}