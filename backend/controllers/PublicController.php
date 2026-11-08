<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Repositories\CivitasRepository;
use App\Repositories\KomisariatRepository;
use App\Services\CivitasService;
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

    public function adArt(Request $request): Response
    {
        $profile = $this->orgService->getProfile();

        return view('public.ad-art', [
            'pageTitle' => 'Anggaran Dasar & Anggaran Rumah Tangga (AD/ART) - GMKI Cabang Padang',
            'profile' => $profile,
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
        $nama = trim((string)$request->post('nama'));
        $email = trim((string)$request->post('email'));
        $pesan = trim((string)$request->post('pesan'));
        $isAjax = $request->isAjax() 
            || str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') 
            || $request->input('ajax') === '1';

        if (empty($nama) || empty($pesan)) {
            if ($isAjax) {
                return (new Response())->json([
                    'success' => false,
                    'message' => 'Silakan lengkapi nama dan pesan Anda.'
                ], 422);
            }
            Session::flash('error', 'Silakan lengkapi nama dan pesan Anda.');
            redirect('/kontak');
        }

        audit_log('SUBMIT_CONTACT_FORM', 'public_contact', null, [
            'nama' => $nama,
            'email' => $email,
        ]);

        if ($isAjax) {
            return (new Response())->json([
                'success' => true,
                'message' => 'Pesan Anda telah berhasil dicatat di sistem sekretariat BPC GMKI Cabang Padang.'
            ]);
        }

        Session::flash('success', 'Pesan Anda telah berhasil dikirim ke sekretariat BPC GMKI Cabang Padang.');
        redirect('/kontak');
    }

    public function registerIndex(Request $request): Response
    {
        $summary = $this->statsService->getDashboardSummary();
        $profile = $this->orgService->getProfile();
        $settingService = new \App\Services\SettingService();

        return view('register_for_civitas.index', [
            'pageTitle' => 'Portal Registrasi Civitas & Calon Anggota (Maper) - GMKI Cabang Padang',
            'summary' => $summary,
            'profile' => $profile,
            'isMapercaOpen' => $settingService->isMapercaRegistrationOpen(),
            'mapercaClosedMessage' => $settingService->get('maperca_closed_message', 'Pendaftaran calon anggota baru (Maperca) saat ini sedang ditutup.'),
        ], 'public');
    }

    public function registerMaperca(Request $request): Response
    {
        $settingService = new \App\Services\SettingService();
        if (!$settingService->isMapercaRegistrationOpen()) {
            Session::flash('warning', 'Pendaftaran Calon Anggota Baru (Maperca) saat ini sedang ditutup oleh BPC GMKI Cabang Padang. Jalur registrasi/pemutakhiran data Civitas & Anggota Cabang tetap dibuka.');
            redirect('/register_for_civitas');
        }

        $civitasRepo = new CivitasRepository();
        $perguruanTinggiList = $civitasRepo->getDistinctPerguruanTinggi();

        return view('register_for_civitas.register_as_cadet_(maper)', [
            'pageTitle' => 'Pendaftaran Calon Anggota (Maperca) - GMKI Cabang Padang',
            'perguruanTinggiList' => $perguruanTinggiList,
            'tahunSekarang' => (int) date('Y'),
        ], 'public');
    }

    public function submitRegisterMaperca(Request $request): Response
    {
        $settingService = new \App\Services\SettingService();
        if (!$settingService->isMapercaRegistrationOpen()) {
            Session::flash('error', 'Mohon maaf, pendaftaran Calon Anggota Baru (Maperca) saat ini sedang ditutup oleh BPC GMKI Cabang Padang.');
            redirect('/register_for_civitas');
        }

        $postData = $request->post();
        $rules = [
            'nama_lengkap' => 'required|min:3',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'telepon' => 'required',
            'email' => 'required|email',
            'perguruan_tinggi' => 'required',
        ];

        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            Session::setOld($postData);
            redirect('/register_for_civitas/maperca');
        }

        $civitasService = new CivitasService();
        $catatanTambahan = trim((string) ($postData['catatan'] ?? ''));
        $catatanFull = '[Registrasi Mandiri Maperca ' . date('d/m/Y H:i') . '] ' . $catatanTambahan;

        $asalCivitas = trim((string) ($postData['asal_civitas'] ?? ''));
        if ($asalCivitas === '') {
            $asalCivitas = trim((string) ($postData['tempat_lahir'] ?? ''));
        }

        $dataToSave = [
            'nim' => trim((string) ($postData['nim'] ?? '')),
            'nama_lengkap' => trim((string) $postData['nama_lengkap']),
            'jenis_kelamin' => trim((string) $postData['jenis_kelamin']),
            'tempat_lahir' => trim((string) ($postData['tempat_lahir'] ?? '')),
            'tanggal_lahir' => trim((string) ($postData['tanggal_lahir'] ?? '')),
            'telepon' => trim((string) ($postData['telepon'] ?? '')),
            'email' => trim((string) ($postData['email'] ?? '')),
            'perguruan_tinggi' => trim((string) ($postData['perguruan_tinggi'] ?? '')),
            'fakultas' => trim((string) ($postData['fakultas'] ?? '')),
            'jurusan' => trim((string) ($postData['jurusan'] ?? '')),
            'alamat_padang' => trim((string) ($postData['alamat_padang'] ?? '')),
            'asal_civitas' => $asalCivitas,
            'tahun_maperca' => !empty($postData['tahun_maperca']) ? (int) $postData['tahun_maperca'] : (int) date('Y'),
            'tingkat_kaderisasi' => 'Maperca',
            'status_keanggotaan' => 'Aktif',
            'anggota_komisariat' => 0,
            'komisariat' => null,
            'catatan' => $catatanFull,
            'created_by' => null,
        ];

        $foto = $request->file('foto_anggota');
        $res = $civitasService->create($dataToSave, $foto);

        if (!$res['success']) {
            Session::flash('error', 'Terjadi kendala saat menyimpan data pendaftaran. Silakan coba kembali.');
            Session::setOld($postData);
            redirect('/register_for_civitas/maperca');
        }

        $created = $civitasService->getById($res['id']);
        $assignedNim = $created['nim'] ?? $dataToSave['nim'];

        audit_log('PUBLIC_REGISTER_MAPERCA', 'civitas', (string) $res['id'], [
            'nim' => $assignedNim,
            'nama_lengkap' => $dataToSave['nama_lengkap'],
            'kampus' => $dataToSave['perguruan_tinggi'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        ]);

        Session::flash('success', "Puji Tuhan! Pendaftaran Calon Anggota (Maperca) atas nama {$dataToSave['nama_lengkap']} berhasil disimpan dengan ID: {$assignedNim}.");
        redirect('/register_for_civitas/bukti/' . urlencode($assignedNim));
    }

    public function registerMember(Request $request): Response
    {
        $komisariatRepo = new KomisariatRepository();
        $civitasRepo = new CivitasRepository();

        $komisariatNames = $komisariatRepo->names();
        $perguruanTinggiList = $civitasRepo->getDistinctPerguruanTinggi();
        $tahunList = $civitasRepo->getDistinctTahunMaperca();

        return view('register_for_civitas.register_as_member', [
            'pageTitle' => 'Registrasi & Pemutakhiran Civitas Terdaftar - GMKI Cabang Padang',
            'komisariatNames' => $komisariatNames,
            'perguruanTinggiList' => $perguruanTinggiList,
            'tahunList' => $tahunList,
            'tahunSekarang' => (int) date('Y'),
        ], 'public');
    }

    public function submitRegisterMember(Request $request): Response
    {
        $postData = $request->post();
        $rules = [
            'nama_lengkap' => 'required|min:3',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'telepon' => 'required',
            'email' => 'required|email',
            'perguruan_tinggi' => 'required',
            'tahun_maperca' => 'required|numeric',
        ];

        $isAnggotaKom = isset($postData['anggota_komisariat']) ? (int) $postData['anggota_komisariat'] : 0;
        if ($isAnggotaKom === 1) {
            $rules['komisariat'] = 'required';
        }

        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            Session::setOld($postData);
            redirect('/register_for_civitas/member');
        }

        $civitasService = new CivitasService();
        $catatanTambahan = trim((string) ($postData['catatan'] ?? ''));
        $catatanFull = '[Registrasi Mandiri Civitas ' . date('d/m/Y H:i') . '] ' . $catatanTambahan;

        $rawStatus = trim((string) ($postData['status_keanggotaan'] ?? 'Aktif'));
        if (!in_array($rawStatus, ['Aktif', 'Pasif', 'Alumni', 'Senior'], true)) {
            $rawStatus = 'Aktif';
        }

        $rawTingkat = trim((string) ($postData['tingkat_kaderisasi'] ?? 'Anggota'));
        if (!in_array($rawTingkat, ['Anggota', 'KK', 'Alumni', 'Maperca'], true)) {
            $rawTingkat = 'Anggota';
        }

        $asalCivitas = trim((string) ($postData['asal_civitas'] ?? ''));
        if ($asalCivitas === '') {
            $asalCivitas = trim((string) ($postData['tempat_lahir'] ?? ''));
        }

        $dataToSave = [
            'nim' => trim((string) ($postData['nim'] ?? '')),
            'nama_lengkap' => trim((string) $postData['nama_lengkap']),
            'jenis_kelamin' => trim((string) $postData['jenis_kelamin']),
            'tempat_lahir' => trim((string) ($postData['tempat_lahir'] ?? '')),
            'tanggal_lahir' => trim((string) ($postData['tanggal_lahir'] ?? '')),
            'telepon' => trim((string) ($postData['telepon'] ?? '')),
            'email' => trim((string) ($postData['email'] ?? '')),
            'perguruan_tinggi' => trim((string) ($postData['perguruan_tinggi'] ?? '')),
            'fakultas' => trim((string) ($postData['fakultas'] ?? '')),
            'jurusan' => trim((string) ($postData['jurusan'] ?? '')),
            'alamat_padang' => trim((string) ($postData['alamat_padang'] ?? '')),
            'asal_civitas' => $asalCivitas,
            'tahun_maperca' => !empty($postData['tahun_maperca']) ? (int) $postData['tahun_maperca'] : (int) date('Y'),
            'tingkat_kaderisasi' => $rawTingkat,
            'status_keanggotaan' => $rawStatus,
            'anggota_komisariat' => $isAnggotaKom,
            'komisariat' => ($isAnggotaKom === 1 && !empty($postData['komisariat'])) ? trim((string) $postData['komisariat']) : null,
            'catatan' => $catatanFull,
            'created_by' => null,
        ];

        $foto = $request->file('foto_anggota');
        $res = $civitasService->create($dataToSave, $foto);

        if (!$res['success']) {
            Session::flash('error', 'Terjadi kendala saat menyimpan pemutakhiran data civitas.');
            Session::setOld($postData);
            redirect('/register_for_civitas/member');
        }

        $created = $civitasService->getById($res['id']);
        $assignedNim = $created['nim'] ?? $dataToSave['nim'];

        audit_log('PUBLIC_REGISTER_CIVITAS', 'civitas', (string) $res['id'], [
            'nim' => $assignedNim,
            'nama_lengkap' => $dataToSave['nama_lengkap'],
            'komisariat' => $dataToSave['komisariat'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        ]);

        Session::flash('success', "Puji Tuhan! Data civitas atas nama {$dataToSave['nama_lengkap']} berhasil disimpan ke basis data cabang dengan ID: {$assignedNim}.");
        redirect('/register_for_civitas/bukti/' . urlencode($assignedNim));
    }

    public function buktiRegistrasi(Request $request, string $nim): Response
    {
        $civitasRepo = new CivitasRepository();
        $cleanNim = urldecode(trim($nim));
        $member = $civitasRepo->findByNim($cleanNim);

        if (!$member) {
            Session::flash('error', 'Data civitas dengan ID ' . e($cleanNim) . ' tidak ditemukan.');
            redirect('/register_for_civitas');
        }

        return view('register_for_civitas.bukti', [
            'pageTitle' => 'Bukti Registrasi & Profil Civitas - ' . ($member['nama_lengkap'] ?? 'GMKI Padang'),
            'member' => $member,
        ], 'public');
    }

    /**
     * Sajikan foto resmi civitas/calon anggota secara publik untuk lembar bukti registrasi.
     */
    public function streamCivitasFoto(Request $request, string $nim): void
    {
        $cleanNim = urldecode(trim($nim));
        $civitasRepo = new CivitasRepository();
        $member = $civitasRepo->findByNim($cleanNim);

        $storageService = new \App\Services\StorageService();
        $filePath = null;

        if ($member && !empty($member['foto_anggota'])) {
            $candidatePath = $storageService->getPrivatePath('foto_anggota', (string) $member['foto_anggota']);
            if (file_exists($candidatePath) && is_readable($candidatePath)) {
                $filePath = $candidatePath;
            }
        }

        if (!$filePath) {
            $defaultPath = dirname(__DIR__, 2) . '/frontend/public/assets/images/default-profile.png';
            if (file_exists($defaultPath) && is_readable($defaultPath)) {
                $filePath = $defaultPath;
            }
        }

        if ($filePath && file_exists($filePath)) {
            $response = new Response();
            $response->file($filePath, basename($filePath), 'inline');
            return;
        }

        http_response_code(404);
        exit('Foto tidak ditemukan.');
    }
}
