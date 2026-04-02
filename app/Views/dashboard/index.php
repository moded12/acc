<?php
// Path: /app/Views/dashboard/index.php
?>

<section class="erp-page-header">
    <div>
        <h1 class="erp-page-title">لوحة التحكم</h1>
        <p class="erp-page-subtitle">نظرة عامة على النظام والمستخدمين وجهات التعامل</p>
    </div>
</section>

<section class="row g-4">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="erp-card erp-stat-card">
            <div class="erp-card-body">
                <div class="erp-stat-label">المستخدمون</div>
                <div class="erp-stat-value"><?= (int) $usersCount ?></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
        <div class="erp-card erp-stat-card">
            <div class="erp-card-body">
                <div class="erp-stat-label">الأدوار</div>
                <div class="erp-stat-value"><?= (int) $rolesCount ?></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
        <div class="erp-card erp-stat-card">
            <div class="erp-card-body">
                <div class="erp-stat-label">العملاء</div>
                <div class="erp-stat-value"><?= (int) ($customersCount ?? 0) ?></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
        <div class="erp-card erp-stat-card">
            <div class="erp-card-body">
                <div class="erp-stat-label">الموردون</div>
                <div class="erp-stat-value"><?= (int) ($suppliersCount ?? 0) ?></div>
            </div>
        </div>
    </div>
</section>

<section class="erp-card">
    <div class="erp-card-body">
        <div class="erp-page-header mb-0">
            <div>
                <h3 class="mb-1 fw-bold">روابط سريعة</h3>
                <p class="erp-page-subtitle m-0">الوصول السريع إلى أهم أجزاء النظام</p>
            </div>

            <div class="erp-actions">
                <a href="<?= url('users') ?>" class="erp-btn erp-btn-outline">المستخدمون</a>
                <a href="<?= url('roles') ?>" class="erp-btn erp-btn-outline">الأدوار والصلاحيات</a>
                <a href="<?= url('customers') ?>" class="erp-btn erp-btn-primary">العملاء والموردون</a>
                <a href="<?= url('settings') ?>" class="erp-btn erp-btn-outline">الإعدادات</a>
            </div>
        </div>
    </div>
</section>