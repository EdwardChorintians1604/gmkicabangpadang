<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Pengawas/CivitasController.php
 * Deskripsi: Controller Pemantauan Data Civitas untuk Pengawas & BPC Leaders
 * Lapisan: Controller (Pengawas) - Mode Baca Saja & Ekspor
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Pengawas;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\CivitasService;

class CivitasController
{
    protected CivitasService $civitasService;

    public function __construct()
    {
        $this->civitasService = new CivitasService();
    }

    /**
     * Tampilkan data civitas dalam mode pantauan baca-saja
     */
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

        return view('pengawas.civitas.index', [
            'pageTitle' => 'Pemantauan Data Civitas & Kader - GMKI Cabang Padang',
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
        ], 'pengawas');
    }

    /**
     * Tampilkan detail anggota secara baca-saja
     */
    public function detail(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.view');

        $member = $this->civitasService->getById((int)$id);
        if (!$member) {
            Session::flash('error', 'Data anggota tidak ditemukan.');
            redirect('/pengawas/civitas');
        }

        return view('pengawas.civitas.detail', [
            'pageTitle' => 'Rincian Civitas - ' . $member['nama_lengkap'],
            'member' => $member,
        ], 'pengawas');
    }

    /**
     * Unduh laporan data civitas dalam format CSV
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

        $this->civitasService->generateExportCsv($filters);
    }
}
