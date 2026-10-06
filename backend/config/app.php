<?php

if (!defined('GMKI_SECURE_ACCESS') && php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Akses langsung ditolak.');
}

return [
    'name' => $_ENV['APP_NAME'] ?? 'GMKI Cabang Padang',
    'env' => $_ENV['APP_ENV'] ?? 'development',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'url' => rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/'),
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'Asia/Jakarta',
    'locale' => $_ENV['APP_LOCALE'] ?? 'id',
    'key' => $_ENV['APP_KEY'] ?? 'secret_gmki_padang_key_fallback_2026',
];
