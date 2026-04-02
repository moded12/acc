<?php
// Path: /app/Routes/web.php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\CustomerController;
use App\Controllers\DashboardController;
use App\Controllers\PermissionController;
use App\Controllers\RoleController;
use App\Controllers\SettingsController;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

return [
    ['GET', '/', [DashboardController::class, 'index'], [AuthMiddleware::class]],

    ['GET', '/login', [AuthController::class, 'showLogin'], [GuestMiddleware::class]],
    ['POST', '/login', [AuthController::class, 'login'], [GuestMiddleware::class]],
    ['POST', '/logout', [AuthController::class, 'logout'], [AuthMiddleware::class]],

    ['GET', '/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]],

    ['GET', '/users', [UserController::class, 'index'], [AuthMiddleware::class]],
    ['GET', '/users/create', [UserController::class, 'create'], [AuthMiddleware::class]],
    ['POST', '/users/store', [UserController::class, 'store'], [AuthMiddleware::class]],
    ['GET', '/users/edit/{id}', [UserController::class, 'edit'], [AuthMiddleware::class]],
    ['POST', '/users/update/{id}', [UserController::class, 'update'], [AuthMiddleware::class]],
    ['POST', '/users/delete/{id}', [UserController::class, 'delete'], [AuthMiddleware::class]],

    ['GET', '/roles', [RoleController::class, 'index'], [AuthMiddleware::class]],
    ['POST', '/roles/store', [RoleController::class, 'store'], [AuthMiddleware::class]],
    ['POST', '/roles/permissions/{id}', [RoleController::class, 'permissions'], [AuthMiddleware::class]],

    ['POST', '/permissions/store', [PermissionController::class, 'store'], [AuthMiddleware::class]],

    ['GET', '/customers', [CustomerController::class, 'index'], [AuthMiddleware::class]],
    ['GET', '/customers/create', [CustomerController::class, 'create'], [AuthMiddleware::class]],
    ['POST', '/customers/store', [CustomerController::class, 'store'], [AuthMiddleware::class]],
    ['GET', '/customers/edit/{id}', [CustomerController::class, 'edit'], [AuthMiddleware::class]],
    ['POST', '/customers/update/{id}', [CustomerController::class, 'update'], [AuthMiddleware::class]],
    ['POST', '/customers/delete/{id}', [CustomerController::class, 'delete'], [AuthMiddleware::class]],

    ['GET', '/settings', [SettingsController::class, 'index'], [AuthMiddleware::class]],
    ['POST', '/settings/update', [SettingsController::class, 'update'], [AuthMiddleware::class]],
];