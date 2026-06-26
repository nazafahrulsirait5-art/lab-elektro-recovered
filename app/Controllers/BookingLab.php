<?php

namespace App\Controllers;

use App\Models\BookingLabModel;

class BookingLab extends BaseController
{
    // Halaman Mahasiswa
    public function index()
    {
        $role = session()->get('role');
        if ($role != 'mahasiswa') {
            return redirect()->to('/dashboard')->with('error', 'Hanya mahasiswa yang dapat melakukan peminjaman ruangan lab.');
        }

        $bookingModel = new BookingLabModel();
        
        $data = [
            'title' => 'Booking Laboratorium | E-Lab Elektro',
            'bookings' => $bookingModel->where('tanggal >=', date('Y-m-d'))
                                       ->orderBy('tanggal', 'ASC')
                                       ->orderBy('jam_mulai', 'ASC')
                                       ->findAll()
        ];

        return view('booking_lab/index', $data);
    }

    // Proses Submit Booking Mahasiswa
    public function store()
    {
        $role = session()->get('role');
        if ($role != 'mahasiswa') {
            return redirect()->to('/dashboard')->with('error', 'Silakan login sebagai mahasiswa terlebih dahulu.');
        }

        $bookingModel = new BookingLabModel();

        $tanggal = $this->request->getPost('tanggal');
        $jam_mulai = $this->request->getPost('jam_mulai');
        $jam_selesai = $this->request->getPost('jam_selesai');
        $keperluan = $this->request->getPost('keperluan');

        // Validasi input
        if (!$tanggal || !$jam_mulai || !$jam_selesai || !$keperluan) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi semua form booking.');
        }

        if ($jam_mulai >= $jam_selesai) {
            return redirect()->back()->withInput()->with('error', 'Jam selesai harus lebih besar dari jam mulai.');
        }

        if ($tanggal < date('Y-m-d')) {
            return redirect()->back()->withInput()->with('error', 'Tidak bisa meminjam di tanggal yang sudah lewat.');
        }

        // Validasi Jam Operasional Kampus (07:00 - 18:00)
        if ($jam_mulai < '07:00' || $jam_selesai > '18:00') {
            return redirect()->back()->withInput()->with('error', 'Peminjaman di luar jam operasional (07:00 - 18:00) tidak diizinkan.');
        }

        // Cek Bentrok (Overlapping)
        if ($bookingModel->isScheduleConflict($tanggal, $jam_mulai, $jam_selesai)) {
            return redirect()->back()->withInput()->with('error', 'Maaf, jadwal pada tanggal dan jam tersebut sudah terisi (bentrok). Silakan pilih waktu lain.');
        }

        // Jika lolos, simpan
        $bookingModel->insert([
            'username'    => session()->get('username'),
            'tanggal'     => $tanggal,
            'jam_mulai'   => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'keperluan'   => $keperluan,
            'status'      => 'Menunggu Persetujuan',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/booking')->with('success', 'Pengajuan booking berhasil dikirim. Silakan tunggu persetujuan admin.');
    }

    // Halaman Admin/Penjaga
    public function admin()
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'penjaga_lab', 'penjaga'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $bookingModel = new BookingLabModel();

        $data = [
            'title' => 'Manajemen Booking Lab | E-Lab Elektro',
            'bookings' => $bookingModel->getBookingsWithUser()
        ];

        return view('booking_lab/admin', $data);
    }

    // Aksi Setujui/Tolak oleh Admin
    public function action($id, $status)
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'penjaga_lab', 'penjaga'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        if (!in_array($status, ['Disetujui', 'Ditolak', 'Selesai'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $bookingModel = new BookingLabModel();
        
        // Pastikan tidak bentrok jika disetujui
        if ($status == 'Disetujui') {
            $booking = $bookingModel->find($id);
            // Cek conflict selain id ini sendiri
            $conflictCount = $bookingModel->where('id !=', $id)
                                          ->where('tanggal', $booking['tanggal'])
                                          ->whereIn('status', ['Menunggu Persetujuan', 'Disetujui'])
                                          ->groupStart()
                                            ->where("jam_mulai <", $booking['jam_selesai'])
                                            ->where("jam_selesai >", $booking['jam_mulai'])
                                          ->groupEnd()
                                          ->countAllResults();
            if ($conflictCount > 0) {
                return redirect()->back()->with('error', 'Tidak bisa disetujui karena jadwal ini bentrok dengan booking lain.');
            }
        }

        $bookingModel->update($id, ['status' => $status]);

        return redirect()->back()->with('success', 'Status booking berhasil diubah menjadi ' . $status . '.');
    }
}
