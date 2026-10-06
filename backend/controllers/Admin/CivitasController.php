<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Admin/CivitasController.php
 * Deskripsi: Controller Pengelolaan Data Civitas (anggota resmi, non-Maperca).
 *            Kader baru (Maperca) ditangani oleh MapercaController (turunan).
 * Lapisan: Controller (Admin / Pengawas pencatat)
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Repositories\KomisariatRepository;
use App\Services\CivitasService;

class CivitasController
{
    protected CivitasService $civitasService;

    /** Awalan URL modul ini */
    protected string $basePath = '/admin/civitas';

    /** 'anggota' = civitas resmi (non-Maperca), 'maperca' = kader baru */
    protected string $kelompok = 'anggota';

    public function __construct()
    {
        $this->civitasService = new CivitasService();
    }

    /**
     * Pilih layout sesuai peran: pengawas (Ketcab/Sekcab) memakai panel pengawas
     */
    protected function layout(): string
    {
        return Authorization::role('pengawas') ? 'pengawas' : 'admin';
    }

    /**
     * Paksa jenjang kaderisasi sesuai kelompok modul:
     * - modul Maperca  : selalu 'Maperca'
     * - modul Anggota  : tidak boleh 'Maperca' (default 'Anggota')
     */
    protected function normalizeData(array $data): array
    {
        unset($data['file_kta']);

        if ($this->kelompok === 'maperca') {
            $data['tingkat_kaderisasi'] = 'Maperca';
            $data['status_keanggotaan'] = $data['status_keanggotaan'] ?? 'Aktif';
            $data['anggota_komisariat'] = 0;
            $data['komisariat'] = null;
            $data['tahun_maperca'] = !empty($data['tahun_maperca']) ? (int) $data['tahun_maperca'] : null;
        } else {
            $isAnggotaKom = isset($data['anggota_komisariat']) ? (int) $data['anggota_komisariat'] : 0;
            if ($isAnggotaKom === 1 && !empty($data['komisariat']) && trim((string) $data['komisariat']) !== '') {
                $data['anggota_komisariat'] = 1;
                $data['komisariat'] = trim((string) $data['komisariat']);
            } else {
                $data['anggota_komisariat'] = 0;
                $data['komisariat'] = null;
            }
            $tk = $data['tingkat_kaderisasi'] ?? '';
            if (!in_array($tk, ['Anggota', 'KK', 'Alumni'], true)) {
                $data['tingkat_kaderisasi'] = 'Anggota';
            }
        }

        return $data;
    }

    protected function viewData(array $extra = []): array
    {
        return $extra + [
            'basePath' => $this->basePath,
            'kelompok' => $this->kelompok,
            'labelSingular' => $this->kelompok === 'maperca' ? 'Kader Baru (Maperca)' : 'Anggota / Civitas',
            'komisariatNames' => (new KomisariatRepository())->names(),
        ];
    }

    /**
     * Tampilkan daftar civitas / kader baru dengan filter pencarian
     */
    public function index(Request $request): Response
    {
        Authorization::authorize('civitas.view');

        $page = (int) $request->query('page', 1);
        $search = $request->query('q');
        $komisariat = $request->query('komisariat');
        $pt = $request->query('perguruan_tinggi');
        $tahun = $request->query('tahun_maperca');
        $status = $request->query('status');

        $filters = array_filter([
            'search' => $search,
            'komisariat' => $komisariat,
            'perguruan_tinggi' => $pt,
            'tahun_maperca' => $tahun,
            'status_keanggotaan' => $status,
        ]);
        $filters['kelompok'] = $this->kelompok;

        $result = $this->civitasService->getList($filters, $page, 20);

        $title = $this->kelompok === 'maperca'
            ? 'Data Kader Baru (Maperca) - GMKI Cabang Padang'
            : 'Kelola Data Civitas - GMKI Cabang Padang';

        return view('admin.civitas.index', $this->viewData([
            'pageTitle' => $title,
            'civitas' => $result['data'],
            'total' => $result['total'],
            'currentPage' => $result['current_page'],
            'totalPages' => $result['total_pages'],
            'komisariatList' => $result['komisariat_list'],
            'ptList' => $result['perguruan_tinggi_list'],
            'tahunList' => $result['tahun_maperca_list'],
            'search' => $search,
            'selectedKomisariat' => $komisariat,
            'selectedPt' => $pt,
            'selectedTahun' => $tahun,
            'selectedStatus' => $status,
        ]), $this->layout());
    }

    /**
     * Tampilkan form penambahan data baru
     */
    public function create(Request $request): Response
    {
        Authorization::authorize('civitas.create');

        return view('admin.civitas.form', $this->viewData([
            'pageTitle' => $this->kelompok === 'maperca'
                ? 'Pendaftaran Anggota Baru'
                : 'Tambah Data Civitas / Anggota',
            'member' => null,
            'isEdit' => false,
        ]), $this->layout());
    }

    /**
     * Simpan data baru ke basis data
     */
    public function store(Request $request): Response
    {
        Authorization::authorize('civitas.create');

        $rules = [
            'nama_lengkap' => 'required|min:3',
            'jenis_kelamin' => 'required|in:L,P',
            'perguruan_tinggi' => 'required',
        ];
        $postData = $request->post();
        if (!empty($postData['nim']) && trim((string)$postData['nim']) !== '') {
            $rules['nim'] = 'unique:civitas,nim';
        }
        if ($this->kelompok !== 'maperca') {
            $rules['tahun_maperca'] = 'required|numeric';
            if (($postData['anggota_komisariat'] ?? '') === '1') {
                $rules['komisariat'] = 'required';
            }
        } else {
            $rules['tahun_maperca'] = 'numeric';
        }

        $validator = Validator::make($request->post(), $rules);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            Session::setOld($request->post());
            redirect("{$this->basePath}/create");
        }

        $foto = $request->file('foto_anggota');
        $res = $this->civitasService->create($this->normalizeData($request->post()), $foto);

        if (!$res['success']) {
            Session::flash('error', 'Gagal menyimpan data.');
            redirect("{$this->basePath}/create");
        }

        Session::flash('success', $this->kelompok === 'maperca'
            ? 'Pendaftaran anggota baru berhasil disimpan.'
            : 'Data anggota baru berhasil ditambahkan.');
        redirect($this->basePath);
    }

    /**
     * Tampilkan detail biodata lengkap
     */
    public function detail(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.view');

        $member = $this->civitasService->getById((int) $id);
        if (!$member) {
            Session::flash('error', 'Data tidak ditemukan.');
            redirect($this->basePath);
        }

        return view('admin.civitas.detail', $this->viewData([
            'pageTitle' => 'Biodata - ' . $member['nama_lengkap'],
            'member' => $member,
        ]), $this->layout());
    }

    /**
     * Tampilkan form ubah data
     */
    public function edit(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.update');

        $member = $this->civitasService->getById((int) $id);
        if (!$member) {
            Session::flash('error', 'Data tidak ditemukan.');
            redirect($this->basePath);
        }

        return view('admin.civitas.form', $this->viewData([
            'pageTitle' => 'Edit Data - ' . $member['nama_lengkap'],
            'member' => $member,
            'isEdit' => true,
        ]), $this->layout());
    }

    /**
     * Perbarui data di basis data
     */
    public function update(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.update');

        $rules = [
            'nama_lengkap' => 'required|min:3',
            'jenis_kelamin' => 'required|in:L,P',
            'perguruan_tinggi' => 'required',
        ];
        $postData = $request->post();
        if (!empty($postData['nim']) && trim((string)$postData['nim']) !== '') {
            $rules['nim'] = "unique:civitas,nim,{$id}";
        }
        if ($this->kelompok !== 'maperca') {
            $rules['tahun_maperca'] = 'required|numeric';
            if (($postData['anggota_komisariat'] ?? '') === '1') {
                $rules['komisariat'] = 'required';
            }
        } else {
            $rules['tahun_maperca'] = 'numeric';
        }

        $validator = Validator::make($request->post(), $rules);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            redirect("{$this->basePath}/{$id}/edit");
        }

        $foto = $request->file('foto_anggota');
        $this->civitasService->update((int) $id, $this->normalizeData($request->post()), $foto);

        Session::flash('success', 'Perubahan data berhasil disimpan.');
        redirect("{$this->basePath}/{$id}");
    }

    /**
     * Lantik kader baru (Maperca) menjadi anggota resmi GMKI Cabang Padang
     */
    public function lantik(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.update');

        $member = $this->civitasService->getById((int) $id);
        if (!$member || $member['tingkat_kaderisasi'] !== 'Maperca') {
            Session::flash('error', 'Data kader baru tidak ditemukan.');
            redirect('/admin/maperca');
        }

        // Penempatan komisariat bersifat opsional (tidak semua anggota langsung masuk komisariat)
        $komisariatInput = trim((string) ($request->post()['komisariat'] ?? ''));
        $hasKomisariat = !empty($komisariatInput) && (new KomisariatRepository())->findByName($komisariatInput);

        $updateData = [
            'tingkat_kaderisasi' => 'Anggota',
            'status_keanggotaan' => 'Aktif',
            'komisariat' => $hasKomisariat ? $komisariatInput : null,
            'anggota_komisariat' => $hasKomisariat ? 1 : 0,
        ];

        // Jika tahun maperca belum terisi, otomatis gunakan tahun berjalan
        if (empty($member['tahun_maperca'])) {
            $updateData['tahun_maperca'] = (int) date('Y');
        }

        $this->civitasService->update((int) $id, $updateData);

        Session::flash('success', "{$member['nama_lengkap']} resmi dilantik menjadi Anggota sah GMKI Cabang Padang dan dipindahkan ke Data Civitas.");
        redirect('/admin/maperca');
    }

    /**
     * Hapus data dari basis data
     */
    public function delete(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.delete');

        $this->civitasService->delete((int) $id);
        Session::flash('success', 'Data berhasil dihapus.');
        redirect($this->basePath);
    }

    /**
     * Tampilkan formulir impor massal CSV
     */
    public function imporForm(Request $request): Response
    {
        Authorization::authorize('civitas.import');

        return view('admin.civitas.impor', [
            'pageTitle' => 'Impor Data Civitas via Berkas CSV',
        ], 'admin');
    }

    /**
     * Proses impor massal CSV
     */
    public function imporProcess(Request $request): Response
    {
        Authorization::authorize('civitas.import');

        $file = $request->file('file_csv');
        if (!$file || empty($file['tmp_name'])) {
            Session::flash('error', 'Silakan pilih berkas CSV untuk diimpor.');
            redirect('/admin/civitas/impor');
        }

        $result = $this->civitasService->importCsv($file['tmp_name']);

        if (!$result['success']) {
            Session::flash('error', $result['message']);
            redirect('/admin/civitas/impor');
        }

        $msg = "Impor selesai: {$result['imported']} data berhasil diproses.";
        if ($result['failed'] > 0) {
            $msg .= " ({$result['failed']} baris gagal/dilewati).";
        }

        Session::flash('success', $msg);
        redirect('/admin/civitas');
    }

    /**
     * Ekspor data ke berkas CSV
     */
    public function export(Request $request): void
    {
        Authorization::authorize('civitas.export');

        $filters = array_filter([
            'search' => $request->query('q'),
            'komisariat' => $request->query('komisariat'),
            'perguruan_tinggi' => $request->query('perguruan_tinggi'),
            'tahun_maperca' => $request->query('tahun_maperca'),
            'status_keanggotaan' => $request->query('status'),
        ]);
        $filters['kelompok'] = $this->kelompok;

        $this->civitasService->generateExportCsv($filters);
    }
}
