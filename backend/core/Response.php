<?php

namespace App\Core;

class Response
{
    protected int $statusCode = 200;
    protected array $headers = [];
    protected ?string $content = null;

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function json(mixed $data, int $status = 200): self
    {
        $this->statusCode = $status;
        $this->header('Content-Type', 'application/json; charset=utf-8');
        $this->content = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $this;
    }

    public function html(string $html, int $status = 200): self
    {
        $this->statusCode = $status;
        $this->header('Content-Type', 'text/html; charset=utf-8');
        $this->content = $html;
        return $this;
    }

    public function redirect(string $url, int $status = 302): void
    {
        http_response_code($status);
        header("Location: {$url}");
        exit;
    }

    public function file(string $filePath, ?string $filename = null, string $disposition = 'inline'): void
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            $this->setStatusCode(404)->html('Berkas tidak ditemukan.')->send();
            exit;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath) ?: 'application/octet-stream';
        finfo_close($finfo);

        $downloadName = $filename ?: basename($filePath);
        
        while (ob_get_level()) {
            ob_end_clean();
        }

        header("Content-Type: {$mimeType}");
        header("Content-Disposition: {$disposition}; filename=\"{$downloadName}\"");
        header("Content-Length: " . filesize($filePath));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        readfile($filePath);
        exit;
    }

    public function send(): void
    {
        if (!headers_sent()) {
            http_response_code($this->statusCode);
            foreach ($this->headers as $name => $value) {
                header("{$name}: {$value}");
            }
        }

        if ($this->content !== null) {
            echo $this->content;
        }
    }
}
