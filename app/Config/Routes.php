<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'BerandaController::index'); // Update this line

$routes->get('/peserta', 'Peserta::index');
$routes->post('/peserta/save', 'Peserta::save');
$routes->get('/peserta/show', 'Peserta::show');

$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/save', 'Auth::save');

// $routes->get('/auth/login', 'Auth::login'); // Menampilkan halaman login
// $routes->post('/auth/login', 'Auth::authenticate'); // Mengautentikasi pengguna

// In Routes.php
$routes->get('/auth/login', 'Auth::login');            // Login page
$routes->post('/auth/login', 'Auth::authenticate');    // Process login
$routes->get('/auth/logout', 'Auth::logout');          // Logout
$routes->get('/beranda', 'BerandaController::index', ['filter' => 'auth']); // Homepage with login check


$routes->get('/rapat/create', 'Rapat::create'); // Rute untuk halaman buat rapat
$routes->post('/rapat/save', 'Rapat::save'); // Rute untuk menyimpan rapat
$routes->get('/rapat/show', 'Rapat::show');

$routes->get('/beranda', 'BerandaController::index');
