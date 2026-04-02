<?php
// Path: /app/Views/layouts/master.php

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$currentPath = is_string($currentPath) ? $currentPath : '';

function erp_is_active(string $needle): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $path = is_string($path) ? $path : '';

    return str_contains($path, $needle) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'ERP') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="<?= url('public/assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>
<div class="erp-app">
    <header class="erp-topbar">
        <div class="container-fluid">
            <div class="erp-topbar-inner">
                <a class="erp-brand" href="<?= url('dashboard') ?>">ERP</a>

                <?php if (\App\Core\Auth::check()): ?>
                    <nav class="erp-nav">
                        <a href="<?= url('dashboard') ?>" class="erp-nav-link <?= e(erp_is_active('/dashboard')) ?>">لوحة التحكم</a>
                        <a href="<?= url('users') ?>" class="erp-nav-link <?= e(erp_is_active('/users')) ?>">المستخدمون</a>
                        <a href="<?= url('roles') ?>" class="erp-nav-link <?= e(erp_is_active('/roles')) ?>">الأدوار</a>
                        <a href="<?= url('customers') ?>" class="erp-nav-link <?= e(erp_is_active('/customers')) ?>">العملاء والموردون</a>
                        <a href="<?= url('settings') ?>" class="erp-nav-link <?= e(erp_is_active('/settings')) ?>">الإعدادات</a>
                    </nav>

                    <div class="erp-userbar">
                        <div class="erp-user-pill"><?= e(\App\Core\Auth::user()['name'] ?? '') ?></div>

                        <form method="POST" action="<?= url('logout') ?>" class="m-0">
                            <?= csrf_field() ?>
                            <button class="erp-logout-btn" type="submit">خروج</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="erp-main">
        <div class="container">
            <div class="erp-page">
                <?php if ($message = flash('success')): ?>
                    <div class="erp-alert erp-alert-success"><?= e($message) ?></div>
                <?php endif; ?>

                <?php if ($message = flash('error')): ?>
                    <div class="erp-alert erp-alert-danger"><?= e($message) ?></div>
                <?php endif; ?>

                <?php
                if (isset($viewPath) && is_string($viewPath) && file_exists($viewPath)) {
                    require $viewPath;
                } else {
                    echo '<div class="erp-alert erp-alert-danger">حدث خطأ غير متوقع.</div>';
                }
                ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>