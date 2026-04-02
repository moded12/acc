<?php
// Path: /modules/Settings/Routes/web.php

use App\Middleware\AuthMiddleware;
use Modules\Settings\Controllers\SettingController;

return [
    ['GET', '/settings', [SettingController::class, 'index'], [AuthMiddleware::class]],
    ['POST', '/settings', [SettingController::class, 'update'], [AuthMiddleware::class]],
];
