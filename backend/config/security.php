<?php

return [
    'session_lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 7200), // 2 hours
    'csrf_lifetime' => (int)($_ENV['CSRF_LIFETIME'] ?? 3600),       // 1 hour
    'rate_limit' => [
        'login_max_attempts' => (int)($_ENV['RATE_LIMIT_LOGIN_MAX'] ?? 5),
        'login_decay_seconds' => (int)($_ENV['RATE_LIMIT_LOGIN_DECAY'] ?? 900), // 15 mins lock
    ],
    'password_min_length' => 8,
    'allowed_image_mimes' => explode(',', $_ENV['ALLOWED_IMAGE_TYPES'] ?? 'image/jpeg,image/png,image/webp'),
    'allowed_doc_mimes' => explode(',', $_ENV['ALLOWED_DOC_TYPES'] ?? 'application/pdf,text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
    'max_upload_size' => ((int)($_ENV['MAX_UPLOAD_SIZE_MB'] ?? 5)) * 1024 * 1024, // in bytes
];
