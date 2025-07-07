<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class UserController extends BaseController
{
    public function index()
    {
        $model = new UserModel();

        // Ambil filter role dari URL
        $role = $this->request->getGet('role');

        // Siapkan query dasar
        $query = $model->orderBy('id', 'DESC');

        // Jika ada filter role yang valid, tambahkan kondisi where
        if ($role && in_array($role, ['Admin', 'Guru', 'Wali Murid'])) {
            $query->where('role', $role);
        }

        // Kirim data hasil query dan filter yang dipilih ke view
        $data = [
            'users' => $query->findAll(),
            'selected_role' => $role
        ];

        return view('pages/user/index', $data);
    }

    public function new()
    {
        return view('pages/user/form');
    }

    public function create()
    {
        $rules = [
            'name' => 'required',
            'username' => 'required|is_unique[users.username]',
            'role' => 'required|in_list[Admin,Guru,Wali Murid]',
            'password' => 'required|min_length[8]',
            'pass_confirm' => 'required|matches[password]',
            'photo' => 'max_size[photo,1024]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $photoFile = $this->request->getFile('photo');
        $photoName = ''; // Kosongkan dulu
        if ($photoFile->isValid() && !$photoFile->hasMoved()) {
            $photoName = $photoFile->getRandomName();
            $photoFile->move(FCPATH . 'uploads/photos', $photoName);
        }

        $model = new UserModel();
        $model->save([
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role' => $this->request->getPost('role'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'photo' => $photoName,
        ]);

        return redirect()->to('admin/user')->with('success', 'Data User berhasil ditambahkan!');
    }

    public function edit($id = null)
    {
        $model = new UserModel();
        $data['user'] = $model->find($id);

        if (empty($data['user'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data User tidak ditemukan.');
        }

        return view('pages/user/form', $data);
    }

    public function update($id = null)
    {
        $model = new UserModel();

        $rules = [
            'name' => 'required',
            'username' => "required|is_unique[users.username,id,{$id}]",
            'role' => 'required|in_list[Admin,Guru,Wali Murid]',
            'password' => 'permit_empty|min_length[8]',
            'pass_confirm' => 'permit_empty|matches[password]',
            'photo' => 'max_size[photo,1024]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $oldData = $model->find($id);
        $dataToUpdate = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role' => $this->request->getPost('role'),
        ];

        if ($this->request->getPost('password')) {
            $dataToUpdate['password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
        }

        $photoFile = $this->request->getFile('photo');
        if ($photoFile->isValid() && !$photoFile->hasMoved()) {
            if ($oldData['photo'] && $oldData['photo'] !== 'default.png') {
                unlink(FCPATH . 'uploads/photos/' . $oldData['photo']);
            }
            $photoName = $photoFile->getRandomName();
            $photoFile->move(FCPATH . 'uploads/photos', $photoName);
            $dataToUpdate['photo'] = $photoName;
        }

        $model->update($id, $dataToUpdate);

        return redirect()->to('admin/user')->with('success', 'Data User berhasil diperbarui!');
    }

    public function delete($id = null)
    {
        if ($id == session()->get('user_id')) {
            return redirect()->to('admin/user')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $model = new UserModel();
        $user = $model->find($id);

        if ($user) {
            if ($user['role'] === 'Admin') {
                return redirect()->to('admin/user')->with('error', 'Akun dengan role Admin tidak dapat dihapus.');
            }

            try {
                if ($user['photo'] && $user['photo'] !== 'default.png') {
                    unlink(FCPATH . 'uploads/photos/' . $user['photo']);
                }
                $model->delete($id);
                return redirect()->to('admin/user')->with('success', 'Data User berhasil dihapus!');
            } catch (DatabaseException $e) {
                return redirect()->to('admin/user')->with('error', 'User tidak dapat dihapus karena terhubung dengan data siswa.');
            }
        }

        return redirect()->to('admin/user')->with('error', 'Data User tidak ditemukan.');
    }
}