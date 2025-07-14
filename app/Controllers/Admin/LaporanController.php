<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\KehadiranModel;
use App\Models\SiswaModel; // Tambahkan
use App\Models\KegiatanModel;
use App\Models\ActivityNameModel;


class LaporanController extends BaseController
{
    public function kehadiran()
    {
        $kelasModel = new KelasModel();
        $kehadiranModel = new KehadiranModel();

        // Ambil filter dari URL, siapkan default untuk bulan ini
        $class_id = $this->request->getGet('class_id');
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');

        $data = [
            'classes' => $kelasModel->findAll(),
            'selected_class_id' => $class_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'reportData' => [],
            'dateHeaders' => []
        ];

        if ($class_id) {
            // 1. Ambil data mentah dari database
            $raw_data = $kehadiranModel
                ->select('students.full_name, students.nis, attendances.attendance_date, attendances.status')
                ->join('students', 'students.id = attendances.student_id')
                ->where('students.class_id', $class_id)
                ->where('attendances.attendance_date >=', $start_date)
                ->where('attendances.attendance_date <=', $end_date)
                ->orderBy('students.full_name', 'ASC')
                ->orderBy('attendances.attendance_date', 'ASC')
                ->findAll();

            // 2. Proses data menjadi format pivot
            $pivotedData = [];
            foreach ($raw_data as $row) {
                // Key = NIS, Value = Array data siswa dan kehadirannya
                $pivotedData[$row['nis']]['full_name'] = $row['full_name'];
                $pivotedData[$row['nis']]['attendances'][$row['attendance_date']] = $row['status'];
            }
            $data['reportData'] = $pivotedData;

            // 3. Buat header tanggal untuk tabel
            $period = new \DatePeriod(
                new \DateTime($start_date),
                new \DateInterval('P1D'),
                (new \DateTime($end_date))->modify('+1 day') // Tambah 1 hari agar tanggal akhir ikut
            );
            foreach ($period as $value) {
                $data['dateHeaders'][] = $value->format('Y-m-d');
            }
        }

        return view('pages/laporan/kehadiran', $data);
    }

    public function kegiatanSiswaSelector()
    {
        $kelasModel = new KelasModel();
        $siswaModel = new SiswaModel();

        // 1. Ambil semua kelas yang berada di tahun ajaran aktif
        $data['classes'] = $kelasModel
            ->join('academic_years', 'academic_years.id = classes.academic_year_id')
            ->where('academic_years.status', 'Aktif')
            ->select('classes.id, classes.name')
            ->orderBy('classes.name', 'ASC')
            ->findAll();

        // 2. Ambil semua siswa aktif untuk data JavaScript
        $data['all_students'] = $siswaModel
            ->select('id, class_id, full_name')
            ->where('status', 'Aktif')
            ->findAll();

        return view('pages/laporan/kegiatan_selector', $data);
    }

    public function kegiatanSiswa($student_id)
    {
        $siswaModel = new SiswaModel();
        $kegiatanModel = new KegiatanModel();
        $activityNameModel = new ActivityNameModel();

        $student = $siswaModel->find($student_id);
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Siswa tidak ditemukan.');
        }

        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');

        // 1. Ambil semua catatan kegiatan siswa pada rentang tanggal
        $recorded_activities = $kegiatanModel
            ->where('student_id', $student_id)
            ->where('activity_date >=', $start_date)
            ->where('activity_date <=', $end_date)
            ->findAll();

        // 2. Proses menjadi format yang mudah diakses: [activity_name_id][activity_date]
        $processed_records = [];
        foreach ($recorded_activities as $rec) {
            $processed_records[$rec['activity_name_id']][$rec['activity_date']] = true;
        }

        // 3. Buat header tanggal untuk tabel
        $dateHeaders = [];
        $period = new \DatePeriod(new \DateTime($start_date), new \DateInterval('P1D'), (new \DateTime($end_date))->modify('+1 day'));
        foreach ($period as $value) {
            $dateHeaders[] = $value->format('Y-m-d');
        }

        $data = [
            'student' => $student,
            'activity_names' => $activityNameModel->findAll(), // Semua nama kegiatan sebagai baris
            'processed_records' => $processed_records, // Data yang sudah diproses
            'dateHeaders' => $dateHeaders, // Header tanggal
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        return view('pages/laporan/kegiatan_siswa', $data);
    }
}