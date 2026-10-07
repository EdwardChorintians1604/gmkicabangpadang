<?php

declare(strict_types=1);

$rootDir = dirname(__DIR__);
$envFile = $rootDir . DIRECTORY_SEPARATOR . '.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = trim(trim($value), '"\'');
            putenv($key . '=' . $_ENV[$key]);
        }
    }
}

date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Asia/Jakarta');
require_once $rootDir . DIRECTORY_SEPARATOR . 'backend' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Autoloader.php';
\App\Core\Autoloader::register();

$once = in_array('--once', $argv, true);
$worker = new \App\Core\QueueWorker();
fwrite(STDOUT, "Worker antrean GMKI berjalan.\n");

do {
    try {
        $processed = $worker->workNext();
    } catch (Throwable $exception) {
        error_log('Queue worker error: ' . $exception->getMessage());
        fwrite(STDERR, 'Worker dihentikan karena terjadi kesalahan sistem.' . PHP_EOL);
        exit(1);
    }

    if ($once) {
        break;
    }
    if (!$processed) {
        sleep(2);
    }
} while (true);
