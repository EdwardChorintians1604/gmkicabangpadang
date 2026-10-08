<?php

declare(strict_types=1);

if (!defined('GMKI_SECURE_ACCESS')) {
    http_response_code(403);
    exit('Akses langsung ditolak.');
}

use App\Core\Router;

/** @var Router $router */
$router->get('/ruang-kerja', 'WorkspaceController@dashboard', ['auth', 'role:admin,operator,sekcab,bencab,sekfung_medko']);
$router->get('/ketcab/dashboard', 'WorkspaceController@dashboard', ['auth', 'role:admin,ketcab']);

$router->get('/sekcab/inventaris', 'WorkspaceController@inventory', ['auth', 'role:admin,sekcab']);
$router->post('/sekcab/inventaris', 'WorkspaceController@saveInventory', ['auth', 'role:admin,sekcab', 'csrf']);
$router->post('/sekcab/inventaris/{id}/delete', 'WorkspaceController@deleteInventory', ['auth', 'role:admin,sekcab', 'csrf']);

$router->get('/sekcab/arsip', 'WorkspaceController@archives', ['auth', 'role:admin,sekcab']);
$router->post('/sekcab/arsip', 'WorkspaceController@saveArchive', ['auth', 'role:admin,sekcab', 'csrf']);
$router->post('/sekcab/arsip/{id}/delete', 'WorkspaceController@deleteArchive', ['auth', 'role:admin,sekcab', 'csrf']);

$router->get('/bencab/laporan', 'WorkspaceController@finance', ['auth', 'role:admin,ketcab,bencab']);
$router->post('/bencab/laporan', 'WorkspaceController@saveFinance', ['auth', 'role:bencab', 'csrf']);
$router->post('/bencab/laporan/{id}/delete', 'WorkspaceController@deleteFinance', ['auth', 'role:bencab', 'csrf']);
$router->get('/ruang-kerja/berkas', 'WorkspaceController@files', ['auth', 'role:admin,ketcab,sekcab,bencab']);
$router->get('/dokumen/{id}/unduh', 'WorkspaceController@downloadDocument', ['auth']);

$router->get('/ketcab/strategi', 'WorkspaceController@strategies', ['auth', 'role:admin,ketcab']);
$router->post('/ketcab/strategi', 'WorkspaceController@saveStrategy', ['auth', 'role:admin,ketcab', 'csrf']);
$router->post('/ketcab/strategi/{id}/delete', 'WorkspaceController@deleteStrategy', ['auth', 'role:admin,ketcab', 'csrf']);

$router->get('/ketcab/koordinasi', 'WorkspaceController@coordination', ['auth', 'role:ketcab']);
$router->get('/ruang-kerja/koordinasi', 'WorkspaceController@coordination', ['auth', 'role:sekcab,bencab,sekfung_medko']);
$router->get('/admin/koordinasi', 'WorkspaceController@coordination', ['auth', 'role:admin']);
$router->get('/ketcab/koordinasi/pesan', 'WorkspaceController@coordinationMessages', ['auth', 'role:ketcab']);
$router->get('/ruang-kerja/koordinasi/pesan', 'WorkspaceController@coordinationMessages', ['auth', 'role:sekcab,bencab,sekfung_medko']);
$router->get('/admin/koordinasi/pesan', 'WorkspaceController@coordinationMessages', ['auth', 'role:admin']);
$router->post('/ketcab/koordinasi', 'WorkspaceController@sendMessage', ['auth', 'role:ketcab', 'csrf']);
$router->post('/ruang-kerja/koordinasi', 'WorkspaceController@sendMessage', ['auth', 'role:sekcab,bencab,sekfung_medko', 'csrf']);
$router->post('/admin/koordinasi', 'WorkspaceController@sendMessage', ['auth', 'role:admin', 'csrf']);

$router->get('/ruang-kerja/antrian', 'QueueController@index', ['auth', 'role:admin,ketcab,bencab']);
$router->post('/ruang-kerja/antrian/laporan-keuangan', 'QueueController@enqueueFinanceExport', ['auth', 'role:admin,ketcab,bencab', 'csrf']);
$router->get('/ruang-kerja/antrian/{id}/unduh', 'QueueController@download', ['auth', 'role:admin,ketcab,bencab']);
