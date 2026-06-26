<?php
$pdo = new PDO('mysql:host=localhost;dbname=lab_elektro', 'root', '');
$stmt = $pdo->query('SELECT username, role, password FROM users');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($users)) {
    echo "NO USERS FOUND IN DATABASE!\n";
    // Let's create an admin user
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("INSERT INTO users (username, password, nama_lengkap, role, status_akun) VALUES ('admin', '$hash', 'Administrator', 'admin', 'aktif')");
    echo "Created admin user with password 'admin123'\n";
} else {
    echo "USERS FOUND:\n";
    foreach ($users as $u) {
        echo "- Username: " . $u['username'] . " | Role: " . $u['role'] . "\n";
    }
    // Force reset admin password
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("UPDATE users SET password = '$hash' WHERE username = 'admin'");
    echo "Reset password for 'admin' to 'admin123'\n";
}
