<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $data['users'] = $model->orderBy('id', 'DESC')->findAll();
        return view('pages/user/index', $data);
    }

    public function new()
    {
        return view('pages/user/form');
    }

    public function create()
    {
        $rules = [
            'name'      => 'required',
            'username'  => 'required|is_unique[users.username]',
            'role'      => 'required|in_list[Admin,Guru,Wali Murid]',
            'password'  => 'required|min_length[8]',
            'pass_confirm' => 'required|matches[password]',
            'photo'     => 'max_size[photo,1024]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $photoFile = $this->request->getFile('photo');
        $photoName = 'default.png';
        if ($photoFile->isValid() && !$photoFile->hasMoved()) {
            $photoName = $photoFile->getRandomName();
            $photoFile->move(FCPATH . 'uploads/photos', $photoName);
        }

        $model = new UserModel();
        $model->save([
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'photo'    => $photoName,
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
        $oldData = $model->find($id);

        $usernameRule = ($this->request->getPost('username') == $oldData['username']) ? 'required' : 'required|is_unique[users.username]';
        
        $rules = [
            'name'      => 'required',
            'username'  => $usernameRule,
            'role'      => 'required|in_list[Admin,Guru,Wali Murid]',
            'password'  => 'permit_empty|min_length[8]',
            'pass_confirm' => 'permit_empty|matches[password]',
            'photo'     => 'max_size[photo,1024]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataToUpdate = [
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
        ];
        
        // Update password jika diisi
        if ($this->request->getPost('password')) {
            $dataToUpdate['password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
        }
        
        // Proses upload foto baru jika ada
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
        $model = new UserModel();
        $user = $model->find($id);

        if ($user) {
            // Hapus foto jika bukan default
            if ($user['photo'] && $user['photo'] !== 'default.png') {
                unlink(FCPATH . 'uploads/photos/' . $user['photo']);
            }
            $model->delete($id);
            return redirect()->to('admin/user')->with('success', 'Data User berhasil dihapus!');
        }

        return redirect()->to('admin/user')->with('error', 'Data User tidak ditemukan.');
    }
}