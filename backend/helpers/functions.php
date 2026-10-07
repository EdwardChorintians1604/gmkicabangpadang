<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/helpers/functions.php
 * Deskripsi: Kumpulan fungsi pembantu (helper) global untuk sistem.
 * Meliputi perenderan tampilan, URL, manipulasi tanggal, ikon SVG, dan otorisasi.
 * =====================================================================
 */

declare(strict_types=1);

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\Csrf;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;

if (!function_exists('view')) {
    /**
     * Render template tampilan lengkap dengan layout
     */
    function view(string $template, array $data = [], ?string $layout = 'public'): Response
    {
        return View::render($template, $data, $layout);
    }
}

if (!function_exists('partial')) {
    /**
     * Render komponen parsial yang dipakai bersama (stat-card, tabel, pagination, chart-card)
     */
    function partial(string $template, array $data = []): string
    {
        return View::partial($template, $data);
    }
}

if (!function_exists('redirect')) {
    /**
     * Alihkan pengguna ke URL tujuan
     */
    function redirect(string $url, int $status = 302): void
    {
        $response = new Response();
        $response->redirect($url, $status);
    }
}

if (!function_exists('url')) {
    /**
     * Dapatkan URL penuh berdasarkan rute relatif
     */
    function url(string $path = ''): string
    {
        $base = rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/');
        $trimmed = ltrim($path, '/');
        return $trimmed ? "{$base}/{$trimmed}" : $base;
    }
}

if (!function_exists('asset')) {
    /**
     * Dapatkan path aset publik (CSS, JS, gambar, vendor)
     */
    function asset(string $path): string
    {
        return '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('upload_url')) {
    /**
     * Dapatkan path berkas unggahan publik
     */
    function upload_url(string $path): string
    {
        return '/uploads/' . ltrim($path, '/');
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Ambil token CSRF aktif sesi
     */
    function csrf_token(): string
    {
        return Csrf::token();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Buat input hidden CSRF token untuk formulir HTML
     */
    function csrf_field(): string
    {
        return Csrf::field();
    }
}

if (!function_exists('old')) {
    /**
     * Ambil data input lama dari sesi flash saat validasi gagal
     */
    function old(string $key, mixed $default = null): mixed
    {
        return Session::old($key, $default);
    }
}

if (!function_exists('session')) {
    /**
     * Akses data sesi saat ini
     */
    function session(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_SESSION ?? [];
        }
        return Session::get($key, $default);
    }
}

if (!function_exists('flash')) {
    /**
     * Setel atau ambil pesan flash notifikasi
     */
    function flash(string $key, mixed $value = null): mixed
    {
        if ($value === null) {
            return Session::getFlash($key);
        }
        Session::flash($key, $value);
        return null;
    }
}

if (!function_exists('auth')) {
    /**
     * Ambil data akun pengguna yang sedang login
     */
    function auth(): ?array
    {
        return Auth::user();
    }
}

if (!function_exists('is_admin')) {
    /**
     * Periksa apakah pengguna adalah Administrator
     */
    function is_admin(): bool
    {
        $user = Auth::user();
        return $user && ($user['role'] ?? '') === 'admin';
    }
}

if (!function_exists('is_pengawas')) {
    /**
     * Periksa apakah pengguna adalah Pengawas / BPC Leader
     */
    function is_pengawas(): bool
    {
        $user = Auth::user();
        return $user && in_array($user['role'] ?? '', ['admin', 'ketcab', 'pengawas'], true);
    }
}

if (!function_exists('is_operator')) {
    /**
     * Periksa apakah pengguna adalah Operator Cabang
     */
    function is_operator(): bool
    {
        $user = Auth::user();
        return $user && ($user['role'] ?? '') === 'operator';
    }
}

if (!function_exists('can')) {
    /**
     * Cek hak akses perizinan (Permission)
     */
    function can(string $permission): bool
    {
        return Authorization::can($permission);
    }
}

if (!function_exists('has_role')) {
    /**
     * Cek apakah pengguna memiliki salah satu role yang ditentukan
     */
    function has_role(string|array $roles): bool
    {
        return Authorization::role($roles);
    }
}

if (!function_exists('e')) {
    /**
     * Sanitasi output HTML untuk mencegah serangan XSS
     */
    function e(?string $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('active_nav')) {
    /**
     * Helper untuk menentukan kelas aktif pada menu navigasi
     */
    function active_nav(string $path, string $activeClass = 'active'): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        if ($path === '/' && $uri === '/') {
            return $activeClass;
        }
        if ($path !== '/' && (str_starts_with($uri, $path))) {
            return $activeClass;
        }
        return '';
    }
}

if (!function_exists('slugify')) {
    /**
     * Ubah teks string menjadi format slug ramah URL
     */
    function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', (string)$text);
        $text = preg_replace('~[^-\w]+~', '', (string)$text);
        $text = trim((string)$text, '-');
        $text = preg_replace('~-+~', '-', (string)$text);
        $text = strtolower((string)$text);
        return empty($text) ? 'n-a' : $text;
    }
}

if (!function_exists('format_date')) {
    /**
     * Format tanggal Indonesia
     */
    function format_date(?string $datetime, string $format = 'd M Y'): string
    {
        if (!$datetime) {
            return '-';
        }
        $timestamp = strtotime($datetime);
        if (!$timestamp) {
            return $datetime;
        }

        $bulanIndo = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $hari = date('d', $timestamp);
        $bulan = (int)date('m', $timestamp);
        $tahun = date('Y', $timestamp);

        if ($format === 'd F Y') {
            return "{$hari} " . ($bulanIndo[$bulan] ?? date('M', $timestamp)) . " {$tahun}";
        }

        if ($format === 'd M Y H:i') {
            $jam = date('H:i', $timestamp);
            return "{$hari} " . ($bulanIndo[$bulan] ?? date('M', $timestamp)) . " {$tahun} {$jam}";
        }

        return date($format, $timestamp);
    }
}

if (!function_exists('svg_icon')) {
    /**
     * Helper pembuat ikon SVG inline (Lucide/Feather Style) yang cepat, ringan, dan mandiri tanpa library eksternal
     */
    function svg_icon(string $name, int $size = 18, string $class = ''): string
    {
        $icons = [
            'dashboard' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
            'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            'user' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'news' => '<path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>',
            'organization' => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>',
            'chart' => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
            'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
            'lock' => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
            'database' => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>',
            'backup' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>',
            'plus' => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
            'edit' => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',
            'trash' => '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
            'eye' => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
            'search' => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
            'download' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>',
            'upload' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>',
            'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>',
            'check' => '<polyline points="20 6 9 17 4 12"/>',
            'alert' => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
            'external' => '<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>',
            'eye-off' => '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>',
            'file' => '<path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/>',
            'filter' => '<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>',
            'refresh' => '<polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>',
            'menu' => '<line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>',
            'sidebar' => '<rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/>'
        ];

        $svgBody = $icons[$name] ?? '<circle cx="12" cy="12" r="10"/>';
        $classAttr = $class ? " class=\"{$class}\"" : '';

        return "<svg width=\"{$size}\" height=\"{$size}\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"{$classAttr}>{$svgBody}</svg>";
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die untuk debugging
     */
    function dd(...$vars): void
    {
        echo '<pre style="background:#1e1e1e; color:#ff79c6; padding:15px; border-radius:8px; font-family:monospace; z-index:99999; position:relative;">';
        foreach ($vars as $v) {
            var_dump($v);
        }
        echo '</pre>';
        exit;
    }
}
