<?php
// Path: /app/Core/Controller.php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'layouts/master'): void
    {
        extract($data, EXTR_SKIP);

        $viewPath = APP_PATH . '/Views/' . $view . '.php';
        $layoutPath = APP_PATH . '/Views/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException('View not found: ' . $viewPath);
        }

        if (!file_exists($layoutPath)) {
            throw new \RuntimeException('Layout not found: ' . $layoutPath);
        }

        require $layoutPath;
    }
}