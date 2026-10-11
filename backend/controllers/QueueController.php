<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\Database;
use App\Core\Queue;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class QueueController
{
    public function index(Request $request): Response
    {
        Authorization::authorize('reports.download');
        $jobs = Database::fetchAll(
            "SELECT id, job_type, status, error_message, created_at, finished_at
             FROM background_jobs WHERE owner_id = ? AND job_type = 'finance_export'
             ORDER BY id DESC LIMIT 50",
            [Auth::id()]
        );

        return view('workspaces.queue', [
            'pageTitle' => 'Antrean Ekspor - GMKI Cabang Padang',
            'jobs' => $jobs,
            'user' => Auth::user(),
        ], 'dashboard');
    }

    public function enqueueFinanceExport(Request $request): Response
    {
        Authorization::authorize('reports.download');
        $jobId = Queue::dispatchFinanceExport((int)Auth::id(), (string)Auth::role());

        // Di shared hosting / InfinityFree tanpa CLI background daemon,
        // jalankan antrean secara sinkron (sync) jika dikonfigurasi
        $queueMode = $_ENV['QUEUE_CONNECTION'] ?? 'sync';
        if ($queueMode === 'sync') {
            try {
                $worker = new \App\Core\QueueWorker();
                $worker->workNext();
                Session::flash('success', 'Ekspor laporan keuangan berhasil diproses langsung dan siap diunduh.');
            } catch (\Throwable $e) {
                error_log('Eksekusi antrean sinkron gagal: ' . $e->getMessage());
                Session::flash('success', 'Ekspor laporan masuk antrean dengan nomor #' . $jobId . '.');
            }
        } else {
            Session::flash('success', 'Ekspor laporan masuk antrean dengan nomor #' . $jobId . '.');
        }

        redirect('/ruang-kerja/antrian');
        return new Response();
    }

    /**
     * Web-Cron Endpoint: Memproses antrean via pemicu eksternal (Cron-job.org / UptimeRobot)
     * Menggunakan proteksi kunci rahasia token di parameter URL.
     */
    public function cronWorker(Request $request): Response
    {
        $cronToken = trim((string)($_ENV['CRON_SECRET_TOKEN'] ?? 'gmki_secret_cron_key'));
        $inputToken = trim((string)$request->query('token', ''));

        if (empty($inputToken) || !hash_equals($cronToken, $inputToken)) {
            http_response_code(403);
            return (new Response())->json(['status' => 'error', 'message' => 'Token cron tidak valid.'], 403);
        }

        $processedCount = 0;
        $maxJobs = 5;
        $worker = new \App\Core\QueueWorker();

        while ($processedCount < $maxJobs) {
            $worked = $worker->workNext();
            if (!$worked) {
                break;
            }
            $processedCount++;
        }

        return (new Response())->json([
            'status' => 'ok',
            'processed_jobs' => $processedCount,
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
    }

    public function download(Request $request, string $id): Response
    {
        Authorization::authorize('reports.download');
        $job = Database::fetchOne(
            "SELECT output_name FROM background_jobs
             WHERE id = ? AND owner_id = ? AND job_type = 'finance_export' AND status = 'completed'",
            [(int)$id, Auth::id()]
        );
        if (!$job || !$job['output_name']) {
            http_response_code(404);
            return (new Response())->html('Hasil ekspor tidak ditemukan atau belum selesai.', 404);
        }

        $filename = basename($job['output_name']);
        $path = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR
            . 'private' . DIRECTORY_SEPARATOR . 'exports' . DIRECTORY_SEPARATOR . $filename;
        if (!is_file($path)) {
            error_log('Hasil ekspor antrean hilang: ' . $filename);
            http_response_code(404);
            return (new Response())->html('Hasil ekspor tidak tersedia di penyimpanan.', 404);
        }

        (new Response())->file($path, 'rekap-laporan-keuangan-' . (int)$id . '.csv', 'attachment');
        return new Response();
    }
}
