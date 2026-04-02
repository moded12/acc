<?php
// Path: /app/Controllers/PermissionController.php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Authorization;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Logger;
use App\Core\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function store(Request $request): void
    {
        Authorization::require('roles.edit');

        if (!Csrf::validate($request->input('_token'))) {
            flash('error', 'انتهت صلاحية الجلسة.');
            redirect('roles');
        }

        $name = trim((string) $request->input('name'));
        $label = trim((string) $request->input('label'));

        if ($name === '' || $label === '') {
            flash('error', 'الاسم البرمجي والوصف مطلوبان.');
            redirect('roles');
        }

        $permissionModel = new Permission();

        if ($permissionModel->existsByName($name)) {
            flash('error', 'الصلاحية موجودة مسبقًا.');
            redirect('roles');
        }

        $permissionModel->create([
            'name' => $name,
            'label' => $label
        ]);

        Logger::info('Permission created', ['name' => $name]);

        flash('success', 'تم إنشاء الصلاحية بنجاح.');
        redirect('roles');
    }
}