<?php

namespace App\Controllers;

use App\Models\AlatModel;

class Aset extends BaseController
{
    /**
     * Display list of laboratory equipment
     */
    public function index()
    {
        $alatModel = new AlatModel();
        
        $data = [
            'title' => 'Inventaris Alat | E-Lab Elektro',
            'alat'  => $alatModel->findAll()
        ];

        return view('aset/index', $data);
    }

    /**
     * Add new equipment to database
     */
    public function store()
    {
        $alatModel = new AlatModel();

        $data = [
            'nama_alat'          => $this->request->getPost('nama_alat'),
            'merk'               => $this->request->getPost('merk'),
            'jumlah_total'       => $this->request->getPost('jumlah_total'),
            'jumlah_tersedia'    => $this->request->getPost('jumlah_total'), // Awalnya tersedia = total
            'status'             => 'Tersedia'
        ];

        $alatModel->insert($data);

        $auditModel = new \App\Models\AuditModel();
        $auditModel->logAction("TAMBAH_ASET", "Menambahkan alat baru: {$data['nama_alat']} sebanyak {$data['jumlah_total']} unit.");

        return redirect()->to('/alat')->with('success', 'Alat berhasil ditambahkan.');
    }

    /**
     * Update equipment data
     */
    public function update($id)
    {
        $alatModel = new AlatModel();
        $old_alat = $alatModel->find($id);
        
        $data = [
            'nama_alat'          => $this->request->getPost('nama_alat'),
            'merk'               => $this->request->getPost('merk'),
            'jumlah_total'       => (int) $this->request->getPost('jumlah_total'),
            'jumlah_maintenance' => (int) $this->request->getPost('jumlah_maintenance'),
            'jumlah_rusak'       => (int) $this->request->getPost('jumlah_rusak'),
        ];

        // Hitung selisih perubahan dari masing-masing kategori
        $delta_total = $data['jumlah_total'] - (int) $old_alat['jumlah_total'];
        $delta_mt    = $data['jumlah_maintenance'] - (int) $old_alat['jumlah_maintenance'];
        $delta_rusak = $data['jumlah_rusak'] - (int) $old_alat['jumlah_rusak'];

        // Menghitung jumlah_tersedia baru dengan delta 
        // Mengapa delta? Agar tidak menghapus barang yang statusnya saat ini 'Dipinjam'
        $new_tersedia = (int) $old_alat['jumlah_tersedia'] + $delta_total - $delta_mt - $delta_rusak;

        // Validasi pencegah inkonsistensi
        if ($new_tersedia < 0) {
            return redirect()->back()->with('error', 'Gagal menyimpan: Stok rusak/maintenance melebihi unit yang tersedia di lab (mungkin sedang dipinjam).');
        }

        $data['jumlah_tersedia'] = $new_tersedia;
        $data['status'] = ($data['jumlah_tersedia'] > 0) ? 'Tersedia' : 'Habis';

        $alatModel->update($id, $data);

        $auditModel = new \App\Models\AuditModel();
        $auditModel->logAction("UBAH_ASET", "Mengubah data alat ID: {$id} ({$data['nama_alat']}). Total: {$data['jumlah_total']}, Maintenance: {$data['jumlah_maintenance']}, Rusak: {$data['jumlah_rusak']}.");

        return redirect()->to('/alat')->with('success', 'Data alat berhasil diperbarui.');
    }

    /**
     * Delete equipment
     */
    public function delete($id)
    {
        $alatModel = new AlatModel();
        $alat = $alatModel->find($id);
        
        $alatModel->delete($id);

        $auditModel = new \App\Models\AuditModel();
        if ($alat) {
            $auditModel->logAction("HAPUS_ASET", "Menghapus alat: {$alat['nama_alat']} (ID: {$id}).");
        }

        return redirect()->to('/alat')->with('success', 'Alat berhasil dihapus.');
    }

    /**
     * Generate QR Code for an equipment
     */
    public function generateQr($id)
    {
        $alatModel = new AlatModel();
        $alat = $alatModel->find($id);

        if (!$alat) return "Alat tidak ditemukan.";

        // Ganti Google Charts API (sudah deprecated) dengan qrserver.com API
        $qrData = "ELAB-ALAT-" . $alat['id'];
        $qrUrl  = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrData) . "&format=png&ecc=M";

        $data = [
            'title' => 'Cetak QR Code - ' . $alat['nama_alat'],
            'alat'  => $alat,
            'qrUrl' => $qrUrl
        ];

        return view('aset/print_qr', $data);
    }
}
