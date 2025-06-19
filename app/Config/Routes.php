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