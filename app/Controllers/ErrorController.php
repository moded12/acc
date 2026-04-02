<?php
// Path: /app/Controllers/ErrorController.php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class ErrorController extends Controller
{
    public function notFound(Request $request): void
    {
        $this->view('errors/404', [
            'title' => 'الصفحة غير موجودة',
            'message' => 'الصفحة التي طلبتها غير موجودة.'
        ], 'layouts/master');
    }
}