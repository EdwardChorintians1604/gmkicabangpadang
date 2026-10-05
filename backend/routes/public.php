<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/routes/public.php
 * Deskripsi: Definisi seluruh rute halaman publik (dapat diakses umum tanpa login)
 * =====================================================================
 */

declare(strict_types=1);

use App\Core\Router;

/** @var Router $router */

// Beranda & Portal Informasi
$router->get('/', 'PublicController@home');
$router->get('/profil', 'PublicController@profil');
$router->get('/struktur-organisasi', 'PublicController@strukturOrganisasi');

// Warta Berita & Kegiatan
$router->get('/berita', 'PublicController@berita');
$router->get('/berita/{slug}', 'PublicController@detailBerita');

// Kontak & Pesan Masuk
$router->get('/kontak', 'PublicController@kontak');
$router->post('/kontak', 'PublicController@kirimKontak', ['csrf']);

// Penanganan Berkas Privat Terotentikasi
$router->get('/file/civitas/foto/{id}', 'FileController@fotoCivitas', ['auth']);
$router->get('/file/civitas/kta/{id}', 'FileController@ktaCivitas', ['auth']);
