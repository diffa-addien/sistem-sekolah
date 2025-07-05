<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function () {
    return redirect()->to('/admin/dashboard');
});

/*
 * --------------------------------------------------------------------
 * Admin Routes
 * --------------------------------------------------------------------
 */
$routes->group('admin', static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Pakai Auto Method
    $routes->resource('tahun-ajaran', [
        'controller' => '\App\Controllers\Admin\TahunAjaranController'
    ]);
    $routes->resource('kelas', [
        'controller' => '\App\Controllers\Admin\KelasController'
    ]);
    $routes->resource('siswa', [
        'controller' => '\App\Controllers\Admin\SiswaController'
    ]);
    $routes->resource('user', [
        'controller' => '\App\Controllers\Admin\UserController'
    ]);

    // Rute untuk Kehadiran
    $routes->get('kehadiran', 'Admin\KehadiranController::index');
    $routes->post('kehadiran/simpan', 'Admin\KehadiranController::store');
    $routes->get('laporan/kehadiran', 'Admin\LaporanController::kehadiran');

    $routes->resource('nama-kegiatan', [
        'controller' => '\App\Controllers\Admin\ActivityNameController'
    ]);
    $routes->resource('kegiatan', [
        'controller' => '\App\Controllers\Admin\KegiatanController'
    ]);

    $routes->get('kenaikan-kelas', 'Admin\KenaikanKelasController::index');
    $routes->post('kenaikan-kelas/proses', 'Admin\KenaikanKelasController::process');
});

// Rute untuk API
$routes->group('api', static function ($routes) {
    // Endpoint utama yang dipanggil Arduino setiap kali ada tap kartu
    $routes->post('tap', 'Api::processTap');

    // Endpoint yang digunakan untuk mekanisme 'scan' pada form web
    $routes->post('store-scan', 'Api::storeScannedUid');
    $routes->get('check-scan', 'Api::checkScannedUid');
});