<?php
// Path: /app/Core/Router.php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $uri, array $action, array $middlewares = []): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'uri' => $this->normalize($uri),
            'action' => $action,
            'middlewares' => $middlewares,
        ];
    }

    public function dispatch(Request $request): void
    {
        $requestMethod = strtoupper($request->method());
        $requestUri = $this->normalize($request->uri());

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '([^/]+)', $route['uri']);
            $pattern = '#^' . $pattern . '$#';

            if (!preg_match($pattern, $requestUri, $matches)) {
                continue;
            }

            array_shift($matches);

            foreach ($route['middlewares'] as $middlewareClass) {
                $middleware = new $middlewareClass();
                $middleware->handle($request);
            }

            [$controllerClass, $controllerMethod] = $route['action'];

            $controller = new $controllerClass();
            $params = array_merge([$request], $matches);

            call_user_func_array([$controller, $controllerMethod], $params);
            return;
        }

        http_response_code(404);

        $controller = new \App\Controllers\ErrorController();
        $controller->notFound($request);
    }

    private function normalize(string $uri): string
    {
        $uri = '/' . ltrim($uri, '/');
        $uri = rtrim($uri, '/');

        return $uri === '' ? '/' : $uri;
    }
}