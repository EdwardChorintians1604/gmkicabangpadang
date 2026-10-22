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
        $uri = rawurldecode($_SERVER['REQUEST_URI'] ?? '');

        // 1. Perisai Anti-PHP2Shell / Anti-RCE (Remote Code Execution)
        $rceGet = detect_rce_payload($_GET);
        if ($rceGet !== null) {
            return $this->blockAttack('PHP2Shell / RCE Attempt (GET)', $rceGet);
        }

        $rcePost = detect_rce_payload($_POST);
        if ($rcePost !== null) {
            return $this->blockAttack('PHP2Shell / RCE Attempt (POST)', $rcePost);
        }

        $rceUri = detect_rce_payload($uri);
        if ($rceUri !== null) {
            return $this->blockAttack('PHP2Shell / Web Shell Probe (URI)', $rceUri);
        }

        // 2. Perisai Anti-LFI & Directory Traversal
        $lfiGet = detect_lfi_traversal($_GET);
        if ($lfiGet !== null) {
            return $this->blockAttack('Directory Traversal / LFI (GET)', $lfiGet);
        }

        $lfiUri = detect_lfi_traversal($uri);
        if ($lfiUri !== null) {
            return $this->blockAttack('Directory Traversal / LFI (URI)', $lfiUri);
        }

        // 3. Perisai Anti-SQL Injection
        $queryPayload = detect_sql_injection($_GET);
        if ($queryPayload !== null) {
            return $this->blockAttack('SQL Injection (GET Query)', $queryPayload);
        }

        $postPayload = detect_sql_injection($_POST);
        if ($postPayload !== null) {
            return $this->blockAttack('SQL Injection (POST Body)', $postPayload);
        }

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
        $response->setStatusCode(403);

        $safeType = htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - Perisai Anti-RCE & WAF | GMKI Padang</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 2rem; display: flex; align-items: center; justify-content: center; min-height: 80vh; }
        .shield-card { max-width: 560px; background: #1e293b; border: 2px solid #ef4444; border-radius: 16px; padding: 2.5rem; text-align: center; box-shadow: 0 20px 40px -10px rgba(239, 68, 68, 0.3); }
        .shield-icon { font-size: 3.5rem; margin-bottom: 1rem; }
        h1 { font-size: 1.5rem; color: #f87171; margin: 0 0 0.75rem; font-weight: 800; }
        p { font-size: 0.925rem; color: #94a3b8; line-height: 1.6; margin-bottom: 1.5rem; }
        .incident-badge { background: #450a0a; color: #fca5a5; border: 1px solid #7f1d1d; padding: 8px 14px; border-radius: 8px; font-family: monospace; font-size: 0.825rem; display: inline-block; margin-bottom: 1.5rem; }
        .btn-home { display: inline-block; background: #2563eb; color: #ffffff; padding: 10px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.875rem; transition: background 0.2s; }
        .btn-home:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <div class="shield-card">
        <div class="shield-icon">🛡️</div>
        <h1>Akses Ditolak oleh Perisai Siber WAF</h1>
        <p>Aktivitas mencurigakan yang teridentifikasi sebagai upaya <strong>' . $safeType . '</strong> telah dicegat dan digagalkan secara otomatis oleh Web Application Firewall (WAF) GMKI Cabang Padang.</p>
        <div class="incident-badge">Status: Serangan Digagalkan & Alamat IP Terkunci di Audit Trail</div>
        <div>
            <a href="/" class="btn-home">&larr; Kembali ke Halaman Utama</a>
        </div>
    </div>
</body>
</html>';

        return $response->html($html, 403);
    }
}
