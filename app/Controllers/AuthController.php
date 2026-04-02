<?php
// Path: /app/Controllers/AuthController.php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Logger;
use App\Core\Request;
use App\Core\Validator;

class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        if (Auth::check()) {
            redirect('');
        }

        $this->view('auth/login', [
            'title' => 'تسجيل الدخول',
            'errors' => $_SESSION['_errors'] ?? [],
        ], 'layouts/master');

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function login(Request $request): void
    {
        if (!Csrf::validate($request->input('_token'))) {
            $_SESSION['_errors'] = [
                'general' => ['انتهت صلاحية الجلسة. حاول مرة أخرى.']
            ];
            redirect('login');
        }

        $data = $request->only(['email', 'password']);
        $_SESSION['_old'] = [
            'email' => $data['email'] ?? ''
        ];

        $errors = Validator::validate($data, [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            redirect('login');
        }

        if (!Auth::attempt((string) $data['email'], (string) $data['password'])) {
            $_SESSION['_errors'] = [
                'general' => ['بيانات الدخول غير صحيحة.']
            ];

            Logger::info('Failed login attempt', [
                'email' => $data['email'] ?? ''
            ]);

            redirect('login');
        }

        Logger::info('User logged in', [
            'email' => $data['email'] ?? ''
        ]);

        flash('success', 'تم تسجيل الدخول بنجاح.');
        redirect('dashboard');
    }

    public function logout(Request $request): void
    {
        if (!Csrf::validate($request->input('_token'))) {
            redirect('dashboard');
        }

        Auth::logout();
        flash('success', 'تم تسجيل الخروج بنجاح.');
        redirect('login');
    }
}