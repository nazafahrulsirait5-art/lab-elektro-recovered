<?php

namespace App\Models;

use CodeIgniter\Model;

class AlatModel extends Model
{
    protected $table            = 'alat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_alat', 
        'merk', 
        'jumlah_total', 
        'jumlah_tersedia', 
        'status', 
        'jumlah_maintenance', 
        'jumlah_rusak'
    ];

    // Dates
    protected $useTimestamps = false;

    /**
     * Get available stock for a specific tool
     */
    public function getStock($id)
    {
        return $this->find($id)['jumlah_tersedia'] ?? 0;
    }
}
