<?php

namespace App\Models;

use CodeIgniter\Model;

class ModulPraktikumModel extends Model
{
    protected $table            = 'modul_praktikum';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['judul', 'file_modul', 'created_by', 'created_at'];

    protected $useTimestamps = false;
}
