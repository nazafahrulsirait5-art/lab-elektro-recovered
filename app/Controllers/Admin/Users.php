<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    /**
     * Display all users
     */
    public function index()
    {
        $userModel = new UserModel();
        
        $data = [
            'title' => 'Manajemen Pengguna | E-Lab Elektro',
            'users' => $userModel->findAll()
        ];

        return view('admin/users/index', $data);
    }

    /**
     * Add new user (including email)
     */
    public function store()
    {
        $userModel = new UserModel();
        
        $data = [
            'username'     => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => $this->request->getPost('role'),
            'status_akun'  => 'aktif'
        ];

        $userModel->insert($data);

        return redirect()->to('/users')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Update user data (nama, email, role, status, password opsional)
     */
    public function update($id)
    {
        $userModel = new UserModel();

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'role'         => $this->request->getPost('role'),
            'status_akun'  => $this->request->getPost('status_akun'),
        ];

        // Hanya update password jika diisi
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userModel->update($id, $data);

        return redirect()->to('/users')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $userModel = new UserModel();
        $userModel->delete($id);

        return redirect()->to('/users')->with('success', 'User berhasil dihapus.');
    }
}
