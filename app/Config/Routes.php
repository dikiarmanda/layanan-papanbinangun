<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Publik
$routes->get('/', 'Home::index');
$routes->get('paket-wisata', 'PaketWisataController::index');
$routes->get('paket-wisata/(:segment)', 'PaketWisataController::show/$1');
$routes->post('checkout-reservasi', 'CheckoutReservasiController::create');

$routes->get('toko', 'ProdukController::index');
$routes->get('toko/(:segment)', 'ProdukController::show/$1');
$routes->get('keranjang', 'ProdukController::keranjang');
$routes->post('keranjang/add', 'ProdukController::addCart');
$routes->post('keranjang/update', 'ProdukController::updateCart');
$routes->get('keranjang/remove/(:num)', 'ProdukController::removeCart/$1');

$routes->get('api/provinces', 'ProdukController::provinces');
$routes->get('api/cities', 'ProdukController::cities');
$routes->get('api/destinations', 'ProdukController::destinations');
$routes->get('api/ongkir', 'ProdukController::ongkir');
$routes->get('api/zona-antar', 'ProdukController::zonaAntar');
$routes->get('api/homestay-availability', 'ProdukController::homestayAvailability');

$routes->get('checkout-produk', 'CheckoutProdukController::index');
$routes->post('checkout-produk', 'CheckoutProdukController::process');

$routes->get('status/(:segment)', 'StatusTransaksiController::show/$1');
$routes->post('midtrans/notification', 'MidtransWebhookController::notify');

$routes->get('kebijakan-privasi', 'LegalController::privasi');
$routes->get('persyaratan', 'LegalController::persyaratan');

// Admin auth
$routes->get('admin/login', 'Admin\AuthController::login');
$routes->post('admin/login', 'Admin\AuthController::attempt');
$routes->get('admin/logout', 'Admin\AuthController::logout');

$routes->group('admin', ['filter' => 'adminauth'], static function ($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');

    $routes->get('paket-wisata', 'Admin\PaketWisataAdminController::index');
    $routes->get('paket-wisata/create', 'Admin\PaketWisataAdminController::create');
    $routes->post('paket-wisata', 'Admin\PaketWisataAdminController::store');
    $routes->get('paket-wisata/(:num)/edit', 'Admin\PaketWisataAdminController::edit/$1');
    $routes->post('paket-wisata/(:num)', 'Admin\PaketWisataAdminController::update/$1');
    $routes->post('paket-wisata/(:num)/delete', 'Admin\PaketWisataAdminController::delete/$1');
    $routes->get('paket-wisata/(:num)/jadwal', 'Admin\PaketWisataAdminController::jadwal/$1');
    $routes->post('paket-wisata/(:num)/jadwal', 'Admin\PaketWisataAdminController::storeJadwal/$1');
    $routes->post('jadwal/(:num)/delete', 'Admin\PaketWisataAdminController::deleteJadwal/$1');

    $routes->get('produk', 'Admin\ProdukAdminController::index');
    $routes->get('produk/create', 'Admin\ProdukAdminController::create');
    $routes->post('produk', 'Admin\ProdukAdminController::store');
    $routes->get('produk/(:num)/edit', 'Admin\ProdukAdminController::edit/$1');
    $routes->post('produk/(:num)', 'Admin\ProdukAdminController::update/$1');
    $routes->post('produk/(:num)/delete', 'Admin\ProdukAdminController::delete/$1');

    $routes->get('zona-antar', 'Admin\ZonaAntarAdminController::index');
    $routes->get('zona-antar/create', 'Admin\ZonaAntarAdminController::create');
    $routes->post('zona-antar', 'Admin\ZonaAntarAdminController::store');
    $routes->get('zona-antar/(:num)/edit', 'Admin\ZonaAntarAdminController::edit/$1');
    $routes->post('zona-antar/(:num)', 'Admin\ZonaAntarAdminController::update/$1');
    $routes->post('zona-antar/(:num)/delete', 'Admin\ZonaAntarAdminController::delete/$1');

    $routes->get('reservasi', 'Admin\ReservasiAdminController::index');
    $routes->get('reservasi/(:num)', 'Admin\ReservasiAdminController::show/$1');
    $routes->post('reservasi/(:num)/status', 'Admin\ReservasiAdminController::updateStatus/$1');

    $routes->get('order', 'Admin\OrderAdminController::index');
    $routes->get('order/(:num)', 'Admin\OrderAdminController::show/$1');
    $routes->post('order/(:num)/status', 'Admin\OrderAdminController::updateStatus/$1');

    $routes->get('pembayaran', 'Admin\PembayaranAdminController::index');

    $routes->group('', ['filter' => 'superadmin'], static function ($routes) {
        $routes->get('users', 'Admin\UserAdminController::index');
        $routes->get('users/create', 'Admin\UserAdminController::create');
        $routes->post('users', 'Admin\UserAdminController::store');
        $routes->get('users/(:num)/edit', 'Admin\UserAdminController::edit/$1');
        $routes->post('users/(:num)', 'Admin\UserAdminController::update/$1');
    });
});
