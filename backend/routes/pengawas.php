<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/routes/pengawas.php
 * Deskripsi: Definisi rute panel pemantauan Pengawas (/pengawas)
 * Khusus untuk KETCAB, SEKCAB, BENCAB, dan Majelis Pengawas (MPPC).
 * Karakteristik: Panel Baca-Saja (Read-Only) yang aman dan berorientasi laporan eksekutif.
 * =====================================================================
 */

declare(strict_types=1);

if (!defined('GMKI_SECURE_ACCESS')) {
    http_response_code(403);
    exit('Akses langsung ditolak.');
}

use App\Core\Router;

/** @var Router $router */

// 1. Dashboard Pemantauan Eksekutif (Tab: Ketcab, Sekcab, Bencab)
$router->get('/pengawas/dashboard', 'Pengawas\DashboardController@index', ['auth', 'role:admin,pengawas']);

// 2. Pemantauan Civitas & Kader (Baca Saja & Unduh Laporan)
$router->get('/pengawas/civitas', 'Pengawas\CivitasController@index', ['auth', 'role:admin,pengawas']);
$router->get('/pengawas/civitas/export', 'Pengawas\CivitasController@export', ['auth', 'role:admin,pengawas']);
$router->get('/pengawas/civitas/{id}', 'Pengawas\CivitasController@detail', ['auth', 'role:admin,pengawas']);

// 3. Pemantauan Warta & Publikasi Cabang
$router->get('/pengawas/berita', 'Pengawas\NewsController@index', ['auth', 'role:admin,pengawas']);
$router->get('/pengawas/berita/{id}', 'Pengawas\NewsController@detail', ['auth', 'role:admin,pengawas']);

// 4. Pemantauan Organisasi BPC
$router->get('/pengawas/organisasi/profil', 'Pengawas\OrganizationController@profil', ['auth', 'role:admin,pengawas']);
$router->get('/pengawas/organisasi/struktur', 'Pengawas\OrganizationController@struktur', ['auth', 'role:admin,pengawas']);

// 5. Analitik & Grafik Statistik
$router->get('/pengawas/statistik', 'Pengawas\StatisticsController@index', ['auth', 'role:admin,pengawas']);

// 6. Pemantauan Keamanan, Audit Log, dan Cadangan (Monitoring)
$router->get('/pengawas/pemantauan/audit-log', 'Pengawas\MonitoringController@auditLog', ['auth', 'role:admin,pengawas']);
$router->get('/pengawas/pemantauan/keamanan', 'Pengawas\MonitoringController@threats', ['auth', 'role:admin,pengawas']);
$router->get('/pengawas/pemantauan/ancaman', 'Pengawas\MonitoringController@threats', ['auth', 'role:admin,pengawas']);
$router->get('/pengawas/pemantauan/backup', 'Pengawas\MonitoringController@backup', ['auth', 'role:admin,pengawas']);
