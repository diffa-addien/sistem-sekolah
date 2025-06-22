<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/dashboard', 'Dashboard::index');

// Rute untuk manajemen siswa (menggunakan Siswa.php)
$routes->get('/daftar-siswa', 'Siswa::daftarSiswa');
$routes->get('/tambah-siswa', 'Siswa::tambahSiswa');

/*
 * --------------------------------------------------------------------
 * Admin Routes
 * --------------------------------------------------------------------
 */
$routes->group('admin', static function ($routes) {
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
});