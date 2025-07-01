<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TahunAjaranModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;

class KenaikanKelasController extends BaseController
{
    public function index()
    {
        $tahunAjaranModel = new TahunAjaranModel();
        $kelasModel = new KelasModel();
        $siswaModel = new SiswaModel();

        $from_year_id = $this->request->getGet('from_year_id');

        $data = [
            'inactive_years' => $tahunAjaranModel->where('status', 'Tidak Aktif')->orderBy('year', 'DESC')->limit(3)->findAll(),
            'active_year' => $tahunAjaranModel->where('status', 'Aktif')->first(),
            'source_classes' => [],
            'destination_classes' => [],
            'selected_from_year' => $from_year_id
        ];

        if ($data['active_year']) {
            $data['destination_classes'] = $kelasModel->where('academic_year_id', $data['active_year']['id'])->findAll();
        }

        if ($from_year_id) {
            $source_classes = $kelasModel->where('academic_year_id', $from_year_id)->findAll();
            foreach ($source_classes as &$class) {
                $class['students'] = $siswaModel->where('class_id', $class['id'])->where('status', 'Aktif')->findAll();
            }
            $data['source_classes'] = $source_classes;
        }

        return view('pages/kenaikan_kelas/index', $data);
    }

    public function process()
    {
        // !! TAMBAHKAN BARIS INI !!
        $db = \Config\Database::connect();

        $siswaModel = new SiswaModel();
        $actions = $this->request->getPost('actions');

        if (empty($actions)) {
            return redirect()->back()->with('error', 'Tidak ada aksi yang dipilih.');
        }

        // Gunakan variabel $db untuk transaksi
        $db->transStart();

        foreach ($actions as $student_id => $action) {
            if ($action === 'lulus') {
                var_dump($siswaModel->update($student_id, ['status' => 'Lulus', 'class_id' => null])); die;
            } elseif (is_numeric($action)) {
                $siswaModel->update($student_id, ['class_id' => $action]);
            }
        }

        $db->transComplete();

        var_dump($db->transStatus());die;

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses data.');
        }

        return redirect()->to('admin/kenaikan-kelas')->with('success', 'Proses kenaikan kelas dan kelulusan berhasil disimpan!');
    }
}
