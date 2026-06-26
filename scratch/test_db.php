<?php
try {
    $dsn = "pgsql:host=db.zvqipmizxtbpmpglwfzt.supabase.co;port=5432;dbname=postgres";
    $user = "postgres";
    $pass = "Naza223311";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully to Supabase PostgreSQL!\n";
    
    // Check tables
    $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname='public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
