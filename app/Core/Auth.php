<?php
// Path: /app/Core/Auth.php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function user(): ?array
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }

        return (new User())->find((int) $_SESSION['user_id']);
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = (new User())->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        $_SESSION['user_id'] = (int) $user['id'];
        session_regenerate_id(true);
        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }

    public static function can(string $permissionKey): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }

        return (new User())->hasPermission((int) $user['id'], $permissionKey);
    }
}
