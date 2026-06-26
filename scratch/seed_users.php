<?php
/**
 * Seeding Users for Lab Elektro Recovery
 */

// Define path constants manually to bootstrap CodeIgniter without running the app
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);

require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

// Define ENVIRONMENT if not defined
if (! defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

// Load environment settings
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

$db = \Config\Database::connect();
$password = password_hash('password', PASSWORD_BCRYPT);

echo "--- Starting Seeding Process ---\n";

// 1. Ensure 'npm' column exists
try {
    $db->query("ALTER TABLE users ADD COLUMN npm VARCHAR(20) AFTER username");
    echo "[INFO] Added 'npm' column to 'users' table.\n";
} catch (\Exception $e) {
    echo "[INFO] column 'npm' might already exist or table doesn't exist. Skipping ALTER TABLE.\n";
}

// 2. Clear existing users
try {
    $db->table('users')->emptyTable();
    echo "[INFO] Cleared 'users' table.\n";
} catch (\Exception $e) {
    echo "[ERROR] Could not clear table: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Define users
$users = [
    [
        'username'     => 'admin',
        'npm'          => null,
        'password'     => $password,
        'nama_lengkap' => 'Administrator Utama',
        'role'         => 'admin',
        'status_akun'  => 'aktif'
    ],
    [
        'username'     => 'penjaga',
        'npm'          => null,
        'password'     => $password,
        'nama_lengkap' => 'Penjaga Lab',
        'role'         => 'penjaga_lab',
        'status_akun'  => 'aktif'
    ],
    [
        'username'     => 'kaprodi',
        'npm'          => null,
        'password'     => $password,
        'nama_lengkap' => 'Kepala Program Studi',
        'role'         => 'kaprodi',
        'status_akun'  => 'aktif'
    ],
    [
        'username'     => '250420501100004',
        'npm'          => '250420501100004',
        'password'     => $password,
        'nama_lengkap' => 'Dwiky Ilham',
        'role'         => 'mahasiswa',
        'status_akun'  => 'aktif'
    ],
    [
        'username'     => '2404205010006',
        'npm'          => '2404205010006',
        'password'     => $password,
        'nama_lengkap' => 'Misbah Anuari',
        'role'         => 'mahasiswa',
        'status_akun'  => 'aktif'
    ],
    [
        'username'     => '250420501100002',
        'npm'          => '250420501100002',
        'password'     => $password,
        'nama_lengkap' => 'Naza Fahrul Sirait',
        'role'         => 'mahasiswa',
        'status_akun'  => 'aktif'
    ],
    [
        'username'     => '250420501100007',
        'npm'          => '250420501100007',
        'password'     => $password,
        'nama_lengkap' => 'Ahmad Mufadhdhal',
        'role'         => 'mahasiswa',
        'status_akun'  => 'aktif'
    ],
    [
        'username'     => '250420501100003',
        'npm'          => '250420501100003',
        'password'     => $password,
        'nama_lengkap' => 'Rana Sulthanah',
        'role'         => 'mahasiswa',
        'status_akun'  => 'aktif'
    ],
];

// 4. Insert users
foreach ($users as $user) {
    if ($db->table('users')->insert($user)) {
        echo "[SUCCESS] Inserted: " . $user['nama_lengkap'] . " (" . $user['role'] . ")\n";
    } else {
        echo "[ERROR] Failed to insert: " . $user['nama_lengkap'] . "\n";
    }
}

echo "--- Seeding Process Completed ---\n";
