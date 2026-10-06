<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Admin/OrganizationController.php
 * Deskripsi: Controller Profil & Struktur Organisasi BPC untuk Administrator
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
use App\Services\OrganizationService;

class OrganizationController
{
    protected OrganizationService $orgService;

    public function __construct()
    {
        $this->orgService = new OrganizationService();
    }

    /**
     * Tampilkan formulir kelola profil organisasi
     */
    public function profil(Request $request): Response
    {
        Authorization::authorize('organization.view');

        $profile = $this->orgService->getProfile();

        return view('admin.organisasi.profil', [
            'pageTitle' => 'Kelola Profil Cabang - GMKI Cabang Padang',
            'profile' => $profile,
        ], 'admin');
    }

    /**
     * Simpan pembaruan profil organisasi
     */
    public function updateProfil(Request $request): Response
    {
        Authorization::authorize('organization.update');

        $data = [
            'nama_organisasi' => $request->post('nama_organisasi'),
            'slogan' => $request->post('slogan'),
            'tema_periode' => $request->post('tema_periode'),
            'sub_tema' => $request->post('sub_tema'),
            'sejarah' => $request->post('sejarah'),
            'visi' => $request->post('visi'),
            'misi' => $request->post('misi'),
            'tri_panji' => $request->post('tri_panji'),
            'panca_kegiatan' => $request->post('panca_kegiatan'),
            'alamat_sekretariat' => $request->post('alamat_sekretariat'),
            'telepon' => $request->post('telepon'),
            'email' => $request->post('email'),
            'instagram' => $request->post('instagram'),
            'youtube' => $request->post('youtube'),
            'facebook' => $request->post('facebook'),
        ];

        $this->orgService->updateProfile($data);

        Session::flash('success', 'Profil organisasi berhasil diperbarui.');
        redirect('/admin/organisasi/profil');
    }

    /**
     * Tampilkan daftar dan formulir struktur kepengurusan BPC
     */
    public function struktur(Request $request): Response
    {
        Authorization::authorize('organization.view');

        $structure = $this->orgService->getStructure(false);

        return view('admin.organisasi.struktur', [
            'pageTitle' => 'Kelola Struktur BPC - GMKI Cabang Padang',
            'structure' => $structure,
        ], 'admin');
    }

    /**
     * Simpan atau perbarui data pengurus BPC
     */
    public function simpanStruktur(Request $request): Response
    {
        Authorization::authorize('organization.update');

        $id = $request->post('id');
        $nama = $request->post('nama');
        $jabatan = $request->post('jabatan');
        $bidang = $request->post('bidang');
        $periode = $request->post('periode');
        $urutan = (int)$request->post('urutan', 0);
        $telepon = $request->post('telepon');
        $statusAktif = (int)$request->post('status_aktif', 1);

        $validator = Validator::make([
            'nama' => $nama,
            'jabatan' => $jabatan,
        ], [
            'nama' => 'required',
            'jabatan' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            redirect('/admin/organisasi/struktur');
        }

        $payload = [
            'nama' => $nama,
            'jabatan' => $jabatan,
            'bidang' => $bidang ?: 'BPC (Badan Pengurus Cabang)',
            'periode' => $periode ?: '2024-2026',
            'urutan' => $urutan,
            'telepon' => $telepon,
            'status_aktif' => $statusAktif,
        ];

        $fotoFile = $request->file('foto');

        if (!empty($id)) {
            $this->orgService->updateStructure((int)$id, $payload, $fotoFile);
            Session::flash('success', 'Data pengurus berhasil diperbarui.');
        } else {
            $this->orgService->createStructure($payload, $fotoFile);
            Session::flash('success', 'Pengurus baru berhasil ditambahkan.');
        }

        redirect('/admin/organisasi/struktur');
    }

    /**
     * Hapus pengurus dari struktur organisasi
     */
    public function hapusStruktur(Request $request, string $id): Response
    {
        Authorization::authorize('organization.update');

        $this->orgService->deleteStructure((int)$id);
        Session::flash('success', 'Data pengurus berhasil dihapus.');
        redirect('/admin/organisasi/struktur');
    }
}
