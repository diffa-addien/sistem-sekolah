<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\UserModel;
use App\Models\KehadiranModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $siswaModel = new SiswaModel();
        $kelasModel = new KelasModel();
        $userModel = new UserModel();
        $kehadiranModel = new KehadiranModel();

        // 1. Data untuk Kartu Statistik
        $data['stats'] = [
            'total_students' => $siswaModel->where('status', 'Aktif')->countAllResults(),
            'total_classes' => $kelasModel->join('academic_years', 'academic_years.id = classes.academic_year_id')
                ->where('academic_years.status', 'Aktif')
                ->countAllResults(),
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

        // Proses data mentah menjadi format yang siap dipakai oleh ApexCharts
        $chart_data = [];
        $categories = [];
        $series = [
            ['name' => 'Hadir', 'data' => []],
            ['name' => 'Sakit', 'data' => []],
            ['name' => 'Izin', 'data' => []]
        ];

        // Inisialisasi data 7 hari dengan nilai 0
        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $categories[$date] = date('D, d M', strtotime($date)); // Format: Sun, 05 Jul
            $chart_data[$date] = ['Hadir' => 0, 'Sakit' => 0, 'Izin' => 0];
        }

        // Isi data dari database
        foreach ($attendance_raw as $row) {
            if (isset($chart_data[$row['attendance_date']][$row['status']])) {
                $chart_data[$row['attendance_date']][$row['status']] = (int)$row['total'];
            }
        }

        // Urutkan data sesuai urutan tanggal
        ksort($chart_data);
        ksort($categories);

        foreach ($chart_data as $data_point) {
            $series[0]['data'][] = $data_point['Hadir'];
            $series[1]['data'][] = $data_point['Sakit'];
            $series[2]['data'][] = $data_point['Izin'];
        }

        $data['attendanceChart'] = [
            'categories' => array_values($categories),
            'series' => $series
        ];

        // Mengirim data ke view
        return view('pages/dashboard', $data);
    }
}
