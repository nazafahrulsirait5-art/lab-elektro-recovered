<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table            = 'transaksi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'id_alat',
        'jumlah_pinjam',
        'tanggal_pinjam',
        'batas_waktu',
        'tanggal_kembali',
        'status_pinjam',
        'denda',
        'foto_pengembalian'
    ];

    // Dates
    protected $useTimestamps = false;

    /**
     * Get transactions with tool and user details
     */
    public function getTransaksiWithDetail($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('transaksi.*, alat.nama_alat, alat.merk, users.nama_lengkap');
        $builder->join('alat', 'alat.id = transaksi.id_alat');
        $builder->join('users', 'users.username = transaksi.username');
        
        if ($id) {
            return $builder->where('transaksi.id', $id)->get()->getRowArray();
        }

        return $builder->orderBy('transaksi.tanggal_pinjam', 'DESC')->get()->getResultArray();
    }

    /**
     * Atomic Stock Update: Pinjam
     */
    public function pinjamAlat($data)
    {
        $this->db->transStart();

        // 1. Insert Transaksi
        $this->insert($data);

        // 2. Update Stock in AlatModel
        $alatModel = new \App\Models\AlatModel();
        $alat = $alatModel->find($data['id_alat']);
        
        if ($alat && $alat['jumlah_tersedia'] >= $data['jumlah_pinjam']) {
            $newStock = $alat['jumlah_tersedia'] - $data['jumlah_pinjam'];
            $alatModel->update($data['id_alat'], ['jumlah_tersedia' => $newStock]);
        } else {
            $this->db->transRollback();
            return false;
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    /**
     * Atomic Stock Update: Pinjam Banyak Alat (Cart)
     */
    public function pinjamAlatBatch($batchData)
    {
        $this->db->transStart();
        $alatModel = new \App\Models\AlatModel();

        foreach ($batchData as $data) {
            // 1. Insert Transaksi for each alat
            $this->insert($data);

            // 2. Update Stock in AlatModel
            $alat = $alatModel->find($data['id_alat']);
            
            if ($alat && $alat['jumlah_tersedia'] >= $data['jumlah_pinjam']) {
                $newStock = $alat['jumlah_tersedia'] - $data['jumlah_pinjam'];
                $alatModel->update($data['id_alat'], ['jumlah_tersedia' => $newStock]);
            } else {
                $this->db->transRollback();
                return false;
            }
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    /**
     * Get Overdue transactions for a specific user
     */
    public function getOverdueByUsername($username)
    {
        $builder = $this->db->table($this->table);
        $builder->select('transaksi.*, alat.nama_alat');
        $builder->join('alat', 'alat.id = transaksi.id_alat');
        $builder->where('transaksi.username', $username);
        $builder->where('transaksi.status_pinjam', 'Dipinjam');
        $builder->where('transaksi.batas_waktu <', date('Y-m-d'));
        return $builder->get()->getResultArray();
    }

    /**
     * Get All Overdue transactions (for Admin)
     */
    public function getAllOverdue()
    {
        $builder = $this->db->table($this->table);
        $builder->select('transaksi.*, alat.nama_alat, users.nama_lengkap');
        $builder->join('alat', 'alat.id = transaksi.id_alat');
        $builder->join('users', 'users.username = transaksi.username');
        $builder->where('transaksi.status_pinjam', 'Dipinjam');
        $builder->where('transaksi.batas_waktu <', date('Y-m-d'));
        $builder->orderBy('transaksi.batas_waktu', 'ASC');
        return $builder->get()->getResultArray();
    }

    /**
     * Get Top 5 Borrowed Tools
     */
    public function getTopBorrowedTools()
    {
        $builder = $this->db->table($this->table);
        $builder->select('alat.nama_alat, COUNT(transaksi.id_alat) as total_pinjam, SUM(transaksi.jumlah_pinjam) as total_unit');
        $builder->join('alat', 'alat.id = transaksi.id_alat');
        $builder->groupBy('transaksi.id_alat, alat.nama_alat');
        $builder->orderBy('total_pinjam', 'DESC');
        $builder->limit(5);
        return $builder->get()->getResultArray();
    }
}
