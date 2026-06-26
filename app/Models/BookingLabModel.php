<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingLabModel extends Model
{
    protected $table            = 'booking_lab';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'tanggal', 'jam_mulai', 'jam_selesai', 'keperluan', 'status', 'created_at'];

    protected $useTimestamps = false;

    // Fungsi untuk mengecek bentrok jadwal
    public function isScheduleConflict($tanggal, $jam_mulai, $jam_selesai)
    {
        // Kondisi bentrok: (StartA < EndB) AND (EndA > StartB)
        // dimana A = Jadwal Baru, B = Jadwal di Database
        return $this->where('tanggal', $tanggal)
                    ->whereIn('status', ['Menunggu Persetujuan', 'Disetujui'])
                    ->groupStart()
                        ->where("jam_mulai <", $jam_selesai)
                        ->where("jam_selesai >", $jam_mulai)
                    ->groupEnd()
                    ->countAllResults() > 0;
    }

    public function getBookingsWithUser()
    {
        return $this->select('booking_lab.*, users.nama_lengkap')
                    ->join('users', 'users.username = booking_lab.username COLLATE utf8mb4_general_ci', 'left', false)
                    ->orderBy('booking_lab.tanggal', 'DESC')
                    ->orderBy('booking_lab.jam_mulai', 'ASC')
                    ->findAll();
    }
}
