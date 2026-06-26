<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profil extends BaseController
{
    public function index()
    {
        $userModel = new \App\Models\UserModel();
        $transaksiModel = new \App\Models\TransaksiModel();
        
        $userId = session()->get('id');
        $username = session()->get('username');
        $user = $userModel->find($userId);

        $totalTransaksi = $transaksiModel->where('username', $username)->countAllResults();
        
        $activeBorrows = $transaksiModel->where('username', $username)
                                        ->whereIn('status_pinjam', ['Menunggu', 'Menunggu Persetujuan', 'Dipinjam'])
                                        ->countAllResults();
        $sisaKuota = max(0, 3 - $activeBorrows);

        $data = [
            'title' => 'Profil Saya | E-Lab Elektro',
            'user'  => $user,
            'total_transaksi' => $totalTransaksi,
            'sisa_kuota' => $sisaKuota
        ];

        return view('profil/index', $data);
    }

    public function uploadFoto()
    {
        $userModel = new UserModel();
        $id = session()->get('id');

        $base64 = $this->request->getPost('foto_base64');
        if (!$base64) {
            return redirect()->to('/profil')->with('error', 'Tidak ada foto yang diterima.');
        }

        // Decode base64 → binary
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
        $fileName = 'profil_' . $id . '_' . time() . '.jpg';
        $savePath = ROOTPATH . 'public/uploads/profil/' . $fileName;

        // Ensure directory exists
        if (!is_dir(ROOTPATH . 'public/uploads/profil')) {
            mkdir(ROOTPATH . 'public/uploads/profil', 0755, true);
        }

        file_put_contents($savePath, $imageData);

        // Delete old photo if not default
        $user = $userModel->find($id);
        if (!empty($user['foto_profil']) && $user['foto_profil'] !== 'default.png') {
            $oldPath = ROOTPATH . 'public/uploads/profil/' . $user['foto_profil'];
            if (file_exists($oldPath)) @unlink($oldPath);
        }

        $userModel->update($id, ['foto_profil' => $fileName]);
        session()->set('foto_profil', $fileName);

        return redirect()->to('/profil')->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function deleteFoto()
    {
        $userModel = new UserModel();
        $id = session()->get('id');
        $user = $userModel->find($id);

        if (!empty($user['foto_profil']) && $user['foto_profil'] !== 'default.png') {
            $path = ROOTPATH . 'public/uploads/profil/' . $user['foto_profil'];
            if (file_exists($path)) @unlink($path);
        }

        $userModel->update($id, ['foto_profil' => null]);
        session()->set('foto_profil', null);

        return redirect()->to('/profil')->with('success', 'Foto profil berhasil dihapus.');
    }

    public function update()
    {
        $userModel = new UserModel();
        $id = session()->get('id');

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
        ];

        // Handle profile photo upload
        $file = $this->request->getFile('foto_profil');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/profil', $newName);
            $data['foto_profil'] = $newName;
            
            // Update session
            session()->set('foto_profil', $newName);
        }

        $userModel->update($id, $data);
        
        // Update session name
        session()->set('nama_lengkap', $data['nama_lengkap']);

        return redirect()->to('/profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword()
    {
        $userModel = new UserModel();
        $id = session()->get('id');
        $user = $userModel->find($id);

        $oldPassword = $this->request->getPost('old_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!password_verify($oldPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama tidak sesuai.');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        $userModel->update($id, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        return redirect()->to('/profil')->with('success', 'Password berhasil diubah.');
    }
}
