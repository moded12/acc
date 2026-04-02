<?php
// Path: /app/Controllers/UserController.php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Logger;
use App\Core\Request;
use App\Core\Validator;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request): void
    {
        Authorization::require('users.view');

        $userModel = new User();

        $this->view('users/index', [
            'title' => 'المستخدمون',
            'users' => $userModel->getAllWithRoles()
        ], 'layouts/master');
    }

    public function create(Request $request): void
    {
        Authorization::require('users.create');

        $roleModel = new Role();

        $this->view('users/create', [
            'title' => 'إضافة مستخدم',
            'roles' => $roleModel->getAll(),
            'errors' => $_SESSION['_errors'] ?? [],
            'old' => $_SESSION['_old'] ?? [],
        ], 'layouts/master');

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function store(Request $request): void
    {
        Authorization::require('users.create');

        if (!Csrf::validate($request->input('_token'))) {
            flash('error', 'انتهت صلاحية الجلسة.');
            redirect('users/create');
        }

        $data = $request->only(['name', 'email', 'password', 'role_id', 'status']);
        $_SESSION['_old'] = $data;

        $errors = Validator::validate($data, [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
            'role_id' => ['required'],
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            redirect('users/create');
        }

        $userModel = new User();

        if ($userModel->findByEmail((string) $data['email'])) {
            $_SESSION['_errors'] = [
                'email' => ['البريد الإلكتروني مستخدم بالفعل.']
            ];
            redirect('users/create');
        }

        $userModel->create($data);

        Logger::info('User created', [
            'email' => $data['email']
        ]);

        flash('success', 'تم إنشاء المستخدم بنجاح.');
        redirect('users');
    }

    public function edit(Request $request, string $id): void
    {
        Authorization::require('users.edit');

        $userModel = new User();
        $roleModel = new Role();

        $user = $userModel->findWithRole((int) $id);

        if (!$user) {
            flash('error', 'المستخدم غير موجود.');
            redirect('users');
        }

        $this->view('users/edit', [
            'title' => 'تعديل المستخدم',
            'user' => $user,
            'roles' => $roleModel->getAll(),
            'errors' => $_SESSION['_errors'] ?? [],
            'old' => $_SESSION['_old'] ?? [],
        ], 'layouts/master');

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function update(Request $request, string $id): void
    {
        Authorization::require('users.edit');

        if (!Csrf::validate($request->input('_token'))) {
            flash('error', 'انتهت صلاحية الجلسة.');
            redirect('users');
        }

        $userId = (int) $id;
        $userModel = new User();
        $existingUser = $userModel->findWithRole($userId);

        if (!$existingUser) {
            flash('error', 'المستخدم غير موجود.');
            redirect('users');
        }

        $data = $request->only(['name', 'email', 'password', 'role_id', 'status']);
        $_SESSION['_old'] = $data;

        $errors = Validator::validate($data, [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'role_id' => ['required'],
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            redirect('users/edit/' . $userId);
        }

        $emailOwner = $userModel->findByEmail((string) $data['email']);
        if ($emailOwner && (int) $emailOwner['id'] !== $userId) {
            $_SESSION['_errors'] = [
                'email' => ['البريد الإلكتروني مستخدم بالفعل.']
            ];
            redirect('users/edit/' . $userId);
        }

        $userModel->updateUser($userId, $data);

        Logger::info('User updated', [
            'user_id' => $userId,
            'email' => $data['email']
        ]);

        flash('success', 'تم تحديث المستخدم بنجاح.');
        redirect('users');
    }

    public function delete(Request $request, string $id): void
    {
        Authorization::require('users.delete');

        if (!Csrf::validate($request->input('_token'))) {
            flash('error', 'انتهت صلاحية الجلسة.');
            redirect('users');
        }

        $userId = (int) $id;
        $currentUser = Auth::user();

        if ((int) ($currentUser['id'] ?? 0) === $userId) {
            flash('error', 'لا يمكنك حذف المستخدم الحالي.');
            redirect('users');
        }

        $userModel = new User();
        $user = $userModel->findWithRole($userId);

        if (!$user) {
            flash('error', 'المستخدم غير موجود.');
            redirect('users');
        }

        $userModel->deleteUser($userId);

        Logger::info('User deleted', [
            'user_id' => $userId,
            'email' => $user['email'] ?? ''
        ]);

        flash('success', 'تم حذف المستخدم بنجاح.');
        redirect('users');
    }
}