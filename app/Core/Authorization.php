<?php
// Path: /app/Core/Authorization.php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Authorization
{
    public static function check(string $permission): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();

        if (!$user || empty($user['id'])) {
            return false;
        }

        if ((int) ($user['role_id'] ?? 0) === 1) {
            return true;
        }

        $userModel = new User();
        return $userModel->hasPermission((int) $user['id'], $permission);
    }

    public static function require(string $permission): void
    {
        if (!self::check($permission)) {
            http_response_code(403);

            view('errors/403', [
                'title' => 'غير مصرح',
                'message' => 'ليس لديك صلاحية للوصول إلى هذه الصفحة.'
            ]);

            exit;
        }
    }
}