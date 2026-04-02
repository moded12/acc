<?php
// Path: /app/Controllers/DashboardController.php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Authorization;
use App\Core\Controller;
use App\Core\Request;
use App\Models\Customer;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        Authorization::require('dashboard.view');

        $userModel = new User();
        $roleModel = new Role();
        $permissionModel = new Permission();
        $customerModel = new Customer();

        $customerTotals = $customerModel->totals();

        $this->view('dashboard/index', [
            'title' => 'لوحة التحكم',
            'usersCount' => count($userModel->getAllWithRoles()),
            'rolesCount' => count($roleModel->getAll()),
            'permissionsCount' => count($permissionModel->getAll()),
            'customersCount' => (int) ($customerTotals['customers_count'] ?? 0),
            'suppliersCount' => (int) ($customerTotals['suppliers_count'] ?? 0),
        ], 'layouts/master');
    }
}