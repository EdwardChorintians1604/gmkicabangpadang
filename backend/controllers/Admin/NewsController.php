<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Admin/NewsController.php
 * Deskripsi: Controller Manajemen Warta Berita & Publikasi untuk Administrator
 * Lapisan: Controller (Admin)
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Services\NewsService;

class NewsController
{
    protected NewsService $newsService;

    public function __construct()
    {
        $this->newsService = new NewsService();
    }

    /**
     * Tampilkan daftar warta & berita cabang
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

        return view('admin.berita.index', [
            'pageTitle' => 'Manajemen Warta & Berita - GMKI Cabang Padang',
            'news' => $newsData['data'],
            'total' => $newsData['total'],
            'currentPage' => $newsData['current_page'],
            'totalPages' => $newsData['total_pages'],
            'search' => $search,
            'status' => $status,
        ], 'admin');
    }

    /**
     * Tampilkan formulir tulis berita baru
     */
    public function create(Request $request): Response
    {
        Authorization::authorize('news.create');

        return view('admin.berita.form', [
            'pageTitle' => 'Tulis Berita Baru - GMKI Cabang Padang',
            'article' => null,
            'isEdit' => false,
        ], 'admin');
    }

    /**
     * Simpan berita baru ke basis data
     */
    public function store(Request $request): Response
    {
        Authorization::authorize('news.create');

        $validator = Validator::make($request->post(), [
            'judul' => 'required|min:5|max:255',
            'konten' => 'required',
            'kategori' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            Session::setOld($request->post());
            redirect('/admin/berita/create');
        }

        $coverFile = $request->file('gambar_sampul');
        $result = $this->newsService->create($request->post(), $coverFile);

        if (!$result['success']) {
            Session::flash('error', 'Gagal menyimpan berita.');
            redirect('/admin/berita/create');
        }

        Session::flash('success', 'Berita berhasil diterbitkan.');
        redirect('/admin/berita');
    }

    /**
     * Tampilkan formulir edit berita
     */
    public function edit(Request $request, string $id): Response
    {
        Authorization::authorize('news.update');

        $article = $this->newsService->getById((int)$id);
        if (!$article) {
            Session::flash('error', 'Berita tidak ditemukan.');
            redirect('/admin/berita');
        }

        return view('admin.berita.form', [
            'pageTitle' => 'Edit Berita - ' . $article['judul'],
            'article' => $article,
            'isEdit' => true,
        ], 'admin');
    }

    /**
     * Simpan perubahan berita
     */
    public function update(Request $request, string $id): Response
    {
        Authorization::authorize('news.update');

        $validator = Validator::make($request->post(), [
            'judul' => 'required|min:5|max:255',
            'konten' => 'required',
            'kategori' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            redirect("/admin/berita/{$id}/edit");
        }

        $coverFile = $request->file('gambar_sampul');
        $this->newsService->update((int)$id, $request->post(), $coverFile);

        Session::flash('success', 'Perubahan berita berhasil disimpan.');
        redirect('/admin/berita');
    }

    /**
     * Hapus berita dari basis data
     */
    public function delete(Request $request, string $id): Response
    {
        Authorization::authorize('news.delete');

        $this->newsService->delete((int)$id);
        Session::flash('success', 'Berita berhasil dihapus.');
        redirect('/admin/berita');
    }
}
