<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            if (session()->get('role') === 'Admin' || session()->get('role') === 'Guru') {
                return redirect()->to('admin/dashboard');
            } elseif (session()->get('role') === 'Wali Murid') {
                // !! UBAH TUJUAN REDIRECT !!
                return redirect()->to('wali/dashboard');
            }
        }
        return view('auth/login');
    }

    public function processLogin()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Jika login berhasil, simpan data ke session
            $sessionData = [
                'user_id'    => $user['id'],
                'name'       => $user['name'],
                'username'   => $user['username'],
                'role'       => $user['role'],
                'photo'      => $user['photo'],
                'isLoggedIn' => true,
            ];
            session()->set($sessionData);

            // Redirect berdasarkan role
            if ($user['role'] === 'Admin' || $user['role'] === 'Guru') {
                return redirect()->to('admin/dashboard');
            } elseif ($user['role'] === 'Wali Murid') {
                return redirect()->to('wali/dashboard');
            }
        }

        // Jika login gagal
        return redirect()->to('login')->with('error', 'Username atau Password salah.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login')->with('success', 'Anda berhasil logout.');
    }
}
