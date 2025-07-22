<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\UserModel;
use App\Models\KehadiranModel;
use App\Models\EnrollmentModel;
use App\Models\TahunAjaranModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $siswaModel = new SiswaModel();
        $kelasModel = new KelasModel();
        $userModel = new UserModel();
        $kehadiranModel = new KehadiranModel();
        $enrollmentModel = new EnrollmentModel();
        $tahunAjaranModel = new TahunAjaranModel();

        // Cari tahun ajaran yang aktif
        $activeYear = $tahunAjaranModel->where('status', 'Aktif')->first();
        $activeYearId = $activeYear ? $activeYear['id'] : 0;

        // 1. Data untuk Kartu Statistik (dengan logika baru)
        $data['stats'] = [
            // Hitung siswa yang memiliki pendaftaran 'Aktif' di tahun ajaran aktif
            'total_students' => $enrollmentModel->where('academic_year_id', $activeYearId)->where('status', 'Aktif')->countAllResults(),
            // Hitung kelas yang ada di tahun ajaran aktif
            'total_classes' => $kelasModel->where('academic_year_id', $activeYearId)->countAllResults(),
            'total_teachers' => $userModel->where('role', 'Guru')->countAllResults(),
            'total_parents' => $userModel->where('role', 'Wali Murid')->countAllResults(),
        ];

        // 2. Data untuk Grafik Kehadiran 7 Hari Terakhir
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-6 days'));

        $attendance_raw = $kehadiranModel
            ->select('attendance_date, status, COUNT(id) as total')
            ->where('attendance_date >=', $startDate)
            ->where('attendance_date <=', $endDate)
            ->groupBy(['attendance_date', 'status'])
            ->findAll();

        // ... (sisa logika pemrosesan grafik tidak berubah)
        $chart_data = [];
        $categories = [];
        $series = [
            ['name' => 'Hadir', 'data' => []],
            ['name' => 'Sakit', 'data' => []],
            ['name' => 'Izin', 'data' => []]
        ];
        for ($i = 6; $i >= 0; $i--) { // Urutan dari kiri ke kanan (lama ke baru)
            $date = date('Y-m-d', strtotime("-$i days"));
            $categories[$date] = date('D, d M', strtotime($date));
            $chart_data[$date] = ['Hadir' => 0, 'Sakit' => 0, 'Izin' => 0];
        }
        foreach ($attendance_raw as $row) {
            if (isset($chart_data[$row['attendance_date']][$row['status']])) {
                $chart_data[$row['attendance_date']][$row['status']] = (int) $row['total'];
            }
        }
        foreach ($chart_data as $data_point) {
            $series[0]['data'][] = $data_point['Hadir'];
            $series[1]['data'][] = $data_point['Sakit'];
            $series[2]['data'][] = $data_point['Izin'];
        }
        $data['attendanceChart'] = [
            'categories' => array_values($categories),
            'series' => $series
        ];

        return view('pages/dashboard', $data);
    }
}
