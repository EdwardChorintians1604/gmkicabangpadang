<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;

class QueueWorker
{
    public function workNext(): bool
    {
        Database::beginTransaction();
        try {
            $job = Database::fetchOne(
                "SELECT * FROM background_jobs
                 WHERE (status = 'queued' AND available_at <= NOW())
                    OR (status = 'processing' AND reserved_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE))
                 ORDER BY id ASC LIMIT 1 FOR UPDATE"
            );
            if (!$job) {
                Database::commit();
                return false;
            }

            Database::execute(
                "UPDATE background_jobs SET status = 'processing', attempts = attempts + 1, reserved_at = NOW(), error_message = NULL WHERE id = ?",
                [(int)$job['id']]
            );
            Database::commit();
        } catch (Throwable $exception) {
            Database::rollBack();
            throw $exception;
        }

        try {
            if ($job['job_type'] !== 'finance_export') {
                throw new \RuntimeException('Jenis pekerjaan antrean tidak dikenal.');
            }
            $payload = json_decode($job['payload'], true, 512, JSON_THROW_ON_ERROR);
            $outputName = $this->createFinanceExport((int)$job['owner_id'], (string)($payload['scope'] ?? ''));
            Database::execute(
                "UPDATE background_jobs SET status = 'completed', output_name = ?, finished_at = NOW() WHERE id = ? AND status = 'processing'",
                [$outputName, (int)$job['id']]
            );
        } catch (Throwable $exception) {
            error_log('Background job ' . (int)$job['id'] . ' failed: ' . $exception->getMessage());
            Database::execute(
                "UPDATE background_jobs SET status = 'failed', error_message = ?, finished_at = NOW() WHERE id = ? AND status = 'processing'",
                ['Pekerjaan gagal diproses. Coba kembali atau hubungi administrator.', (int)$job['id']]
            );
        }

        return true;
    }

    private function createFinanceExport(int $ownerId, string $scope): string
    {
        if (!in_array($scope, ['ketcab', 'bencab', 'admin'], true)) {
            throw new \RuntimeException('Cakupan ekspor tidak valid.');
        }

        $sql = "SELECT d.title, d.period, d.amount, d.description, d.original_name, d.created_at, u.nama_lengkap
                FROM office_documents d JOIN users u ON u.id = d.created_by
                WHERE d.document_type = 'finance'";
        $params = [];
        if ($scope === 'bencab') {
            $sql .= ' AND d.created_by = ?';
            $params[] = $ownerId;
        }
        $sql .= ' ORDER BY d.created_at DESC, d.id DESC';

        $directory = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'exports';
        if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
            throw new \RuntimeException('Folder ekspor privat tidak dapat dibuat.');
        }
        $outputName = 'finance-' . bin2hex(random_bytes(16)) . '.csv';
        $path = $directory . DIRECTORY_SEPARATOR . $outputName;
        $stream = fopen($path, 'xb');
        if ($stream === false) {
            throw new \RuntimeException('Berkas ekspor tidak dapat dibuat.');
        }

        try {
            $this->writeCsvRow($stream, ['Judul', 'Periode', 'Jumlah (Rp)', 'Keterangan', 'Berkas', 'Diunggah oleh', 'Tanggal']);
            $connection = Database::getConnection();
            $bufferingAttribute = \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY;
            $wasBuffered = $connection->getAttribute($bufferingAttribute);
            $connection->setAttribute($bufferingAttribute, false);
            $documents = null;
            try {
                $documents = Database::query($sql, $params);
                while ($document = $documents->fetch()) {
                    $this->writeCsvRow($stream, [
                        $this->spreadsheetCell((string)$document['title']),
                        $this->spreadsheetCell((string)$document['period']),
                        (string)$document['amount'],
                        $this->spreadsheetCell((string)($document['description'] ?? '')),
                        $this->spreadsheetCell((string)$document['original_name']),
                        $this->spreadsheetCell((string)$document['nama_lengkap']),
                        (string)$document['created_at'],
                    ]);
                }
            } finally {
                if ($documents) {
                    $documents->closeCursor();
                }
                $connection->setAttribute($bufferingAttribute, $wasBuffered);
            }
            if (!fclose($stream)) {
                throw new \RuntimeException('Berkas ekspor tidak dapat ditutup dengan benar.');
            }
        } catch (Throwable $exception) {
            if (is_resource($stream)) {
                fclose($stream);
            }
            if (is_file($path) && !unlink($path)) {
                error_log('Gagal membersihkan hasil ekspor parsial: ' . basename($path));
            }
            throw $exception;
        }

        return $outputName;
    }

    private function writeCsvRow(mixed $stream, array $row): void
    {
        if (fputcsv($stream, $row, ',', '"', '') === false) {
            throw new \RuntimeException('Baris laporan gagal ditulis ke berkas ekspor.');
        }
    }

    private function spreadsheetCell(string $value): string
    {
        return preg_match('/^[\x00-\x20]*[=+\-@]/', $value) ? "'" . $value : $value;
    }
}
