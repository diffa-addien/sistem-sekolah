<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // 2. Cek apakah ada argumen role yang dibutuhkan
        if (!empty($arguments)) {
            $userRole = session()->get('role');
            // Jika role user tidak ada di dalam daftar argumen yang diizinkan
            if (!in_array($userRole, $arguments)) {
                // Tampilkan halaman error 403 (Forbidden)
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Anda tidak memiliki izin untuk mengakses halaman ini.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa setelah request
    }
}