<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- ROUTE PUBLIK (Tanpa Login) ---
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('logout', 'AuthController::logout');
$routes->get('/', 'Home::index');
$routes->get('page/(:any)', 'Home::page/$1');

// Notifikasi Midtrans wajib publik
$routes->post('payment/notification', 'PaymentController::notification');

// --- ROUTE TERPROTEKSI ---
$routes->group('', ['filter' => 'auth'], function ($routes) {
    
    // 1. Dashboard (Hanya Owner & Admin - Diatur via Filter & DB)
    $routes->group('dashboard', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'Dashboard::index');
        $routes->get('filter', 'Dashboard::filter');
    });
    
    // 2. Profile (Semua Role)
    $routes->group('profile', function ($routes) {
        $routes->get('/', 'ProfileController::index');
        $routes->post('update-password', 'ProfileController::updatePassword');
    });

    // 3. Pelanggan (Slug: pelanggan)
    $routes->group('pelanggan', function ($routes) {
        $routes->get('/', 'PelangganController::index');
        $routes->get('list', 'PelangganController::list');
        $routes->get('show/(:num)', 'PelangganController::show/$1');
        $routes->post('save', 'PelangganController::save');
        $routes->post('delete/(:num)', 'PelangganController::delete/$1');
    });

    // 4. Tagihan Admin (Slug: tagihan)
    $routes->group('tagihan', function ($routes) {
        $routes->get('/', 'TagihanController::index');
        $routes->get('belum-bayar', 'TagihanController::belumBayar');
        $routes->get('lunas', 'TagihanController::lunas');
        $routes->get('tunggakan', 'TagihanController::tunggakan');
        $routes->get('list', 'TagihanController::list');
        $routes->post('generate', 'TagihanController::generate');
        $routes->post('bayar/(:num)', 'TagihanController::bayar/$1');
        $routes->post('rollback/(:num)', 'TagihanController::rollback/$1');
        $routes->post('delete/(:num)', 'TagihanController::delete/$1');
    });

    // 5. Laporan (Slug: laporan)
    $routes->group('laporan', function ($routes) {
        $routes->get('pemasukan', 'LaporanController::pemasukan');
        $routes->get('tunggakan', 'LaporanController::tunggakan');
    });

    // 6. Paket WiFi (Slug: paket)
    $routes->group('paket', function ($routes) {
        $routes->get('/', 'PackageController::index');
        $routes->get('list', 'PackageController::list');
        $routes->get('show/(:num)', 'PackageController::show/$1');
        $routes->post('save', 'PackageController::save');
        $routes->post('delete/(:num)', 'PackageController::delete/$1');
    });

    // 7. Manajemen User (Slug: user)
    $routes->group('user', function ($routes) {
        $routes->get('/', 'UserController::index');
        $routes->get('list', 'UserController::list');
        $routes->get('show/(:num)', 'UserController::show/$1');
        $routes->post('save', 'UserController::save');
        $routes->post('delete/(:num)', 'UserController::delete/$1');
    });

    // 8. Manajemen Hak Akses (Slug: role)
    $routes->group('role', function ($routes) {
        $routes->get('/', 'RoleController::index');
        $routes->get('ambil_akses/(:any)', 'RoleController::ambil_akses/$1');
        $routes->post('simpan', 'RoleController::simpan');
    });

    // 9. Pengaturan (Slug: pengaturan)
    $routes->group('pengaturan', function ($routes) {
        $routes->get('sistem', 'PengaturanController::sistem');
        $routes->get('landing', 'SettingController::landing');
        $routes->post('landing/save', 'SettingController::saveLanding');
    });

    // 10. Manajemen Log (Slug: logs)
    $routes->group('logs', function ($routes) {
        $routes->get('/', 'LogController::index');
    });

    // 11. Menu Khusus Customer (Slug: customer)
    $routes->group('customer', function ($routes) {
        $routes->get('tagihan', 'CustomerController::index');
        $routes->get('list-tagihan', 'CustomerController::listTagihan');
    });

    // Jalur API Payment
    $routes->get('payment/get-token/(:num)', 'PaymentController::getSnapToken/$1');
});