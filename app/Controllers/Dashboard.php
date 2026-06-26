<?php

namespace App\Controllers;

use App\Models\AlatModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $alatModel = new AlatModel();
        $transaksiModel = new TransaksiModel();
        $userModel = new UserModel();

        $username = session()->get('username');
        $sisaKuota = 3; // Default
        
        $overdueMahasiswa = [];
        if (session()->get('role') == 'mahasiswa') {
            // Count active borrows for this student
            $activeBorrows = $transaksiModel->where('username', $username)
                                            ->whereIn('status_pinjam', ['Menunggu', 'Menunggu Persetujuan', 'Dipinjam'])
                                            ->countAllResults();
            $sisaKuota = max(0, 3 - $activeBorrows);
            $overdueMahasiswa = $transaksiModel->getOverdueByUsername($username);
        }

        // Get total stok keseluruhan vs stok rusak dengan aman
        $stokBagusQuery = $alatModel->selectSum('jumlah_tersedia')->first();
        $totalStokKeseluruhan = $stokBagusQuery ? ($stokBagusQuery['jumlah_tersedia'] ?? 0) : 0;

        $stokRusakQuery = $alatModel->selectSum('jumlah_rusak')->first();
        $totalStokRusak = $stokRusakQuery ? ($stokRusakQuery['jumlah_rusak'] ?? 0) : 0;

        $dendaQuery = $transaksiModel->selectSum('denda')->first();
        $totalDenda = $dendaQuery ? ($dendaQuery['denda'] ?? 0) : 0;

        $modulModel = new \App\Models\ModulPraktikumModel();

        $data = [
            'title'                   => 'Dashboard | E-Lab Elektro',
            'total_alat'              => $alatModel->countAllResults(),
            'total_tersedia'          => $alatModel->where('status', 'Tersedia')->countAllResults(),
            'pinjam_aktif'            => $transaksiModel->where('status_pinjam', 'Dipinjam')->countAllResults(),
            'total_mahasiswa'         => $userModel->where('role', 'mahasiswa')->countAllResults(),
            'total_users'             => $userModel->countAllResults(),
            'menunggu_persetujuan'    => $transaksiModel->where('status_pinjam', 'Menunggu Persetujuan')->countAllResults(),
            'total_denda'             => $totalDenda,
            'transaksi_latest'        => $transaksiModel->getTransaksiWithDetail(),
            'alat'                    => $alatModel->findAll(),
            'sisa_kuota'              => $sisaKuota,
            'overdue_mahasiswa'       => $overdueMahasiswa,
            'all_overdue'             => $transaksiModel->getAllOverdue(),
            'top_tools'               => $transaksiModel->getTopBorrowedTools(),
            'total_stok_bagus'        => $totalStokKeseluruhan,
            'total_stok_rusak'        => $totalStokRusak,
            'modul_praktikum'         => $modulModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('dashboard/index', $data);
    }
}
