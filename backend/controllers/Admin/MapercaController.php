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

class MapercaController extends CivitasController
{
    protected string $basePath = '/admin/maperca';

    protected string $kelompok = 'maperca';
}
