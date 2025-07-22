<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TahunAjaranModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\EnrollmentModel;

class KenaikanKelasController extends BaseController
{
    public function index()
    {
        $tahunAjaranModel = new TahunAjaranModel();
        $kelasModel = new KelasModel();
        $enrollmentModel = new EnrollmentModel();

        $from_year_id = $this->request->getGet('from_year_id');

        $data = [
            'inactive_years' => $tahunAjaranModel->where('status', 'Tidak Aktif')->orderBy('year', 'DESC')->findAll(),
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
                $class['students'] = $enrollmentModel
                    ->select('students.id, students.full_name, students.nis, enrollments.id as enrollment_id')
                    ->join('students', 'students.id = enrollments.student_id')
                    ->where('enrollments.class_id', $class['id'])
                    ->where('enrollments.academic_year_id', $from_year_id)
                    // !! PERBAIKAN: Cek status di tabel enrollments, bukan students !!
                    ->where('enrollments.status', 'Aktif')
                    ->findAll();
            }
            // ... (logika sorting tidak perlu diubah karena sudah ada di respons sebelumnya) ...
            $data['source_classes'] = $source_classes;
        }

        return view('pages/kenaikan_kelas/index', $data);
    }

    public function process()
    {
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $kelasModel = new \App\Models\KelasModel();
        $actions = $this->request->getPost('actions');
        $to_year_id = $this->request->getPost('to_year_id');
        $from_year_id = $this->request->getPost('from_year_id');

        if (empty($actions) || empty($to_year_id) || empty($from_year_id)) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        $db = \Config\Database::connect();
        $db->transStart(); // Mulai transaksi

        foreach ($actions as $student_id => $action) {
            $source_enrollment = $enrollmentModel
                ->join('classes', 'classes.id = enrollments.class_id')
                ->where('enrollments.student_id', $student_id)
                ->where('enrollments.academic_year_id', $from_year_id)
                ->select('enrollments.*, classes.name as class_name')
                ->first();

            if (!$source_enrollment || empty($action)) {
                continue;
            }

            $new_status_for_old_enrollment = 'Tinggal Kelas'; // Default

            if (is_numeric($action)) { // Aksi adalah ID kelas tujuan
                $destination_class = $kelasModel->find($action);
                if ($destination_class) {
                    preg_match('/(\d+)/', $source_enrollment['class_name'], $source_matches);
                    preg_match('/(\d+)/', $destination_class['name'], $dest_matches);
                    $source_level = $source_matches[1] ?? 0;
                    $dest_level = $dest_matches[1] ?? 0;

                    $new_status_for_old_enrollment = ($dest_level > $source_level) ? 'Naik Kelas' : 'Tinggal Kelas';

                    // Buat atau update pendaftaran di tahun ajaran tujuan
                    $existingEnrollment = $enrollmentModel->where(['student_id' => $student_id, 'academic_year_id' => $to_year_id])->first();
                    $data = [
                        'student_id' => $student_id,
                        'class_id' => $action,
                        'academic_year_id' => $to_year_id,
                        'status' => 'Aktif'
                    ];
                    if ($existingEnrollment) {
                        $enrollmentModel->update($existingEnrollment['id'], $data);
                    } else {
                        $enrollmentModel->insert($data);
                    }
                }
            } elseif ($action === 'lulus') {
                $new_status_for_old_enrollment = 'Lulus';
            } elseif ($action === 'keluar') {
                $new_status_for_old_enrollment = 'Keluar';
            }

            // Update status pendaftaran lama
            $enrollmentModel->update($source_enrollment['id'], ['status' => $new_status_for_old_enrollment]);
        }

        $db->transComplete(); // Selesaikan transaksi

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses data.');
        }

        return redirect()->to('admin/kenaikan-kelas')->with('success', 'Proses kenaikan kelas dan kelulusan berhasil disimpan!');
    }
}