<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Auth::login');

// Auth routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->post('auth/register', 'Auth::register');
$routes->get('logout', 'Auth::logout');
$routes->post('logout', 'Auth::logout');
$routes->get('login/refresh-captcha', 'Auth::refreshCaptcha');

// Lupa Password
$routes->get('lupa-password', 'Auth::lupaPassword');
$routes->post('lupa-password/kirim', 'Auth::sendResetLink');
$routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->post('reset-password/(:any)', 'Auth::updatePassword/$1');

// Dashboard
$routes->get('dashboard', 'Dashboard::index');

// UCD Showcase & Heuristic Evaluation
$routes->get('ucd-showcase', 'UcdShowcase::index');

// Alat (Aset)
$routes->group('alat', function($routes) {
    $routes->get('', 'Aset::index');
    $routes->post('store', 'Aset::store');
    $routes->post('update/(:num)', 'Aset::update/$1');
    $routes->get('delete/(:num)', 'Aset::delete/$1');
    $routes->get('qr/(:num)', 'Aset::generateQr/$1');
});

// Peminjaman
$routes->group('peminjaman', function($routes) {
    $routes->get('', 'Peminjaman::index');
    $routes->get('riwayat', 'Peminjaman::index');
    $routes->post('pinjam', 'Peminjaman::pinjam');
    $routes->get('setujui/(:num)', 'Peminjaman::setujui/$1');
    $routes->post('kembali/(:num)', 'Peminjaman::kembali/$1');
});

// Praktikum (Modul)
$routes->group('praktikum', function($routes) {
    $routes->get('', 'Praktikum::index');
    $routes->post('store', 'Praktikum::store');
    $routes->get('delete/(:num)', 'Praktikum::delete/$1');
});

// Booking Lab
$routes->group('booking', function($routes) {
    $routes->get('', 'BookingLab::index');
    $routes->post('store', 'BookingLab::store');
    $routes->get('admin', 'BookingLab::admin');
    $routes->get('action/(:num)/(:any)', 'BookingLab::action/$1/$2');
});

// Laporan
$routes->group('laporan', function($routes) {
    $routes->get('surat-bebas', 'Laporan::suratBebas');
    $routes->get('surat-bebas/(:segment)', 'Laporan::suratBebas/$1');
    $routes->get('inventaris', 'Laporan::inventaris');
    $routes->get('analitik-dashboard', 'Laporan::analitikDashboard');
    $routes->get('analitik', 'Laporan::analitik');
});

// Manajemen User (Admin)
$routes->group('users', function($routes) {
    $routes->get('', 'Admin\Users::index');
    $routes->post('store', 'Admin\Users::store');
    $routes->post('update/(:num)', 'Admin\Users::update/$1');
    $routes->get('delete/(:num)', 'Admin\Users::delete/$1');
});

// Profil
$routes->get('profil', 'Profil::index');
$routes->post('profil/update', 'Profil::update');
$routes->post('profil/changePassword', 'Profil::changePassword');
$routes->post('profil/uploadFoto', 'Profil::uploadFoto');
$routes->get('profil/deleteFoto', 'Profil::deleteFoto');

// Knowledge Graph
$routes->get('graph', 'GraphController::index');
$routes->get('graph/data', 'GraphController::getData');

// Disable auto routing for security and to prevent "method not found" confusion
$routes->setAutoRoute(false);
