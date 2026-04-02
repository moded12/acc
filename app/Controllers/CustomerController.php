<?php
// Path: /app/Controllers/CustomerController.php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Authorization;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Logger;
use App\Core\Request;
use App\Core\Validator;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request): void
    {
        Authorization::require('customers.view');

        $type = (string) $request->input('type', '');
        $type = in_array($type, ['customer', 'supplier'], true) ? $type : null;

        $customerModel = new Customer();

        $this->view('customers/index', [
            'title' => 'العملاء والموردون',
            'records' => $customerModel->getAll($type),
            'type' => $type,
            'totals' => $customerModel->totals(),
        ], 'layouts/master');
    }

    public function create(Request $request): void
    {
        Authorization::require('customers.create');

        $type = (string) $request->input('type', 'customer');
        if (!in_array($type, ['customer', 'supplier'], true)) {
            $type = 'customer';
        }

        $this->view('customers/create', [
            'title' => 'إضافة جهة تعامل',
            'defaultType' => $type,
            'errors' => $_SESSION['_errors'] ?? [],
            'old' => $_SESSION['_old'] ?? [],
        ], 'layouts/master');

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function store(Request $request): void
    {
        Authorization::require('customers.create');

        if (!Csrf::validate($request->input('_token'))) {
            flash('error', 'انتهت صلاحية الجلسة.');
            redirect('customers/create');
        }

        $data = $request->only([
            'type',
            'name',
            'company_name',
            'phone',
            'email',
            'tax_number',
            'address',
            'opening_balance',
            'balance_type',
            'status',
            'notes',
        ]);

        $_SESSION['_old'] = $data;

        $errors = Validator::validate($data, [
            'type' => ['required'],
            'name' => ['required', 'min:3'],
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            redirect('customers/create?type=' . urlencode((string) ($data['type'] ?? 'customer')));
        }

        if (!in_array((string) $data['type'], ['customer', 'supplier'], true)) {
            $_SESSION['_errors'] = [
                'type' => ['نوع السجل غير صالح.']
            ];
            redirect('customers/create');
        }

        $customerModel = new Customer();

        if ($customerModel->existsByPhone((string) ($data['phone'] ?? ''))) {
            $_SESSION['_errors'] = [
                'phone' => ['رقم الهاتف مستخدم بالفعل.']
            ];
            redirect('customers/create?type=' . urlencode((string) $data['type']));
        }

        $customerModel->create($data);

        Logger::info('Customer/Supplier created', [
            'name' => $data['name'],
            'type' => $data['type']
        ]);

        flash('success', 'تم إنشاء السجل بنجاح.');
        redirect('customers?type=' . urlencode((string) $data['type']));
    }

    public function edit(Request $request, string $id): void
    {
        Authorization::require('customers.edit');

        $customerModel = new Customer();
        $record = $customerModel->findById((int) $id);

        if (!$record) {
            flash('error', 'السجل غير موجود.');
            redirect('customers');
        }

        $this->view('customers/edit', [
            'title' => 'تعديل جهة التعامل',
            'record' => $record,
            'errors' => $_SESSION['_errors'] ?? [],
            'old' => $_SESSION['_old'] ?? [],
        ], 'layouts/master');

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function update(Request $request, string $id): void
    {
        Authorization::require('customers.edit');

        if (!Csrf::validate($request->input('_token'))) {
            flash('error', 'انتهت صلاحية الجلسة.');
            redirect('customers');
        }

        $recordId = (int) $id;
        $customerModel = new Customer();
        $record = $customerModel->findById($recordId);

        if (!$record) {
            flash('error', 'السجل غير موجود.');
            redirect('customers');
        }

        $data = $request->only([
            'type',
            'name',
            'company_name',
            'phone',
            'email',
            'tax_number',
            'address',
            'opening_balance',
            'balance_type',
            'status',
            'notes',
        ]);

        $_SESSION['_old'] = $data;

        $errors = Validator::validate($data, [
            'type' => ['required'],
            'name' => ['required', 'min:3'],
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            redirect('customers/edit/' . $recordId);
        }

        if (!in_array((string) $data['type'], ['customer', 'supplier'], true)) {
            $_SESSION['_errors'] = [
                'type' => ['نوع السجل غير صالح.']
            ];
            redirect('customers/edit/' . $recordId);
        }

        if ($customerModel->existsByPhone((string) ($data['phone'] ?? ''), $recordId)) {
            $_SESSION['_errors'] = [
                'phone' => ['رقم الهاتف مستخدم بالفعل.']
            ];
            redirect('customers/edit/' . $recordId);
        }

        $customerModel->updateCustomer($recordId, $data);

        Logger::info('Customer/Supplier updated', [
            'id' => $recordId,
            'name' => $data['name'],
            'type' => $data['type']
        ]);

        flash('success', 'تم تحديث السجل بنجاح.');
        redirect('customers?type=' . urlencode((string) $data['type']));
    }

    public function delete(Request $request, string $id): void
    {
        Authorization::require('customers.delete');

        if (!Csrf::validate($request->input('_token'))) {
            flash('error', 'انتهت صلاحية الجلسة.');
            redirect('customers');
        }

        $recordId = (int) $id;
        $customerModel = new Customer();
        $record = $customerModel->findById($recordId);

        if (!$record) {
            flash('error', 'السجل غير موجود.');
            redirect('customers');
        }

        $customerModel->deleteCustomer($recordId);

        Logger::info('Customer/Supplier deleted', [
            'id' => $recordId,
            'name' => $record['name'] ?? '',
            'type' => $record['type'] ?? ''
        ]);

        flash('success', 'تم حذف السجل بنجاح.');
        redirect('customers?type=' . urlencode((string) ($record['type'] ?? 'customer')));
    }
}