<?php
// Path: /app/Core/App.php

declare(strict_types=1);

namespace App\Core;

class App
{
    public function run(): void
    {
        $appConfig = require APP_PATH . '/Config/app.php';

        if (isset($appConfig['timezone']) && is_string($appConfig['timezone']) && $appConfig['timezone'] !== '') {
            date_default_timezone_set($appConfig['timezone']);
        } else {
            date_default_timezone_set('Asia/Dubai');
        }

        $router = new Router();
        $routes = require APP_PATH . '/Routes/web.php';

        foreach ($routes as $route) {
            $method = $route[0] ?? 'GET';
            $uri = $route[1] ?? '/';
            $action = $route[2] ?? null;
            $middlewares = $route[3] ?? [];

            $router->add($method, $uri, $action, $middlewares);
        }

        $request = new Request();
        $router->dispatch($request);
    }
}