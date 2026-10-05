<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - PINTU MASUK UTAMA (FRONT CONTROLLER)
 * Frontend Public Index
 * =====================================================================
 */

declare(strict_types=1);

// Jika dijalankan lewat PHP Built-in Server, sajikan berkas statis secara langsung
if (php_sapi_name() === 'cli-server') {
    $filePath = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($filePath)) {
        return false;
    }
}

// Tetapkan batas error reporting berdasarkan konfigurasi
error_reporting(E_ALL);

$rootDir = dirname(__DIR__, 2);

// 1. Muat Berkas Lingkungan (.env)
$envFile = $rootDir . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) {
            continue;
        }
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Lepaskan tanda kutip
            $value = trim($value, '"\'');
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("{$key}={$value}");
            }
        }
    }
}

// 2. Setel Timezone
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Asia/Jakarta');

// 3. Muat Autoloader Mandiri (PSR-4)
require_once $rootDir . '/backend/core/Autoloader.php';
\App\Core\Autoloader::register();

// Jika vendor composer tersedia, muat juga
if (file_exists($rootDir . '/vendor/autoload.php')) {
    require_once $rootDir . '/vendor/autoload.php';
}

// 4. Inisialisasi Sesi Aman
\App\Core\Session::start();

// 5. Tangani Permintaan (Request) & Perutean (Router)
try {
    $request = new \App\Core\Request();
    $router = new \App\Core\Router();

    // Muat seluruh definisi rute modular
    require_once $rootDir . '/backend/routes/public.php';
    require_once $rootDir . '/backend/routes/auth.php';
    require_once $rootDir . '/backend/routes/admin.php';
    require_once $rootDir . '/backend/routes/pengawas.php';

    // Eksekusi rute dan kirim respons
    $response = $router->dispatch($request);
    $response->send();
} catch (\Throwable $e) {
    error_log("Critical System Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());

    $debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($debug) {
        http_response_code(500);
        echo '<div style="background:#fee2e2; border:2px solid #ef4444; color:#991b1b; padding:20px; font-family:sans-serif; margin:20px; border-radius:8px;">';
        echo '<h2 style="margin-top:0;">Terjadi Kesalahan Sistem (Debug Mode)</h2>';
        echo '<p><strong>Pesan:</strong> ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p><strong>Berkas:</strong> ' . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . ' : ' . $e->getLine() . '</p>';
        echo '<pre style="background:#fff; padding:15px; border-radius:6px; overflow:auto; font-size:13px;">' . htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8') . '</pre>';
        echo '</div>';
    } else {
        http_response_code(500);
        require_once $rootDir . '/frontend/templates/errors/500.php';
    }
}
