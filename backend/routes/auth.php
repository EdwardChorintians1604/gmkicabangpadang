<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/routes/auth.php
 * Deskripsi: Definisi seluruh rute autentikasi akun (Login, Logout, Ganti Password)
 * Menggunakan satu pintu masuk autentikasi untuk seluruh role.
 * =====================================================================
 */

declare(strict_types=1);

if (!defined('GMKI_SECURE_ACCESS')) {
    http_response_code(403);
    exit('Akses langsung ditolak.');
}

use App\Core\Router;

/** @var Router $router */

// Masuk (Login)
$router->get('/login', 'AuthController@showLogin', ['guest']);
$router->post('/login', 'AuthController@login', ['guest', 'csrf']);

// Keluar (Logout)
$router->post('/logout', 'AuthController@logout', ['auth', 'csrf']);

// Ganti Password Mandiri
$router->get('/ubah-password', 'AuthController@showChangePassword', ['auth']);
$router->post('/ubah-password', 'AuthController@updatePassword', ['auth', 'csrf']);
