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
        Session::flash('success', 'Ekspor laporan masuk antrean dengan nomor #' . $jobId . '.');
        redirect('/ruang-kerja/antrian');
        return new Response();
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
