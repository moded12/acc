<?php
// Path: /app/Middleware/AuthMiddleware.php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;

class AuthMiddleware
{
    public function handle(Request $request): void
    {
        if (!Auth::check()) {
            flash('error', 'يرجى تسجيل الدخول أولاً');
            redirect('login');
        }
    }
}
