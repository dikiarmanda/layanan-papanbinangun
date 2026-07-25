<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// -------------------------------------------------------------------------
// Publik — beranda & legal
// -------------------------------------------------------------------------
$routes->get('/', 'Home::index');
$routes->get('kebijakan-privasi', 'LegalController::privasi');
$routes->get('persyaratan', 'LegalController::persyaratan');

// -------------------------------------------------------------------------
// Publik — wisata & homestay
// -------------------------------------------------------------------------
$routes->group('paket-wisata', static function ($routes) {
    $routes->get('/', 'PaketWisataController::index');
    $routes->get('(:segment)', 'PaketWisataController::show/$1');
});
$routes->post('checkout-reservasi', 'CheckoutReservasiController::create');

// -------------------------------------------------------------------------
// Publik — toko UMKM & catering
// -------------------------------------------------------------------------
$routes->group('toko', static function ($routes) {
    $routes->get('/', 'ProdukController::index');
    $routes->get('(:segment)', 'ProdukController::show/$1');
});

$routes->group('keranjang', static function ($routes) {
    $routes->get('/', 'ProdukController::keranjang');
    $routes->post('add', 'ProdukController::addCart');
    $routes->post('update', 'ProdukController::updateCart');
    $routes->get('remove/(:num)', 'ProdukController::removeCart/$1');
});

$routes->get('checkout-produk', 'CheckoutProdukController::index');
$routes->post('checkout-produk', 'CheckoutProdukController::process');

// -------------------------------------------------------------------------
// Publik — API
// -------------------------------------------------------------------------
$routes->group('api', static function ($routes) {
    $routes->get('provinces', 'ProdukController::provinces');
    $routes->get('cities', 'ProdukController::cities');
    $routes->get('destinations', 'ProdukController::destinations');
    $routes->get('ongkir', 'ProdukController::ongkir');
    $routes->get('zona-antar', 'ProdukController::zonaAntar');
    $routes->get('homestay-availability', 'ProdukController::homestayAvailability');
});

// -------------------------------------------------------------------------
// Publik — status & webhook pembayaran
// -------------------------------------------------------------------------
$routes->get('status/(:segment)', 'StatusTransaksiController::show/$1');
$routes->post('midtrans/notification', 'MidtransWebhookController::notify');

// -------------------------------------------------------------------------
// Admin — autentikasi (tanpa filter)
// -------------------------------------------------------------------------
$routes->group('admin', static function ($routes) {
    $routes->get('login', 'Admin\AuthController::login');
    $routes->post('login', 'Admin\AuthController::attempt');
    $routes->get('logout', 'Admin\AuthController::logout');
});

// -------------------------------------------------------------------------
// Admin — panel (filter adminauth)
// -------------------------------------------------------------------------
$routes->group('admin', ['filter' => 'adminauth'], static function ($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('pembayaran', 'Admin\PembayaranAdminController::index');

    // Paket wisata & jadwal
    $routes->group('paket-wisata', static function ($routes) {
        $routes->get('/', 'Admin\PaketWisataAdminController::index');
        $routes->get('create', 'Admin\PaketWisataAdminController::create');
        $routes->post('/', 'Admin\PaketWisataAdminController::store');
        $routes->get('(:num)/edit', 'Admin\PaketWisataAdminController::edit/$1');
        $routes->post('(:num)', 'Admin\PaketWisataAdminController::update/$1');
        $routes->post('(:num)/delete', 'Admin\PaketWisataAdminController::delete/$1');
        $routes->get('(:num)/jadwal', 'Admin\PaketWisataAdminController::jadwal/$1');
        $routes->post('(:num)/jadwal', 'Admin\PaketWisataAdminController::storeJadwal/$1');
    });
    $routes->post('jadwal/(:num)/delete', 'Admin\PaketWisataAdminController::deleteJadwal/$1');

    // Produk UMKM & catering
    $routes->group('produk', static function ($routes) {
        $routes->get('/', 'Admin\ProdukAdminController::index');
        $routes->get('create', 'Admin\ProdukAdminController::create');
        $routes->post('/', 'Admin\ProdukAdminController::store');
        $routes->get('(:num)/edit', 'Admin\ProdukAdminController::edit/$1');
        $routes->post('(:num)', 'Admin\ProdukAdminController::update/$1');
        $routes->post('(:num)/delete', 'Admin\ProdukAdminController::delete/$1');
    });

    // Zona antar lokal (catering)
    $routes->group('zona-antar', static function ($routes) {
        $routes->get('/', 'Admin\ZonaAntarAdminController::index');
        $routes->get('create', 'Admin\ZonaAntarAdminController::create');
        $routes->post('/', 'Admin\ZonaAntarAdminController::store');
        $routes->get('(:num)/edit', 'Admin\ZonaAntarAdminController::edit/$1');
        $routes->post('(:num)', 'Admin\ZonaAntarAdminController::update/$1');
        $routes->post('(:num)/delete', 'Admin\ZonaAntarAdminController::delete/$1');
    });

    // Reservasi
    $routes->group('reservasi', static function ($routes) {
        $routes->get('/', 'Admin\ReservasiAdminController::index');
        $routes->get('(:num)', 'Admin\ReservasiAdminController::show/$1');
        $routes->post('(:num)/status', 'Admin\ReservasiAdminController::updateStatus/$1');
    });

    // Order
    $routes->group('order', static function ($routes) {
        $routes->get('/', 'Admin\OrderAdminController::index');
        $routes->get('(:num)', 'Admin\OrderAdminController::show/$1');
        $routes->post('(:num)/status', 'Admin\OrderAdminController::updateStatus/$1');
    });

    // Superadmin — kelola user
    $routes->group('', ['filter' => 'superadmin'], static function ($routes) {
        $routes->group('users', static function ($routes) {
            $routes->get('/', 'Admin\UserAdminController::index');
            $routes->get('create', 'Admin\UserAdminController::create');
            $routes->post('/', 'Admin\UserAdminController::store');
            $routes->get('(:num)/edit', 'Admin\UserAdminController::edit/$1');
            $routes->post('(:num)', 'Admin\UserAdminController::update/$1');
        });
    });
});
