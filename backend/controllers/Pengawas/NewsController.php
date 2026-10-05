<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Pengawas/NewsController.php
 * Deskripsi: Controller Pemantauan Warta & Publikasi Cabang untuk Pengawas
 * Lapisan: Controller (Pengawas) - Mode Baca Saja
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Pengawas;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\NewsService;

class NewsController
{
    protected NewsService $newsService;

    public function __construct()
    {
        $this->newsService = new NewsService();
    }

    /**
     * Tampilkan daftar warta cabang dalam mode pantauan
     */
    public function index(Request $request): Response
    {
        Authorization::authorize('news.view');

        $page = (int)$request->query('page', 1);
        $search = $request->query('q');
        $status = $request->query('status');

        $filters = array_filter([
            'search' => $search,
            'status' => $status,
        ]);

        $newsData = $this->newsService->getAdminList($filters, $page, 15);

        return view('pengawas.berita.index', [
            'pageTitle' => 'Pemantauan Warta & Publikasi - GMKI Cabang Padang',
            'news' => $newsData['data'],
            'total' => $newsData['total'],
            'currentPage' => $newsData['current_page'],
            'totalPages' => $newsData['total_pages'],
            'search' => $search,
            'status' => $status,
        ], 'pengawas');
    }

    /**
     * Tampilkan pratinjau berita lengkap
     */
    public function detail(Request $request, string $id): Response
    {
        Authorization::authorize('news.view');

        $article = $this->newsService->getById((int)$id);
        if (!$article) {
            Session::flash('error', 'Berita tidak ditemukan.');
            redirect('/pengawas/berita');
        }

        return view('pengawas.berita.detail', [
            'pageTitle' => 'Detail Warta Cabang - ' . $article['judul'],
            'article' => $article,
        ], 'pengawas');
    }
}
