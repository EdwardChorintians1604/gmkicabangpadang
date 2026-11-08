<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Admin/MapercaController.php
 * Deskripsi: Pengelolaan Kader Baru (Maperca / Calon Anggota),
 *            terpisah dari data anggota (civitas) resmi.
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\SettingService;

class MapercaController extends CivitasController
{
    protected string $basePath = '/admin/maperca';

    protected string $kelompok = 'maperca';

    /**
     * Buka atau Tutup izin pendaftaran Maperca (Calon Anggota Baru) di portal website
     */
    public function toggleIzin(Request $request): Response
    {
        Authorization::authorize('civitas.update');

        $status = (int) $request->post('status', 1);
        $message = trim((string) $request->post('message', ''));

        $settingService = new SettingService();
        $isOpen = $status === 1;
        $settingService->setMapercaRegistrationOpen($isOpen, $message ?: null);

        $statusText = $isOpen ? 'DIBUKA' : 'DITUTUP';

        audit_log('MAPERCA_REGISTRATION_TOGGLE', 'settings', 'maperca_registration_open', [
            'status' => $statusText,
            'message' => $message,
            'actor' => Auth::user()['username'] ?? 'admin',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        ]);

        Session::flash('success', "Puji Tuhan! Izin pendaftaran Calon Anggota Baru (Maperca) di website publik berhasil {$statusText}.");
        redirect($this->basePath);
    }
}
