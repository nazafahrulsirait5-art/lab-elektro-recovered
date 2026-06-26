<?php

// Simulate CI4 index.php environment
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = Config\Services::codeigniter();
$app->initialize();

// Try to execute Aset controller
try {
    $controller = new \App\Controllers\Aset();
    $controller->initController(\Config\Services::request(), \Config\Services::response(), \Config\Services::logger());
    $output = $controller->index();
    echo "SUCCESS:\n";
    echo substr($output, 0, 500); // Print first 500 chars
} catch (\Throwable $e) {
    echo "ERROR CAUGHT:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
