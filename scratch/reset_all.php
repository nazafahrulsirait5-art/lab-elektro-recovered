<?php
$pdo = new PDO('mysql:host=localhost;dbname=lab_elektro', 'root', '');
$hash = password_hash('admin123', PASSWORD_DEFAULT);
$pdo->exec("UPDATE users SET password = '$hash'");
echo "All passwords reset!\n";
