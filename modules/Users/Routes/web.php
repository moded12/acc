<?php
// Path: /modules/Users/Routes/web.php

use App\Middleware\AuthMiddleware;
use Modules\Users\Controllers\UserController;

return [
    ['GET', '/users', [UserController::class, 'index'], [AuthMiddleware::class]],
    ['GET', '/users/create', [UserController::class, 'create'], [AuthMiddleware::class]],
    ['POST', '/users', [UserController::class, 'store'], [AuthMiddleware::class]],
];
