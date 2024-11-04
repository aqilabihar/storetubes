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


//routes tambahan
$routes->get('/kita_rapat', 'MeetController::tambah'); //new controller for buat rapat
$routes->get('/beranda_copy', 'MeetController::buattrapat');
$routes->get('/tambah_rapat', 'MeetController::tambahrapat');



$routes->get('/beranda', 'BerandaController::index');
$routes->post('/beranda/absenHadir/(:num)', 'BerandaController::absenHadir/$1');
$routes->get('/beranda/attendanceList/(:num)', 'BerandaController::attendanceList/$1');

$routes->get('attendance/list/(:num)', 'AttendanceController::list/$1');
$routes->get('/transcription/view/(:num)', 'BerandaController::viewTranscription/$1');
$routes->post('/beranda/saveTranscription/(:num)', 'BerandaController::saveTranscription/$1');

$routes->get('/notulen_view', 'NotulenController::index');
