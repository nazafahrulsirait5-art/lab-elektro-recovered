<?php
$host = 'localhost';
$db   = 'lab_elektro';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
     $pdo = new PDO($dsn, $user, $pass);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     
     $sql = "CREATE TABLE IF NOT EXISTS `modul_praktikum` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `judul` varchar(255) NOT NULL,
        `file_modul` varchar(255) NOT NULL,
        `created_by` varchar(50) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
      
    $pdo->exec($sql);
    echo "<h1>Sukses!</h1>";
    echo "<p>Tabel modul_praktikum berhasil dibuat di database.</p>";
    echo "<a href='index.php/dashboard'>Kembali ke Dashboard</a>";
} catch (\PDOException $e) {
     echo "<h1>Error!</h1>";
     echo "<p>" . $e->getMessage() . "</p>";
}
