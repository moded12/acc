<?php
// Path: /modules/Settings/Controllers/SettingController.php

declare(strict_types=1);

namespace Modules\Settings\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use Modules\Settings\Models\Setting;

class SettingController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('modules/Settings/Views/index', [
            'title' => 'إعدادات النظام',
            'settings' => (new Setting())->allKeyed(),
        ]);
    }

    public function update(Request $request): void
    {
        Csrf::verify($request->input('_token'));
        (new Setting())->saveMany([
            'company_name' => (string) $request->input('company_name'),
            'currency' => (string) $request->input('currency'),
            'invoice_prefix' => (string) $request->input('invoice_prefix'),
            'default_tax' => (string) $request->input('default_tax'),
        ]);

        flash('success', 'تم تحديث الإعدادات بنجاح');
        redirect('settings');
    }
}
