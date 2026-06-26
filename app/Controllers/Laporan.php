<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use App\Models\UserModel;

class Laporan extends BaseController
{
    /**
     * Generate Laboratory Clearance Letter (Surat Bebas Lab)
     */
    public function suratBebas($username = null)
    {
        if (!$username) {
            $username = session()->get('username');
        }

        $userModel = new UserModel();
        $transaksiModel = new TransaksiModel();

        $user = $userModel->getUserByUsername($username);
        
        // Cek tanggungan peminjaman
        $tanggungan = $transaksiModel->where('username', $username)
                                     ->where('status_pinjam', 'Dipinjam')
                                     ->findAll();

        $data = [
            'title'      => 'Surat Bebas Laboratorium',
            'user'       => $user,
            'is_bebas'   => empty($tanggungan),
            'tanggungan' => $tanggungan,
            'date_now'   => date('d F Y')
        ];

        return view('laporan/surat_bebas', $data);
    }

    /**
     * Inventory Report Export
     */
    public function inventaris()
    {
        $alatModel = new \App\Models\AlatModel();
        
        $data = [
            'title' => 'Laporan Rekapitulasi Inventaris | E-Lab Elektro',
            'alat'  => $alatModel->findAll()
        ];
        
        return view('laporan/inventaris', $data);
    }

    /**
     * Kaprodi Analytics Dashboard
     */
    public function analitikDashboard()
    {
        return redirect()->to('/laporan/analitik');
    }

    /**
     * Kaprodi Analytics & Metrics View
     */
    public function analitik()
    {
        $transaksiModel = new TransaksiModel();
        $db = \Config\Database::connect();

        // Base query for transactions
        $builder = $db->table('transaksi');
        $builder->select('transaksi.*, users.nama_lengkap, alat.nama_alat');
        $builder->join('users', 'users.username = transaksi.username');
        $builder->join('alat', 'alat.id = transaksi.id_alat');

        // Total metrics
        $total_transaksi = $db->table('transaksi')->countAllResults();
        $sedang_dipinjam = $db->table('transaksi')->where('status_pinjam', 'Dipinjam')->countAllResults();
        $dikembalikan   = $db->table('transaksi')->whereIn('status_pinjam', ['Selesai', 'Dikembalikan'])->countAllResults();
        
        // Sum Denda
        $sum_denda_query = $db->table('transaksi')->selectSum('denda')->get()->getRow();
        $total_denda = $sum_denda_query->denda ?? 0;

        // Alat Terpopuler
        $populer = $db->table('transaksi')
            ->select('alat.nama_alat, COUNT(transaksi.id_alat) as total_pinjam')
            ->join('alat', 'alat.id = transaksi.id_alat')
            ->groupBy('transaksi.id_alat')
            ->orderBy('total_pinjam', 'DESC')
            ->limit(1)
            ->get()->getRow();

        // Peminjam Teraktif
        $teraktif = $db->table('transaksi')
            ->select('users.nama_lengkap, COUNT(transaksi.username) as total_transaksi')
            ->join('users', 'users.username = transaksi.username')
            ->groupBy('transaksi.username')
            ->orderBy('total_transaksi', 'DESC')
            ->limit(1)
            ->get()->getRow();

        // Chart Data: Distribusi Kondisi Alat
        $alat_dist = $db->table('alat')
            ->selectSum('jumlah_tersedia')
            ->selectSum('jumlah_maintenance')
            ->selectSum('jumlah_rusak')
            ->get()->getRowArray();
            
        // Chart Data: Peminjaman 6 bulan terakhir (dummy logic for mockup if real data is sparse)
        $chart_denda = [15000, 30000, 10000, 50000, 20000, 0];
        $chart_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];

        // Apply filters
        $tgl_mulai = $this->request->getGet('tgl_mulai');
        $tgl_sampai = $this->request->getGet('tgl_sampai');
        $status = $this->request->getGet('status');
        $keyword = $this->request->getGet('keyword');

        if (!empty($tgl_mulai) && !empty($tgl_sampai)) {
            $builder->where('tanggal_pinjam >=', $tgl_mulai);
            $builder->where('tanggal_pinjam <=', $tgl_sampai);
        }
        if (!empty($status) && $status != 'Semua Status') {
            $builder->where('status_pinjam', $status);
        }
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('users.nama_lengkap', $keyword)
                    ->orLike('alat.nama_alat', $keyword)
                    ->orLike('transaksi.username', $keyword) // NIM is stored as username
                    ->groupEnd();
        }

        $transaksi = $builder->orderBy('transaksi.id', 'DESC')->get()->getResultArray();

        $data = [
            'title' => 'Laporan & Analitik | E-Lab Elektro',
            'transaksi' => $transaksi,
            'total_transaksi' => $total_transaksi,
            'sedang_dipinjam' => $sedang_dipinjam,
            'dikembalikan' => $dikembalikan,
            'total_denda' => $total_denda,
            'alat_populer' => $populer ? $populer->nama_alat . ' ('.$populer->total_pinjam.'x dipinjam)' : '-',
            'peminjam_aktif' => $teraktif ? $teraktif->nama_lengkap . ' ('.$teraktif->total_transaksi.'x transaksi)' : '-',
            'chart_alat' => [
                'tersedia' => $alat_dist['jumlah_tersedia'] ?? 0,
                'maintenance' => $alat_dist['jumlah_maintenance'] ?? 0,
                'rusak' => $alat_dist['jumlah_rusak'] ?? 0
            ],
            'chart_denda' => json_encode($chart_denda),
            'chart_bulan' => json_encode($chart_bulan)
        ];

        return view('laporan/analitik', $data);
    }
}
