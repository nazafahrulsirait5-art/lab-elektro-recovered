<?php

namespace App\Controllers;

use App\Models\ModulPraktikumModel;

class Praktikum extends BaseController
{
    public function index()
    {
        $role = session()->get('role');
        
        // Hanya Admin dan Penjaga Lab yang boleh masuk halaman manajemen modul
        if (!in_array($role, ['admin', 'penjaga_lab', 'penjaga'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $modulModel = new ModulPraktikumModel();
        
        $data = [
            'title' => 'Manajemen Modul Praktikum | E-Lab Elektro',
            'modul' => $modulModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('praktikum/admin', $data);
    }

    public function store()
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'penjaga_lab', 'penjaga'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $modulModel = new ModulPraktikumModel();

        $validationRule = [
            'judul' => 'required|min_length[3]',
            'file_modul' => [
                'label' => 'File Modul',
                'rules' => 'uploaded[file_modul]|max_size[file_modul,5120]|ext_in[file_modul,pdf,xls,xlsx,jpg,jpeg,png]|mime_in[file_modul,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/jpeg,image/png]', // Maksimal 5MB & validasi MIME
            ],
        ];

        if (!$this->validate($validationRule)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors()['file_modul'] ?? 'Gagal mengupload. Pastikan ukuran file maksimal 5MB.');
        }

        $file = $this->request->getFile('file_modul');
        $ext = strtolower($file->getClientExtension());
        $allowed = ['pdf', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            return redirect()->back()->withInput()->with('error', 'Format file tidak diizinkan. Hanya PDF, XLS, XLSX, JPG, atau PNG yang diperbolehkan.');
        }

        if ($file->isValid() && !$file->hasMoved()) {
            $randomString = bin2hex(random_bytes(10));
            $newName = $randomString . '.' . $ext;
            $file->move(FCPATH . 'uploads/modul', $newName);

            $modulModel->insert([
                'judul'      => $this->request->getPost('judul'),
                'file_modul' => $newName,
                'created_by' => session()->get('username'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->to('/praktikum')->with('success', 'Modul berhasil diupload.');
        }

        return redirect()->back()->with('error', 'Gagal mengupload file.');
    }

    public function delete($id)
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'penjaga_lab', 'penjaga'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $modulModel = new ModulPraktikumModel();
        $modul = $modulModel->find($id);

        if ($modul) {
            $filePath = FCPATH . 'uploads/modul/' . $modul['file_modul'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $modulModel->delete($id);
            return redirect()->to('/praktikum')->with('success', 'Modul berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Modul tidak ditemukan.');
    }
}
