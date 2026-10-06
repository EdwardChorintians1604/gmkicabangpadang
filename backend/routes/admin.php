<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/routes/admin.php
 * Deskripsi: Definisi seluruh rute panel kendali Administrator (/admin)
 * Hak Akses: Administrator (Akses Penuh Kelola Data & Keamanan) dan Operator
 * =====================================================================
 */

declare(strict_types=1);

if (!defined('GMKI_SECURE_ACCESS')) {
    http_response_code(403);
    exit('Akses langsung ditolak.');
}

use App\Core\Router;

/** @var Router $router */

// Fallback jika user mengakses /dashboard umum
$router->get('/dashboard', function () {
    if (is_admin()) {
        redirect('/admin/dashboard');
    } else {
        redirect('/pengawas/dashboard');
    }
}, ['auth']);

// 1. Dashboard Utama Admin
$router->get('/admin/dashboard', 'Admin\DashboardController@index', ['auth', 'role:admin,operator']);

// 2. Modul Civitas (Data Anggota & Kaderisasi)
$router->get('/admin/civitas', 'Admin\CivitasController@index', ['auth', 'role:admin,operator,pengawas']);
$router->get('/admin/civitas/create', 'Admin\CivitasController@create', ['auth', 'role:admin,operator,pengawas']);
$router->post('/admin/civitas', 'Admin\CivitasController@store', ['auth', 'role:admin,operator,pengawas', 'csrf']);
$router->get('/admin/civitas/impor', 'Admin\CivitasController@imporForm', ['auth', 'role:admin']);
$router->post('/admin/civitas/impor', 'Admin\CivitasController@imporProcess', ['auth', 'role:admin', 'csrf']);
$router->get('/admin/civitas/export', 'Admin\CivitasController@export', ['auth', 'role:admin,operator,pengawas']);
$router->get('/admin/civitas/{id}', 'Admin\CivitasController@detail', ['auth', 'role:admin,operator,pengawas']);
$router->get('/admin/civitas/{id}/edit', 'Admin\CivitasController@edit', ['auth', 'role:admin,operator,pengawas']);
$router->post('/admin/civitas/{id}', 'Admin\CivitasController@update', ['auth', 'role:admin,operator,pengawas', 'csrf']);
$router->post('/admin/civitas/{id}/delete', 'Admin\CivitasController@delete', ['auth', 'role:admin', 'csrf']);

// 2b. Modul Kader Baru / Calon Anggota (Maperca) - terpisah dari data civitas resmi
$router->get('/admin/maperca', 'Admin\MapercaController@index', ['auth', 'role:admin,operator,pengawas']);
$router->get('/admin/maperca/create', 'Admin\MapercaController@create', ['auth', 'role:admin,operator,pengawas']);
$router->post('/admin/maperca', 'Admin\MapercaController@store', ['auth', 'role:admin,operator,pengawas', 'csrf']);
$router->get('/admin/maperca/export', 'Admin\MapercaController@export', ['auth', 'role:admin,operator,pengawas']);
$router->get('/admin/maperca/{id}', 'Admin\MapercaController@detail', ['auth', 'role:admin,operator,pengawas']);
$router->get('/admin/maperca/{id}/edit', 'Admin\MapercaController@edit', ['auth', 'role:admin,operator,pengawas']);
$router->post('/admin/maperca/{id}', 'Admin\MapercaController@update', ['auth', 'role:admin,operator,pengawas', 'csrf']);
$router->post('/admin/maperca/{id}/lantik', 'Admin\MapercaController@lantik', ['auth', 'role:admin,operator,pengawas', 'csrf']);
$router->post('/admin/maperca/{id}/delete', 'Admin\MapercaController@delete', ['auth', 'role:admin', 'csrf']);

// 2c. Modul Komisariat (CRUD komisariat & anggotanya)
$router->get('/admin/komisariat', 'Admin\KomisariatController@index', ['auth', 'role:admin,operator,pengawas']);
$router->get('/admin/komisariat/create', 'Admin\KomisariatController@create', ['auth', 'role:admin,operator,pengawas']);
$router->post('/admin/komisariat', 'Admin\KomisariatController@store', ['auth', 'role:admin,operator,pengawas', 'csrf']);
$router->get('/admin/komisariat/{id}', 'Admin\KomisariatController@detail', ['auth', 'role:admin,operator,pengawas']);
$router->get('/admin/komisariat/{id}/edit', 'Admin\KomisariatController@edit', ['auth', 'role:admin,operator,pengawas']);
$router->post('/admin/komisariat/{id}', 'Admin\KomisariatController@update', ['auth', 'role:admin,operator,pengawas', 'csrf']);
$router->post('/admin/komisariat/{id}/delete', 'Admin\KomisariatController@delete', ['auth', 'role:admin', 'csrf']);

// 3. Modul Warta Berita & Publikasi
$router->get('/admin/berita', 'Admin\NewsController@index', ['auth', 'role:admin,operator']);
$router->get('/admin/berita/create', 'Admin\NewsController@create', ['auth', 'role:admin,operator']);
$router->post('/admin/berita', 'Admin\NewsController@store', ['auth', 'role:admin,operator', 'csrf']);
$router->get('/admin/berita/{id}/edit', 'Admin\NewsController@edit', ['auth', 'role:admin,operator']);
$router->post('/admin/berita/{id}', 'Admin\NewsController@update', ['auth', 'role:admin,operator', 'csrf']);
$router->post('/admin/berita/{id}/delete', 'Admin\NewsController@delete', ['auth', 'role:admin', 'csrf']);

// 4. Modul Organisasi BPC
$router->get('/admin/organisasi/profil', 'Admin\OrganizationController@profil', ['auth', 'role:admin']);
$router->post('/admin/organisasi/profil', 'Admin\OrganizationController@updateProfil', ['auth', 'role:admin', 'csrf']);
$router->get('/admin/organisasi/struktur', 'Admin\OrganizationController@struktur', ['auth', 'role:admin']);
$router->post('/admin/organisasi/struktur', 'Admin\OrganizationController@simpanStruktur', ['auth', 'role:admin', 'csrf']);
$router->post('/admin/organisasi/struktur/{id}/delete', 'Admin\OrganizationController@hapusStruktur', ['auth', 'role:admin', 'csrf']);

// 5. Modul Statistik & Grafik
$router->get('/admin/statistik', 'Admin\StatisticsController@index', ['auth', 'role:admin,operator']);

// 6. Modul Pengelolaan Akun Pengguna (/admin/akun)
$router->get('/admin/akun', 'Admin\UserController@index', ['auth', 'role:admin']);
$router->get('/admin/akun/create', 'Admin\UserController@create', ['auth', 'role:admin']);
$router->post('/admin/akun', 'Admin\UserController@store', ['auth', 'role:admin', 'csrf']);
$router->get('/admin/akun/{id}/edit', 'Admin\UserController@edit', ['auth', 'role:admin']);
$router->post('/admin/akun/{id}', 'Admin\UserController@update', ['auth', 'role:admin', 'csrf']);
$router->post('/admin/akun/{id}/delete', 'Admin\UserController@delete', ['auth', 'role:admin', 'csrf']);

// Alias Rute Pengelolaan Pengguna (/admin/users)
$router->get('/admin/users', 'Admin\UserController@index', ['auth', 'role:admin']);
$router->get('/admin/users/create', 'Admin\UserController@create', ['auth', 'role:admin']);
$router->post('/admin/users', 'Admin\UserController@store', ['auth', 'role:admin', 'csrf']);
$router->get('/admin/users/{id}/edit', 'Admin\UserController@edit', ['auth', 'role:admin']);
$router->post('/admin/users/{id}', 'Admin\UserController@update', ['auth', 'role:admin', 'csrf']);
$router->post('/admin/users/{id}/delete', 'Admin\UserController@delete', ['auth', 'role:admin', 'csrf']);

// 7. Modul Keamanan, Audit Log & Cadangan Data
$router->get('/admin/keamanan/audit-log', 'Admin\SecurityController@auditLog', ['auth', 'role:admin']);
$router->get('/admin/keamanan/akses-log', 'Admin\SecurityController@accessLog', ['auth', 'role:admin']);
$router->get('/admin/keamanan/ancaman', 'Admin\SecurityController@threats', ['auth', 'role:admin']);
$router->post('/admin/keamanan/ancaman/{id}/resolve', 'Admin\SecurityController@resolveAncaman', ['auth', 'role:admin']);
$router->get('/admin/keamanan/ancaman/{id}/resolve', 'Admin\SecurityController@resolveAncaman', ['auth', 'role:admin']);
$router->get('/admin/keamanan/backup', 'Admin\BackupController@index', ['auth', 'role:admin']);
$router->post('/admin/keamanan/backup/database', 'Admin\BackupController@backupDatabase', ['auth', 'role:admin', 'csrf']);
$router->post('/admin/keamanan/backup/manifest', 'Admin\BackupController@generateManifest', ['auth', 'role:admin', 'csrf']);
$router->get('/admin/keamanan/backup/download', 'Admin\BackupController@download', ['auth', 'role:admin']);
$router->get('/admin/keamanan/backup/unduh-sekarang', 'Admin\BackupController@createAndDownload', ['auth', 'role:admin']);
$router->get('/admin/keamanan/backup/database/{filename}', 'Admin\BackupController@download', ['auth', 'role:admin']);
