<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/routes/public.php
 * Deskripsi: Definisi seluruh rute halaman publik (dapat diakses umum tanpa login)
 * =====================================================================
 */

declare(strict_types=1);

if (!defined('GMKI_SECURE_ACCESS')) {
    http_response_code(403);
    exit('Akses langsung ditolak.');
}

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

// Penanganan Berkas Privat Terotentikasi (Foto Anggota & KTA)
$router->get('/private/foto-anggota/{filename}', 'FileController@streamFoto', ['auth']);
$router->get('/private/kta/{filename}', 'FileController@streamKta', ['auth']);
$router->get('/file/civitas/foto/{filename}', 'FileController@streamFoto', ['auth']);
$router->get('/file/civitas/kta/{filename}', 'FileController@streamKta', ['auth']);
