<?php

namespace App\Controllers;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Services\CivitasService;

class CivitasController
{
    protected CivitasService $civitasService;

    public function __construct()
    {
        $this->civitasService = new CivitasService();
    }

    public function index(Request $request): Response
    {
        Authorization::authorize('civitas.view');

        $page = (int)$request->query('page', 1);
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

        $result = $this->civitasService->getList($filters, $page, 20);

        return view('civitas.index', [
            'pageTitle' => 'Database Civitas & Kader - GMKI Cabang Padang',
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
        ], 'dashboard');
    }

    public function create(Request $request): Response
    {
        Authorization::authorize('civitas.create');

        return view('civitas.form', [
            'pageTitle' => 'Tambah Data Civitas / Anggota Baru',
            'member' => null,
            'isEdit' => false,
        ], 'dashboard');
    }

    public function store(Request $request): Response
    {
        Authorization::authorize('civitas.create');

        $rules = [
            'nama_lengkap' => 'required|min:3',
            'jenis_kelamin' => 'required|in:L,P',
            'perguruan_tinggi' => 'required',
            'komisariat' => 'required',
            'tahun_maperca' => 'required|numeric',
        ];
        $postData = $request->post();
        if (!empty($postData['nim']) && trim((string)$postData['nim']) !== '') {
            $rules['nim'] = 'unique:civitas,nim';
        }
        $validator = Validator::make($postData, $rules);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            Session::setOld($request->post());
            redirect('/admin/civitas/create');
        }

        $foto = $request->file('foto_anggota');
        $kta = $request->file('file_kta');

        $res = $this->civitasService->create($request->post(), $foto, $kta);

        if (!$res['success']) {
            Session::flash('error', 'Gagal menyimpan data anggota.');
            redirect('/admin/civitas/create');
        }

        Session::flash('success', 'Data anggota baru berhasil ditambahkan.');
        redirect('/admin/civitas');
    }

    public function detail(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.view');

        $member = $this->civitasService->getById((int)$id);
        if (!$member) {
            Session::flash('error', 'Data anggota tidak ditemukan.');
            redirect('/admin/civitas');
        }

        return view('civitas.detail', [
            'pageTitle' => 'Profil Civitas - ' . $member['nama_lengkap'],
            'member' => $member,
        ], 'dashboard');
    }

    public function edit(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.update');

        $member = $this->civitasService->getById((int)$id);
        if (!$member) {
            Session::flash('error', 'Data anggota tidak ditemukan.');
            redirect('/admin/civitas');
        }

        return view('civitas.form', [
            'pageTitle' => 'Edit Data Civitas - ' . $member['nama_lengkap'],
            'member' => $member,
            'isEdit' => true,
        ], 'dashboard');
    }

    public function update(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.update');

        $rules = [
            'nama_lengkap' => 'required|min:3',
            'jenis_kelamin' => 'required|in:L,P',
            'perguruan_tinggi' => 'required',
            'komisariat' => 'required',
            'tahun_maperca' => 'required|numeric',
        ];
        $postData = $request->post();
        if (!empty($postData['nim']) && trim((string)$postData['nim']) !== '') {
            $rules['nim'] = "unique:civitas,nim,{$id}";
        }
        $validator = Validator::make($postData, $rules);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            redirect("/admin/civitas/{$id}/edit");
        }

        $foto = $request->file('foto_anggota');
        $kta = $request->file('file_kta');

        $this->civitasService->update((int)$id, $request->post(), $foto, $kta);

        Session::flash('success', 'Perubahan data civitas berhasil disimpan.');
        redirect("/admin/civitas/{$id}");
    }

    public function delete(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.delete');

        $this->civitasService->delete((int)$id);
        Session::flash('success', 'Data anggota berhasil dihapus.');
        redirect('/admin/civitas');
    }

    public function imporForm(Request $request): Response
    {
        Authorization::authorize('civitas.import');

        return view('civitas.impor', [
            'pageTitle' => 'Impor Data Civitas via CSV/Excel',
        ], 'dashboard');
    }

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

        $this->civitasService->generateExportCsv($filters);
    }
}
