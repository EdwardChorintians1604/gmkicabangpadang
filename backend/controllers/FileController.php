<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Services\StorageService;

class FileController
{
    protected StorageService $storageService;

    public function __construct()
    {
        $this->storageService = new StorageService();
    }

    public function streamFoto(Request $request, string $filename): void
    {
        // Hanya pengguna yang sudah masuk yang dapat melihat foto privat civitas
        if (Auth::guest()) {
            http_response_code(403);
            exit('Akses ditolak.');
        }

        $filePath = $this->storageService->getPrivatePath('foto_anggota', $filename);
        $response = new Response();
        $response->file($filePath, $filename, 'inline');
    }

    public function streamKta(Request $request, string $filename): void
    {
        // Hanya pengguna yang sudah masuk yang dapat melihat / mengunduh KTA privat civitas
        if (Auth::guest()) {
            http_response_code(403);
            exit('Akses ditolak.');
        }

        $filePath = $this->storageService->getPrivatePath('kta', $filename);
        $response = new Response();
        $response->file($filePath, $filename, 'inline');
    }
}
