<?php

namespace App\Core;

class View
{
    protected static string $templateDir;

    public static function init(): void
    {
        self::$templateDir = dirname(__DIR__, 2) . '/frontend/templates';
    }

    public static function render(string $viewPath, array $data = [], ?string $layout = 'public'): Response
    {
        self::init();

        if (str_contains($viewPath, '..') || str_contains($viewPath, "\0")) {
            http_response_code(403);
            exit('Akses template tidak valid.');
        }

        $baseReal = realpath(self::$templateDir);
        $viewFile = self::$templateDir . '/' . str_replace('.', '/', $viewPath) . '.php';
        $viewReal = realpath($viewFile);

        if (!$viewReal || !$baseReal || !str_starts_with($viewReal, $baseReal) || !is_file($viewReal)) {
            http_response_code(500);
            echo "Template berkas tidak ditemukan: " . htmlspecialchars($viewPath, ENT_QUOTES, 'UTF-8');
            exit;
        }

        // Render template view dengan EXTR_SKIP untuk mencegah Variable Overwrite LFI
        $content = (static function (string $__viewFile, array $__data): string {
            extract($__data, EXTR_SKIP);
            ob_start();
            require $__viewFile;
            return (string) ob_get_clean();
        })($viewReal, $data);

        // Wrap with layout if specified
        if ($layout !== null) {
            $cleanLayout = preg_replace('/[^a-zA-Z0-9_\-]/', '', $layout);
            $layoutFile = self::$templateDir . '/layouts/' . $cleanLayout . '.php';
            $layoutReal = realpath($layoutFile);
            if ($layoutReal && str_starts_with($layoutReal, $baseReal) && is_file($layoutReal)) {
                $dataWithContent = $data;
                $dataWithContent['content'] = $content;
                $content = (static function (string $__layoutFile, array $__data, string $content): string {
                    extract($__data, EXTR_SKIP);
                    ob_start();
                    require $__layoutFile;
                    return (string) ob_get_clean();
                })($layoutReal, $data, $content);
            }
        }

        $response = new Response();
        return $response->html($content);
    }

    public static function partial(string $viewPath, array $data = []): string
    {
        self::init();
        if (str_contains($viewPath, '..') || str_contains($viewPath, "\0")) {
            return '';
        }
        $baseReal = realpath(self::$templateDir);
        $viewFile = self::$templateDir . '/' . str_replace('.', '/', $viewPath) . '.php';
        $viewReal = realpath($viewFile);

        if (!$viewReal || !$baseReal || !str_starts_with($viewReal, $baseReal) || !is_file($viewReal)) {
            return '';
        }

        return (static function (string $__viewFile, array $__data): string {
            extract($__data, EXTR_SKIP);
            ob_start();
            require $__viewFile;
            return (string) ob_get_clean();
        })($viewReal, $data);
    }
}
