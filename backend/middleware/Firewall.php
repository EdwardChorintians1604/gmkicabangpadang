<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/middleware/Firewall.php
 * Deskripsi: Middleware Pelindung WAF & Anti-SQL Injection
 * Lapisan: Middleware Keamanan Siber
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class Firewall implements Middleware
{
    public function handle(Request $request, ?string $param = null): ?Response
    {
        // 1. Pindai Query Parameters (GET)
        $queryPayload = detect_sql_injection($_GET);
        if ($queryPayload !== null) {
            return $this->blockAttack('SQL Injection (GET Query)', $queryPayload);
        }

        // 2. Pindai Form Body Data (POST / PUT / DELETE)
        $postPayload = detect_sql_injection($_POST);
        if ($postPayload !== null) {
            return $this->blockAttack('SQL Injection (POST Body)', $postPayload);
        }

        // 3. Pindai REQUEST_URI
        $uri = rawurldecode($_SERVER['REQUEST_URI'] ?? '');
        $uriPayload = detect_sql_injection($uri);
        if ($uriPayload !== null) {
            return $this->blockAttack('SQL Injection (URI Path)', $uriPayload);
        }

        return null;
    }

    /**
     * Blokir permintaan dan catat ancaman ke tabel security_threats
     */
    protected function blockAttack(string $type, string $payload): Response
    {
        // Catat serangan ke database keamanan
        record_threat($type, $payload, 'critical');

        $response = new Response();
        $response->setStatusCode(400);

        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - Proteksi Anti-SQL Injection | GMKI Padang</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #1e293b; margin: 0; padding: 2rem; display: flex; align-items: center; justify-content: center; min-height: 80vh; }
        .shield-card { max-width: 540px; background: #ffffff; border: 2px solid #ef4444; border-radius: 12px; padding: 2.5rem; text-align: center; box-shadow: 0 10px 25px -5px rgba(239, 68, 68, 0.15); }
        .shield-icon { font-size: 3rem; margin-bottom: 1rem; }
        h1 { font-size: 1.5rem; color: #b91c1c; margin: 0 0 0.75rem; }
        p { font-size: 0.925rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem; }
        .incident-badge { background: #fee2e2; color: #991b1b; padding: 6px 12px; border-radius: 6px; font-family: monospace; font-size: 0.8rem; display: inline-block; margin-bottom: 1.5rem; }
        .btn-home { display: inline-block; background: #0f3d64; color: #ffffff; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.875rem; }
        .btn-home:hover { background: #1e5687; }
    </style>
</head>
<body>
    <div class="shield-card">
        <div class="shield-icon">🛡️</div>
        <h1>Permintaan Diblokir oleh Sistem Keamanan</h1>
        <p>Aktivitas mencurigakan yang teridentifikasi sebagai pola serangan <strong>Anti-SQL Injection</strong> telah dicegat dan digagalkan secara otomatis oleh Web Application Firewall (WAF) GMKI Cabang Padang.</p>
        <div class="incident-badge">Status: Serangan Tercegat & IP Terkunci di Audit Trail</div>
        <div>
            <a href="/" class="btn-home">&larr; Kembali ke Halaman Utama</a>
        </div>
    </div>
</body>
</html>';

        return $response->html($html, 400);
    }
}
