<?php
// Path: /app/Helpers/helpers.php

declare(strict_types=1);

if (!function_exists('config')) {
    function config(string $file): array
    {
        $path = APP_PATH . '/Config/' . $file . '.php';

        if (!file_exists($path)) {
            return [];
        }

        $config = require $path;
        return is_array($config) ? $config : [];
    }
}

if (!function_exists('base_url')) {
    function base_url(): string
    {
        $config = config('app');
        return rtrim((string) ($config['base_url'] ?? ''), '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        return base_url() . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path = ''): void
    {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('flash')) {
    function flash(string $key, ?string $message = null): ?string
    {
        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;
            return null;
        }

        if (!isset($_SESSION['_flash'][$key])) {
            return null;
        }

        $value = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);

        return $value;
    }
}

if (!function_exists('view')) {
    function view(string $view, array $data = [], string $layout = 'layouts/master'): void
    {
        extract($data, EXTR_SKIP);

        $viewPath = APP_PATH . '/Views/' . $view . '.php';
        $layoutPath = APP_PATH . '/Views/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            throw new RuntimeException('View not found: ' . $viewPath);
        }

        if (!file_exists($layoutPath)) {
            throw new RuntimeException('Layout not found: ' . $layoutPath);
        }

        require $layoutPath;
    }
}