<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username', 
        'npm',
        'password', 
        'foto_profil', 
        'nama_lengkap', 
        'role', 
        'status_akun',
        'email',
        'reset_token',
        'reset_expires'
    ];

    // Dates
    protected $useTimestamps = false;

    /**
     * Get user by username for authentication
     */
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}
