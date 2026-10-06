<?php

namespace App\Core;

class Request
{
    protected string $method;
    protected string $uri;
    protected string $path;
    protected array $query;
    protected array $body;
    protected array $files;
    protected array $server;
    protected array $headers;

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        
        // Handle method override from form (_method=PUT/DELETE)
        if ($this->method === 'POST' && isset($_POST['_method'])) {
            $this->method = strtoupper($_POST['_method']);
        }

        $this->uri = $_SERVER['REQUEST_URI'] ?? '/';
        $pathOnly = parse_url($this->uri, PHP_URL_PATH) ?? '/';

        // Normalize base path if project running inside subfolder (e.g. Apache subdirectory)
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        if (php_sapi_name() !== 'cli-server' && basename($scriptName) === 'index.php') {
            $scriptDir = dirname($scriptName);
            if ($scriptDir !== '/' && $scriptDir !== '.' && $scriptDir !== '\\' && strpos($pathOnly, $scriptDir) === 0) {
                $pathOnly = substr($pathOnly, strlen($scriptDir));
            }
        }

        $this->path = '/' . trim($pathOnly, '/');
        if ($this->path === '') {
            $this->path = '/';
        }

        $this->query = $_GET;
        $this->body = $_POST;
        $this->files = $_FILES;
        $this->headers = function_exists('getallheaders') ? getallheaders() : [];

        // Parse JSON body if Content-Type is application/json
        $contentType = $this->server['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $rawInput = file_get_contents('php://input');
            $json = json_decode($rawInput, true);
            if (is_array($json)) {
                $this->body = array_merge($this->body, $json);
            }
        }
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function isMethod(string $method): bool
    {
        return strtoupper($method) === $this->method;
    }

    public function input(?string $key = null, mixed $default = null): mixed
    {
        $data = array_merge($this->query, $this->body);
        if ($key === null) {
            return $data;
        }
        return $data[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function query(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->query;
        }
        return $this->query[$key] ?? $default;
    }

    public function post(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->body;
        }
        return $this->body[$key] ?? $default;
    }

    public function file(string $key): ?array
    {
        if (!isset($this->files[$key]) || empty($this->files[$key]['name'])) {
            return null;
        }
        return $this->files[$key];
    }

    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && !empty($this->files[$key]['tmp_name']) && is_uploaded_file($this->files[$key]['tmp_name']);
    }

    public function has(string $key): bool
    {
        $all = $this->all();
        return isset($all[$key]) && $all[$key] !== '';
    }

    public function ip(): string
    {
        if (!empty($this->server['HTTP_CLIENT_IP'])) {
            return $this->server['HTTP_CLIENT_IP'];
        }
        if (!empty($this->server['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $this->server['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        return $this->server['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    public function userAgent(): string
    {
        return $this->server['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    public function isAjax(): bool
    {
        return (!empty($this->server['HTTP_X_REQUESTED_WITH']) &&
            strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }
}
