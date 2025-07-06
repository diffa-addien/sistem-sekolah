<?php

namespace App\Controllers;

use App\Models\UserModel;

class AkunController extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        // Ambil data user yang sedang login dari session
        $data['user'] = $model->find(session()->get('user_id'));
        return view('akun/index', $data);
    }

    public function update()
    {
        $model = new UserModel();
        $userId = session()->get('user_id');
        $oldData = $model->find($userId);

        // Aturan validasi
        $usernameRule = ($this->request->getPost('username') == $oldData['username']) ? 'required' : 'required|is_unique[users.username]';
        $rules = [
            'name'      => 'required',
            'username'  => $usernameRule,
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
        ];

        // Hanya update password jika diisi
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

        $model->update($userId, $dataToUpdate);

        // Perbarui data session jika ada perubahan
        session()->set('name', $dataToUpdate['name']);
        if (isset($dataToUpdate['photo'])) {
            session()->set('photo', $dataToUpdate['photo']);
        }

        return redirect()->to('akun')->with('success', 'Akun Anda berhasil diperbarui!');
    }
}