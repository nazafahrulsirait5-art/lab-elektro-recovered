<?php

namespace App\Controllers;

use App\Models\AlatModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;
use App\Models\AuditModel;

class Peminjaman extends BaseController
{
    /**
     * Display transactions list (Admin see all, Student see own)
     */
    public function index()
    {
        $transaksiModel = new TransaksiModel();
        $role = session()->get('role');
        
        if (in_array($role, ['admin', 'penjaga_lab', 'penjaga'])) {
            $trans_all = $transaksiModel->getTransaksiWithDetail();
            
            // Separate transactions array for Penjaga table
            $data = [
                'title'             => 'Meja Penjaga: Kelola Transaksi | E-Lab Elektro',
                'trans_menunggu'    => array_filter($trans_all, fn($t) => in_array($t['status_pinjam'], ['Menunggu Persetujuan', 'Menunggu'])),
                'trans_dipinjam'    => array_filter($trans_all, fn($t) => $t['status_pinjam'] == 'Dipinjam'),
                'trans_selesai'     => array_filter($trans_all, fn($t) => $t['status_pinjam'] == 'Dikembalikan'),
            ];
        } else {
            // Filter for student
            $username = session()->get('username');
            $transaksi = $transaksiModel->db->table('transaksi')
                ->select('transaksi.*, alat.nama_alat, alat.merk')
                ->join('alat', 'alat.id = transaksi.id_alat')
                ->where('transaksi.username', $username)
                ->orderBy('transaksi.tanggal_pinjam', 'DESC')
                ->get()->getResultArray();
                
            $data = [
                'title'     => 'Data Peminjaman | E-Lab Elektro',
                'transaksi' => $transaksi
            ];
        }

        return view('peminjaman/index', $data);
    }

    /**
     * Handle borrowing request (Mahasiswa)
     */
    public function pinjam()
    {
        $transaksiModel = new TransaksiModel();

        $id_alat_arr = $this->request->getPost('id_alat');
        $jumlah_arr = $this->request->getPost('jumlah_pinjam');
        $username = session()->get('username');

        if (empty($id_alat_arr) || !is_array($id_alat_arr)) {
            return redirect()->back()->with('error', 'Keranjang kosong atau format data salah.');
        }

        // Cek apakah mahasiswa punya tanggungan / overdue
        $overdueItems = $transaksiModel->getOverdueByUsername($username);
        if (!empty($overdueItems)) {
            return redirect()->back()->with('error', 'Peminjaman ditolak! Anda memiliki ' . count($overdueItems) . ' alat yang belum dikembalikan melewati batas waktu. Harap kembalikan terlebih dahulu.');
        }

        $batchData = [];
        $tanggal_pinjam = date('Y-m-d');
        $batas_waktu = date('Y-m-d', strtotime('+3 days'));

        // Prepare batch array
        for ($i = 0; $i < count($id_alat_arr); $i++) {
            $batchData[] = [
                'username'       => $username,
                'id_alat'        => $id_alat_arr[$i],
                'jumlah_pinjam'  => $jumlah_arr[$i],
                'tanggal_pinjam' => $tanggal_pinjam,
                'batas_waktu'    => $batas_waktu,
                'status_pinjam'  => 'Menunggu Persetujuan'
            ];
        }

        if ($transaksiModel->pinjamAlatBatch($batchData)) {
            session()->setFlashdata('clear_cart', true);
            return redirect()->to('/peminjaman')->with('success', 'Permintaan peminjaman untuk alat-alat tersebut berhasil diajukan.');
        } else {
            return redirect()->back()->with('error', 'Gagal memproses peminjaman. Stok alat mungkin tidak mencukupi atau ada kesalahan.');
        }
    }

    /**
     * Handle Return/Pengembalian (Admin/Penjaga Lab)
     */
    public function kembali($id)
    {
        $transaksiModel = new TransaksiModel();
        $alatModel = new AlatModel();
        $auditModel = new AuditModel();

        $qty_bagus = (int) $this->request->getPost('qty_bagus');
        $qty_rusak = (int) $this->request->getPost('qty_rusak');
        $denda_kerusakan = (int) $this->request->getPost('denda_kerusakan');

        $db = \Config\Database::connect();
        $db->transStart();

        $trx = $transaksiModel->find($id);
        if (!$trx) return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');

        // Validasi jumlah yang dikembalikan harus sama dengan jumlah yang dipinjam
        if (($qty_bagus + $qty_rusak) != $trx['jumlah_pinjam']) {
            return redirect()->back()->with('error', 'Gagal: Total kondisi barang harus sama dengan jumlah unit dipinjam (' . $trx['jumlah_pinjam'] . ').');
        }

        // Kalkulasi Denda
        $denda_keterlambatan = $this->_hitungDenda($trx['batas_waktu']);
        $total_denda = $denda_keterlambatan + $denda_kerusakan;

        // Handle upload foto pengembalian
        $foto_pengembalian = $this->request->getFile('foto_pengembalian');
        $nama_foto = null;

        if ($foto_pengembalian && $foto_pengembalian->isValid() && !$foto_pengembalian->hasMoved()) {
            $nama_foto = $foto_pengembalian->getRandomName();
            $foto_pengembalian->move('uploads/pengembalian/', $nama_foto);
        }

        $updateData = [
            'tanggal_kembali' => date('Y-m-d'),
            'status_pinjam'   => 'Dikembalikan',
            'denda'           => $total_denda
        ];

        if ($nama_foto) {
            $updateData['foto_pengembalian'] = $nama_foto;
        }

        // 1. Update Transaksi
        $transaksiModel->update($id, $updateData);

        // 2. Atomic Increment Stock based on condition
        $updateStokQuery = $alatModel->where('id', $trx['id_alat']);
        if ($qty_bagus > 0) {
            $updateStokQuery->set('jumlah_tersedia', 'jumlah_tersedia + ' . $qty_bagus, false);
        }
        if ($qty_rusak > 0) {
            $updateStokQuery->set('jumlah_rusak', 'jumlah_rusak + ' . $qty_rusak, false);
        }
        $updateStokQuery->update();

        // 3. Catat di Log Audit
        $alat = $alatModel->find($trx['id_alat']);
        $detailLog = "Menerima pengembalian {$trx['jumlah_pinjam']} unit {$alat['nama_alat']} dari {$trx['username']}. (Bagus: {$qty_bagus}, Rusak: {$qty_rusak}). Denda ditagihkan: Rp " . number_format($total_denda, 0, ',', '.');
        $auditModel->logAction("PENGEMBALIAN_ALAT", $detailLog);

        $db->transComplete();

        if ($db->transStatus()) {
            return redirect()->to('/peminjaman')->with('success', 'Alat berhasil dikembalikan dengan catatan kondisi yang sesuai.');
        } else {
            return redirect()->back()->with('error', 'Gagal memproses pengembalian.');
        }
    }

    /**
     * Approve borrowing (Admin)
     */
    public function setujui($id)
    {
        $transaksiModel = new TransaksiModel();
        $auditModel = new AuditModel();
        
        $transaksiModel->update($id, ['status_pinjam' => 'Dipinjam']);

        $trx = $transaksiModel->find($id);
        $auditModel->logAction("PERSETUJUAN_PINJAM", "Menyetujui peminjaman alat ID: {$trx['id_alat']} oleh user {$trx['username']} sebanyak {$trx['jumlah_pinjam']} unit.");

        return redirect()->to('/peminjaman')->with('success', 'Peminjaman disetujui.');
    }

    /**
     * Private: Hitung denda keterlambatan (Rp 5.000 / hari)
     */
    private function _hitungDenda($batas_waktu)
    {
        $tgl_kembali = date_create(date('Y-m-d'));
        $tgl_batas   = date_create($batas_waktu);
        
        $diff = date_diff($tgl_batas, $tgl_kembali);
        $hari_telat = $diff->format("%r%a");

        if ($hari_telat > 0) {
            return $hari_telat * 5000;
        }
        return 0;
    }
}
