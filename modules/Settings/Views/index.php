<?php // Path: /modules/Settings/Views/index.php ?>
<h1 class="mb-4">إعدادات النظام</h1>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?= e(base_url('settings')) ?>">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">اسم الشركة</label>
                    <input type="text" name="company_name" class="form-control" value="<?= e($settings['company_name'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">العملة</label>
                    <input type="text" name="currency" class="form-control" value="<?= e($settings['currency'] ?? 'USD') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Prefix الفواتير</label>
                    <input type="text" name="invoice_prefix" class="form-control" value="<?= e($settings['invoice_prefix'] ?? 'INV') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">الضريبة الافتراضية</label>
                    <input type="number" step="0.01" name="default_tax" class="form-control" value="<?= e($settings['default_tax'] ?? '16') ?>">
                </div>
                <div class="col-12">
                    <button class="btn btn-success">حفظ</button>
                </div>
            </div>
        </form>
    </div>
</div>
