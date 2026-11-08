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
$router->get('/ad-art', 'PublicController@adArt');

// Warta Berita & Kegiatan
$router->get('/berita', 'PublicController@berita');
$router->get('/berita/{slug}', 'PublicController@detailBerita');

// Kontak & Pesan Masuk
$router->get('/kontak', 'PublicController@kontak');
$router->post('/kontak', 'PublicController@kirimKontak', ['csrf']);

// Portal Pendaftaran & Registrasi Civitas & Calon Anggota (Maperca)
$router->get('/register_for_civitas', 'PublicController@registerIndex');
$router->get('/register_as_member_and_cadet', 'PublicController@registerIndex');
$router->get('/register_for_civitas/maperca', 'PublicController@registerMaperca');
$router->get('/register_as_cadet', 'PublicController@registerMaperca');
$router->post('/register_for_civitas/maperca', 'PublicController@submitRegisterMaperca', ['csrf']);
$router->get('/register_for_civitas/member', 'PublicController@registerMember');
$router->get('/register_as_member', 'PublicController@registerMember');
$router->post('/register_for_civitas/member', 'PublicController@submitRegisterMember', ['csrf']);

// Penanganan Berkas Privat Terotentikasi (Foto Anggota & KTA)
$router->get('/private/foto-anggota/{filename}', 'FileController@streamFoto', ['auth']);
$router->get('/private/kta/{filename}', 'FileController@streamKta', ['auth']);
$router->get('/file/civitas/foto/{filename}', 'FileController@streamFoto', ['auth']);
$router->get('/file/civitas/kta/{filename}', 'FileController@streamKta', ['auth']);
