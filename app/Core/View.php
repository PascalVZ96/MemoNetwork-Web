<?php

namespace MemoNetwork\Core;

final class View
{
    public static function render(string $view, array $data = [], string $layout = 'layouts/app'): void
    {
        extract($data, EXTR_SKIP);

        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';
        $layoutPath = dirname(__DIR__) . '/Views/' . $layout . '.php';

        if (!is_file($viewPath)) {
            http_response_code(500);
            exit('View not found: ' . htmlspecialchars($view));
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if (is_file($layoutPath)) {
            require $layoutPath;
            return;
        }

        echo $content;
    }
}
