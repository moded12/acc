<?php
// Path: /app/Controllers/RoleController.php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Authorization;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Request;
use App\Models\Permission;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request): void
    {
        Authorization::require('roles.view');

        $roleModel = new Role();
        $permissionModel = new Permission();

        $roles = $roleModel->getAll();

        foreach ($roles as &$role) {
            $role['permission_ids'] = $roleModel->getPermissionIds((int) $role['id']);
            $role['permissions'] = $roleModel->getPermissions((int) $role['id']);
        }

        $this->view('roles/index', [
            'title' => 'الأدوار والصلاحيات',
            'roles' => $roles,
            'permissions' => $permissionModel->getAll(),
            'errors' => $_SESSION['_errors'] ?? [],
            'old' => $_SESSION['_old'] ?? [],
        ], 'layouts/master');

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function store(Request $request): void
    {
        Authorization::require('roles.create');

        if (!Csrf::validate($request->input('_token'))) {
            flash('error', 'انتهت صلاحية الجلسة.');
            redirect('roles');
        }

        $name = trim((string) $request->input('name'));
        $permissionIds = (array) ($request->input('permissions') ?? []);

        $_SESSION['_old'] = [
            'name' => $name,
            'permissions' => $permissionIds
        ];

        if ($name === '') {
            $_SESSION['_errors'] = [
                'name' => ['اسم الدور مطلوب.']
            ];
            redirect('roles');
        }

        $roleModel = new Role();
        $roleModel->create(['name' => $name]);

        $newRoleId = (int) Database::connection()->lastInsertId();
        $roleModel->syncPermissions($newRoleId, $permissionIds);

        Logger::info('Role created', ['name' => $name]);

        flash('success', 'تم إنشاء الدور بنجاح.');
        redirect('roles');
    }

    public function permissions(Request $request, string $id): void
    {
        Authorization::require('roles.edit');

        if (!Csrf::validate($request->input('_token'))) {
            flash('error', 'انتهت صلاحية الجلسة.');
            redirect('roles');
        }

        $roleId = (int) $id;
        $permissionIds = (array) ($request->input('permissions') ?? []);

        $roleModel = new Role();
        $role = $roleModel->findById($roleId);

        if (!$role) {
            flash('error', 'الدور غير موجود.');
            redirect('roles');
        }

        $roleModel->syncPermissions($roleId, $permissionIds);

        Logger::info('Role permissions updated', ['role_id' => $roleId]);

        flash('success', 'تم تحديث صلاحيات الدور.');
        redirect('roles');
    }
}