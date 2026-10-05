<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Pengawas/OrganizationController.php
 * Deskripsi: Controller Pemantauan Profil & Struktur Pengurus BPC untuk Pengawas
 * Lapisan: Controller (Pengawas) - Mode Baca Saja
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Pengawas;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Services\OrganizationService;

class OrganizationController
{
    protected OrganizationService $orgService;

    public function __construct()
    {
        $this->orgService = new OrganizationService();
    }

    /**
     * Tampilkan profil dan amanat organisasi cabang
     */
    public function profil(Request $request): Response
    {
        Authorization::authorize('organization.view');

        $profile = $this->orgService->getProfile();

        return view('pengawas.organisasi.profil', [
            'pageTitle' => 'Pemantauan Profil Cabang - GMKI Cabang Padang',
            'profile' => $profile,
        ], 'pengawas');
    }

    /**
     * Tampilkan struktur kepengurusan aktif BPC
     */
    public function struktur(Request $request): Response
    {
        Authorization::authorize('organization.view');

        $structure = $this->orgService->getStructure(false);

        return view('pengawas.organisasi.struktur', [
            'pageTitle' => 'Pemantauan Struktur BPC - GMKI Cabang Padang',
            'structure' => $structure,
        ], 'pengawas');
    }
}
