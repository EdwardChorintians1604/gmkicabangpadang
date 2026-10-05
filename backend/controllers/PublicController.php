<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\NewsService;
use App\Services\OrganizationService;
use App\Services\StatisticsService;

class PublicController
{
    protected OrganizationService $orgService;
    protected NewsService $newsService;
    protected StatisticsService $statsService;

    public function __construct()
    {
        $this->orgService = new OrganizationService();
        $this->newsService = new NewsService();
        $this->statsService = new StatisticsService();
    }

    public function home(Request $request): Response
    {
        $profile = $this->orgService->getProfile();
        $latestNews = $this->newsService->getLatest(3);
        $summary = $this->statsService->getDashboardSummary();
        $structure = $this->orgService->getStructure(true);

        return view('public.home', [
            'pageTitle' => 'Beranda - GMKI Cabang Padang',
            'profile' => $profile,
            'latestNews' => $latestNews,
            'summary' => $summary,
            'structure' => array_slice($structure, 0, 4),
        ], 'public');
    }

    public function profil(Request $request): Response
    {
        $profile = $this->orgService->getProfile();

        return view('public.profil', [
            'pageTitle' => 'Profil & Sejarah - GMKI Cabang Padang',
            'profile' => $profile,
        ], 'public');
    }

    public function strukturOrganisasi(Request $request): Response
    {
        $profile = $this->orgService->getProfile();
        $structure = $this->orgService->getStructure(true);

        return view('public.struktur-organisasi', [
            'pageTitle' => 'Struktur BPC - GMKI Cabang Padang',
            'profile' => $profile,
            'structure' => $structure,
        ], 'public');
    }

    public function berita(Request $request): Response
    {
        $page = (int)$request->query('page', 1);
        $kategori = $request->query('kategori');
        $search = $request->query('q');

        $filters = array_filter([
            'kategori' => $kategori,
            'search' => $search,
        ]);

        $newsData = $this->newsService->getPublicList($filters, $page, 6);

        return view('public.berita', [
            'pageTitle' => 'Warta & Berita Cabang - GMKI Cabang Padang',
            'news' => $newsData['data'],
            'total' => $newsData['total'],
            'currentPage' => $newsData['current_page'],
            'totalPages' => $newsData['total_pages'],
            'kategori' => $kategori,
            'search' => $search,
        ], 'public');
    }

    public function detailBerita(Request $request, string $slug): Response
    {
        $article = $this->newsService->getBySlug($slug, true);

        if (!$article || $article['status'] !== 'published') {
            http_response_code(404);
            return view('errors.404', ['message' => 'Berita tidak ditemukan.'], null)->setStatusCode(404);
        }

        $related = $this->newsService->getLatest(3, $article['id']);

        return view('public.detail-berita', [
            'pageTitle' => $article['judul'] . ' - GMKI Cabang Padang',
            'article' => $article,
            'related' => $related,
        ], 'public');
    }

    public function kontak(Request $request): Response
    {
        $profile = $this->orgService->getProfile();

        return view('public.kontak', [
            'pageTitle' => 'Kontak & Sekretariat - GMKI Cabang Padang',
            'profile' => $profile,
        ], 'public');
    }

    public function kirimKontak(Request $request): Response
    {
        $nama = $request->post('nama');
        $email = $request->post('email');
        $pesan = $request->post('pesan');

        if (empty($nama) || empty($pesan)) {
            Session::flash('error', 'Silakan lengkapi nama dan pesan Anda.');
            redirect('/kontak');
        }

        audit_log('SUBMIT_CONTACT_FORM', 'public_contact', null, [
            'nama' => $nama,
            'email' => $email,
        ]);

        Session::flash('success', 'Pesan Anda telah berhasil dikirim ke sekretariat BPC GMKI Cabang Padang.');
        redirect('/kontak');
    }
}
