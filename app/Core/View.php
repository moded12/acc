<?php
// Path: /app/Core/View.php

declare(strict_types=1);

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = APP_PATH . '/Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            $viewFile = BASE_PATH . '/' . ltrim($view, '/');
            if (!str_ends_with($viewFile, '.php')) {
                $viewFile .= '.php';
            }
        }

        if (!file_exists($viewFile)) {
            throw new \RuntimeException('View not found: ' . $view);
        }

        require APP_PATH . '/Views/layouts/master.php';
    }
}
