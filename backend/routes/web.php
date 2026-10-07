<?php

use App\Core\Router;

/** @var Router $router */

// =====================================================================
// RUTE PUBLIK (Dapat diakses oleh siapa saja tanpa login)
// =====================================================================
$router->get('/', 'PublicController@home');
$router->get('/profil', 'PublicController@profil');
$router->get('/struktur-organisasi', 'PublicController@strukturOrganisasi');
$router->get('/berita', 'PublicController@berita');
$router->get('/berita/{slug}', 'PublicController@detailBerita');
$router->get('/kontak', 'PublicController@kontak');
$router->post('/kontak', 'PublicController@kirimKontak', ['csrf']);

// =====================================================================
// RUTE AUTENTIKASI (Login, Logout, Ganti Password)
// =====================================================================
$router->get('/login', 'AuthController@showLogin', ['guest']);
$router->post('/login', 'AuthController@login', ['guest', 'csrf']);
$router->post('/logout', 'AuthController@logout', ['auth', 'csrf']);
$router->get('/ubah-password', 'AuthController@showChangePassword', ['auth']);
$router->post('/ubah-password', 'AuthController@updatePassword', ['auth', 'csrf']);

// =====================================================================
// RUTE DASHBOARD PANEL
// =====================================================================
$router->get('/dashboard', function () {
    $role = \App\Core\Auth::role();
    if (in_array($role, ['ketcab', 'pengawas'], true)) {
        redirect('/ketcab/dashboard');
    }
    redirect('/ruang-kerja');
}, ['auth']);
$router->get('/dashboard/pengawas', function () {
    redirect('/ketcab/dashboard');
}, ['auth', 'role:admin,ketcab,pengawas']);

// =====================================================================
// RUTE ORGANISASI (BPC GMKI Padang)
// =====================================================================
$router->get('/admin/organisasi/profil', 'OrganizationController@profil', ['auth', 'role:admin']);
$router->post('/admin/organisasi/profil', 'OrganizationController@updateProfil', ['auth', 'role:admin', 'csrf']);
$router->get('/admin/organisasi/struktur', 'OrganizationController@struktur', ['auth', 'role:admin']);
$router->post('/admin/organisasi/struktur', 'OrganizationController@simpanStruktur', ['auth', 'role:admin', 'csrf']);
$router->post('/admin/organisasi/struktur/{id}/delete', 'OrganizationController@hapusStruktur', ['auth', 'role:admin', 'csrf']);

// =====================================================================
// RUTE BERITA & WARTA (Admin & Operator)
// =====================================================================
$router->get('/admin/berita', 'NewsController@index', ['auth', 'role:admin,operator,sekfung_medko']);
$router->get('/admin/berita/create', 'NewsController@create', ['auth', 'role:admin,operator,sekfung_medko']);
$router->post('/admin/berita', 'NewsController@store', ['auth', 'role:admin,operator,sekfung_medko', 'csrf']);
$router->get('/admin/berita/{id}/edit', 'NewsController@edit', ['auth', 'role:admin,operator,sekfung_medko']);
$router->post('/admin/berita/{id}', 'NewsController@update', ['auth', 'role:admin,operator,sekfung_medko', 'csrf']);
$router->post('/admin/berita/{id}/delete', 'NewsController@delete', ['auth', 'role:admin,sekfung_medko', 'csrf']);

// =====================================================================
// RUTE CIVITAS / ANGGOTA (Admin, Operator, Pengawas)
// =====================================================================
$router->get('/admin/civitas', 'CivitasController@index', ['auth', 'role:admin,operator,sekcab,ketcab']);
$router->get('/admin/civitas/create', 'CivitasController@create', ['auth', 'role:admin,operator,sekcab']);
$router->post('/admin/civitas', 'CivitasController@store', ['auth', 'role:admin,operator,sekcab', 'csrf']);
$router->get('/admin/civitas/impor', 'CivitasController@imporForm', ['auth', 'role:admin,operator,sekcab']);
$router->post('/admin/civitas/impor', 'CivitasController@imporProcess', ['auth', 'role:admin,operator,sekcab', 'csrf']);
$router->get('/admin/civitas/export', 'CivitasController@export', ['auth', 'role:admin,operator,sekcab,ketcab']);
$router->get('/admin/civitas/{id}', 'CivitasController@detail', ['auth', 'role:admin,operator,sekcab,ketcab']);
$router->get('/admin/civitas/{id}/edit', 'CivitasController@edit', ['auth', 'role:admin,operator,sekcab']);
$router->post('/admin/civitas/{id}', 'CivitasController@update', ['auth', 'role:admin,operator,sekcab', 'csrf']);
$router->post('/admin/civitas/{id}/delete', 'CivitasController@delete', ['auth', 'role:admin,sekcab', 'csrf']);

// =====================================================================
// RUTE STATISTIK & ANALISIS DATA
// =====================================================================
$router->get('/admin/statistik', 'StatisticsController@index', ['auth', 'role:admin,operator,sekcab,ketcab,sekfung_medko']);
$router->get('/api/statistik', 'StatisticsController@apiData', ['auth', 'role:admin,operator,sekcab,ketcab,sekfung_medko']);

// =====================================================================
// RUTE PENGGUNA SISTEM (Khusus Administrator)
// =====================================================================
$router->get('/admin/users', 'UserController@index', ['auth', 'role:admin']);
$router->get('/admin/users/create', 'UserController@create', ['auth', 'role:admin']);
$router->post('/admin/users', 'UserController@store', ['auth', 'role:admin', 'csrf']);
$router->get('/admin/users/{id}/edit', 'UserController@edit', ['auth', 'role:admin']);
$router->post('/admin/users/{id}', 'UserController@update', ['auth', 'role:admin', 'csrf']);
$router->post('/admin/users/{id}/delete', 'UserController@delete', ['auth', 'role:admin', 'csrf']);

// =====================================================================
// RUTE KEAMANAN, AUDIT LOG, & BACKUP
// =====================================================================
$router->get('/admin/keamanan/audit-log', 'SecurityController@auditLog', ['auth', 'role:admin,ketcab,pengawas']);
$router->get('/admin/keamanan/akses-log', 'SecurityController@accessLog', ['auth', 'role:admin,ketcab,pengawas']);
$router->get('/admin/keamanan/ancaman', 'SecurityController@ancaman', ['auth', 'role:admin']);
$router->post('/admin/keamanan/ancaman/{id}/resolve', 'SecurityController@resolveAncaman', ['auth', 'role:admin', 'csrf']);
$router->get('/admin/keamanan/backup', 'BackupController@index', ['auth', 'role:admin,pengawas']);
$router->post('/admin/keamanan/backup/database', 'BackupController@createDatabaseBackup', ['auth', 'role:admin', 'csrf']);
$router->get('/admin/keamanan/backup/database/{filename}', 'BackupController@downloadDatabaseBackup', ['auth', 'role:admin,pengawas']);
$router->post('/admin/keamanan/backup/manifest', 'BackupController@generateManifest', ['auth', 'role:admin', 'csrf']);

// =====================================================================
// RUTE STREAM BERKAS PRIVAT (Aman & Terotentikasi)
// =====================================================================
$router->get('/private/foto-anggota/{filename}', 'FileController@streamFoto', ['auth']);
$router->get('/private/kta/{filename}', 'FileController@streamKta', ['auth']);
