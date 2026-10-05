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

        $viewFile = self::$templateDir . '/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "Template berkas tidak ditemukan: {$viewPath}";
            exit;
        }

        // Render template view
        extract($data);
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Wrap with layout if specified
        if ($layout !== null) {
            $layoutFile = self::$templateDir . '/layouts/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                ob_start();
                require $layoutFile;
                $content = ob_get_clean();
            }
        }

        $response = new Response();
        return $response->html($content);
    }

    public static function partial(string $viewPath, array $data = []): string
    {
        self::init();
        $viewFile = self::$templateDir . '/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            return '';
        }

        extract($data);
        ob_start();
        require $viewFile;
        return ob_get_clean();
    }
}
