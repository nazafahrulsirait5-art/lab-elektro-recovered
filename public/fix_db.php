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
     
     // Ubah collation booking_lab agar sama dengan users
     $sql = "ALTER TABLE `booking_lab` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;";
     $pdo->exec($sql);
     
     echo "Collation berhasil diperbaiki!\n";
} catch (\PDOException $e) {
     echo "Error: " . $e->getMessage() . "\n";
}
