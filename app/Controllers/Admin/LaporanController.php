<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\KehadiranModel;

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
}