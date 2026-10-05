<?php

$baseDir = dirname(__DIR__, 2);

return [
    'public_uploads' => [
        'base' => $baseDir . '/frontend/public/uploads',
        'berita' => $baseDir . '/frontend/public/uploads/berita',
        'lampiran' => $baseDir . '/frontend/public/uploads/lampiran',
        'organisasi' => $baseDir . '/frontend/public/uploads/organisasi',
    ],
    'private_storage' => [
        'base' => $baseDir . '/storage/private',
        'foto_anggota' => $baseDir . '/storage/private/foto-anggota',
        'kta' => $baseDir . '/storage/private/kta',
    ],
    'backup' => [
        'database' => $baseDir . '/storage/backup/database',
        'uploads' => $baseDir . '/storage/backup/uploads',
        'retain_count' => (int)($_ENV['BACKUP_RETAIN_COUNT'] ?? 3),
    ],
    'logs' => $baseDir . '/storage/logs',
    'temp' => $baseDir . '/storage/temp',
];
