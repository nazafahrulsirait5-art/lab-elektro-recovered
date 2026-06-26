<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'action', 'details', 'created_at'];

    // Dates
    protected $useTimestamps = false;
    
    /**
     * Helper to log an action easily
     */
    public function logAction($action, $details = '')
    {
        $username = session()->get('username') ?? 'System';
        
        $data = [
            'username' => $username,
            'action' => $action,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($data);
    }
}
