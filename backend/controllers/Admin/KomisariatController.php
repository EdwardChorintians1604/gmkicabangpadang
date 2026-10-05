<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Admin/KomisariatController.php
 * Deskripsi: CRUD data komisariat beserta daftar anggotanya.
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Repositories\KomisariatRepository;

class KomisariatController
{
    protected KomisariatRepository $repo;

    public function __construct()
    {
        $this->repo = new KomisariatRepository();
    }

    protected function layout(): string
    {
        return Authorization::role('pengawas') ? 'pengawas' : 'admin';
    }

    protected function clean(Request $request): array
    {
        $p = $request->post();
        return [
            'nama' => trim((string)($p['nama'] ?? '')),
            'perguruan_tinggi' => trim((string)($p['perguruan_tinggi'] ?? '')),
            'keterangan' => trim((string)($p['keterangan'] ?? '')),
        ];
    }

    /** @return string|null pesan error */
    protected function validateData(array $d, ?int $exceptId = null): ?string
    {
        if (mb_strlen($d['nama']) < 3) {
            return 'Nama komisariat minimal 3 karakter.';
        }
        if (mb_strlen($d['nama']) > 150) {
            return 'Nama komisariat maksimal 150 karakter.';
        }
        if ($this->repo->findByName($d['nama'], $exceptId)) {
            return 'Nama komisariat sudah terdaftar.';
        }
        return null;
    }

    public function index(Request $request): Response
    {
        Authorization::authorize('civitas.view');

        return view('admin.komisariat.index', [
            'pageTitle' => 'Kelola Komisariat - GMKI Cabang Padang',
            'komisariat' => $this->repo->allWithCounts(),
        ], $this->layout());
    }

    public function create(Request $request): Response
    {
        Authorization::authorize('civitas.create');

        return view('admin.komisariat.form', [
            'pageTitle' => 'Tambah Komisariat',
            'item' => null,
            'isEdit' => false,
        ], $this->layout());
    }

    public function store(Request $request): Response
    {
        Authorization::authorize('civitas.create');

        $data = $this->clean($request);
        if ($err = $this->validateData($data)) {
            Session::flash('error', $err);
            Session::setOld($request->post());
            redirect('/admin/komisariat/create');
        }

        $this->repo->create($data);
        Session::flash('success', 'Komisariat berhasil ditambahkan.');
        redirect('/admin/komisariat');
    }

    public function detail(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.view');

        $item = $this->repo->findById((int)$id);
        if (!$item) {
            Session::flash('error', 'Komisariat tidak ditemukan.');
            redirect('/admin/komisariat');
        }

        return view('admin.komisariat.detail', [
            'pageTitle' => 'Komisariat ' . $item['nama'],
            'item' => $item,
            'members' => $this->repo->members($item['nama']),
        ], $this->layout());
    }

    public function edit(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.update');

        $item = $this->repo->findById((int)$id);
        if (!$item) {
            Session::flash('error', 'Komisariat tidak ditemukan.');
            redirect('/admin/komisariat');
        }

        return view('admin.komisariat.form', [
            'pageTitle' => 'Edit Komisariat',
            'item' => $item,
            'isEdit' => true,
        ], $this->layout());
    }

    public function update(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.update');

        $item = $this->repo->findById((int)$id);
        if (!$item) {
            Session::flash('error', 'Komisariat tidak ditemukan.');
            redirect('/admin/komisariat');
        }

        $data = $this->clean($request);
        if ($err = $this->validateData($data, (int)$id)) {
            Session::flash('error', $err);
            redirect("/admin/komisariat/{$id}/edit");
        }

        $this->repo->update((int)$id, $item['nama'], $data);
        Session::flash('success', 'Komisariat berhasil diperbarui.');
        redirect("/admin/komisariat/{$id}");
    }

    public function delete(Request $request, string $id): Response
    {
        Authorization::authorize('civitas.delete');

        $item = $this->repo->findById((int)$id);
        if (!$item) {
            Session::flash('error', 'Komisariat tidak ditemukan.');
            redirect('/admin/komisariat');
        }

        if ($this->repo->countMembers($item['nama']) > 0) {
            Session::flash('error', 'Komisariat masih memiliki anggota. Pindahkan anggotanya terlebih dahulu.');
            redirect('/admin/komisariat');
        }

        $this->repo->delete((int)$id);
        Session::flash('success', 'Komisariat berhasil dihapus.');
        redirect('/admin/komisariat');
    }
}
