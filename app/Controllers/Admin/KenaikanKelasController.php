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
        $enrollmentModel = new EnrollmentModel();
        $actions = $this->request->getPost('actions');
        $to_year_id = $this->request->getPost('to_year_id');
        $from_year_id = $this->request->getPost('from_year_id');


        if (empty($actions) || empty($to_year_id)) {
            return redirect()->back()->with('error', 'Tidak ada aksi yang dipilih atau tahun ajaran tujuan tidak valid.');
        }

        $db = \Config\Database::connect();
        $db->transStart(); // Mulai transaksi

        foreach ($actions as $student_id => $action) {
            if (empty($action))
                continue; // Lewati jika aksinya "-- Belum diatur --"

            $source_enrollment = $enrollmentModel
                ->where('student_id', $student_id)
                ->where('academic_year_id', $from_year_id)
                ->first();

            if ($action === 'lulus') {
                if ($source_enrollment) {
                    $enrollmentModel->update($source_enrollment['id'], ['status' => 'Lulus']);
                }
            } elseif (is_numeric($action)) {
                $destination_class_id = $action;

                // Logika "Upsert": update jika sudah ada, insert jika belum
                $existingEnrollment = $enrollmentModel->where([
                    'student_id' => $student_id,
                    'academic_year_id' => $to_year_id
                ])->first();

                $data = [
                    'student_id' => $student_id,
                    'class_id' => $destination_class_id,
                    'academic_year_id' => $to_year_id,
                    'status' => 'Aktif'
                ];

                if ($existingEnrollment) {
                    $enrollmentModel->update($existingEnrollment['id'], $data);
                } else {
                    $enrollmentModel->insert($data);
                }

                // Update status enrollment lama menjadi 'Naik Kelas' atau 'Tinggal Kelas'
                if ($source_enrollment) {
                    // Anda bisa menambahkan logika lebih kompleks di sini jika perlu
                    // Untuk saat ini, kita biarkan status lama menjadi 'Aktif' saja sudah cukup sebagai riwayat
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses data.');
        }

        return redirect()->to('admin/kenaikan-kelas')->with('success', 'Proses kenaikan kelas dan kelulusan berhasil disimpan!');
    }
}