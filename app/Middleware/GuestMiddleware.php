<?php
// Path: /app/Middleware/GuestMiddleware.php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;

class GuestMiddleware
{
    public function handle(Request $request): void
    {
        if (Auth::check()) {
            redirect('dashboard');
        }
    }
}
