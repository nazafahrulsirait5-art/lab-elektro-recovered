<?php
$hash = '$2y$10$VoOky5f5RRbQz7bQ7H7d4.5y1DsPxYXyD5hL7WgS0eDRtMnyFn4Hm';
$passwords = ['12345', '123456', '12345678', 'password', 'mahasiswa'];
foreach($passwords as $p) {
    if(password_verify($p, $hash)) {
        echo "MATCH: $p\n";
        exit;
    }
}
echo "NO MATCH\n";
