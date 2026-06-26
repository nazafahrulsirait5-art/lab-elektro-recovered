<?php

namespace App\Controllers;

use App\Models\AlatModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;

class UcdShowcase extends BaseController
{
    public function index()
    {
        $alatModel = new AlatModel();
        $transaksiModel = new TransaksiModel();
        $userModel = new UserModel();

        // Get basic database info to display in the mockup sections
        $data = [
            'title'               => 'UCD & Heuristic Showcase | E-Lab Elektro',
            'db_total_alat'       => $alatModel->countAllResults(),
            'db_total_tersedia'   => $alatModel->where('status', 'Tersedia')->countAllResults(),
            'db_total_rusak'      => $alatModel->selectSum('jumlah_rusak')->first()['jumlah_rusak'] ?? 0,
            'db_total_mahasiswa'  => $userModel->where('role', 'mahasiswa')->countAllResults(),
            'db_active_loans'     => $transaksiModel->where('status_pinjam', 'Dipinjam')->countAllResults(),
        ];

        return view('ucd_showcase/index', $data);
    }
}
