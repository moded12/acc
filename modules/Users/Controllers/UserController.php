<?php
// Path: /modules/Users/Controllers/UserController.php

declare(strict_types=1);

namespace Modules\Users\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Validator;
use App\Models\User;
use Modules\Users\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('modules/Users/Views/index', [
            'title' => 'إدارة المستخدمين',
            'users' => (new User())->all(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->view('modules/Users/Views/create', [
            'title' => 'إضافة مستخدم',
            'roles' => (new Role())->all(),
        ]);
    }

    public function store(Request $request): void
    {
        Csrf::verify($request->input('_token'));
        $data = $request->all();
        $errors = Validator::required($data, ['name', 'email', 'password', 'role_id']);

        if (!empty($errors)) {
            flash('error', 'يرجى تعبئة جميع الحقول المطلوبة');
            redirect('users/create');
        }

        (new User())->create($data);
        flash('success', 'تم إضافة المستخدم بنجاح');
        redirect('users');
    }
}
