<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SetupAudit extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE IF NOT EXISTS audit_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            action VARCHAR(255) NOT NULL,
            details TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($db->query($sql)) {
            echo "Audit logs table created successfully!";
        } else {
            echo "Failed to create table.";
        }
    }
}
