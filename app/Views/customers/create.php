<?php
// Path: /app/Views/customers/create.php

$errors = $errors ?? [];
$old = $old ?? [];
$defaultType = $defaultType ?? 'customer';
?>

<section class="erp-page-header">
    <div>
        <h1 class="erp-page-title">إضافة جهة تعامل</h1>
        <p class="erp-page-subtitle">إنشاء عميل أو مورد جديد بواجهة موحدة ومتجاوبة</p>
    </div>

    <div class="erp-actions">
        <a href="<?= url('customers') ?>" class="erp-btn erp-btn-outline">عودة</a>
    </div>
</section>

<section class="erp-card">
    <div class="erp-card-body">
        <form method="POST" action="<?= url('customers/store') ?>">
            <?= csrf_field() ?>

            <div class="erp-form-grid">
                <div class="erp-col-3">
                    <label class="erp-label">النوع</label>
                    <select name="type" class="erp-select">
                        <option value="customer" <?= (($old['type'] ?? $defaultType) === 'customer') ? 'selected' : '' ?>>عميل</option>
                        <option value="supplier" <?= (($old['type'] ?? $defaultType) === 'supplier') ? 'selected' : '' ?>>مورد</option>
                    </select>
                    <?php if (!empty($errors['type'])): ?>
                        <div class="erp-error"><?= e($errors['type'][0]) ?></div>
                    <?php endif; ?>
                </div>

                <div class="erp-col-6">
                    <label class="erp-label">الاسم</label>
                    <input type="text" name="name" class="erp-input" value="<?= e($old['name'] ?? '') ?>">
                    <?php if (!empty($errors['name'])): ?>
                        <div class="erp-error"><?= e($errors['name'][0]) ?></div>
                    <?php endif; ?>
                </div>

                <div class="erp-col-3">
                    <label class="erp-label">اسم الشركة</label>
                    <input type="text" name="company_name" class="erp-input" value="<?= e($old['company_name'] ?? '') ?>">
                </div>

                <div class="erp-col-4">
                    <label class="erp-label">الهاتف</label>
                    <input type="text" name="phone" class="erp-input" value="<?= e($old['phone'] ?? '') ?>">
                    <?php if (!empty($errors['phone'])): ?>
                        <div class="erp-error"><?= e($errors['phone'][0]) ?></div>
                    <?php endif; ?>
                </div>

                <div class="erp-col-4">
                    <label class="erp-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="erp-input" value="<?= e($old['email'] ?? '') ?>">
                </div>

                <div class="erp-col-4">
                    <label class="erp-label">الرقم الضريبي</label>
                    <input type="text" name="tax_number" class="erp-input" value="<?= e($old['tax_number'] ?? '') ?>">
                </div>

                <div class="erp-col-4">
                    <label class="erp-label">الرصيد الافتتاحي</label>
                    <input type="number" step="0.01" name="opening_balance" class="erp-input" value="<?= e($old['opening_balance'] ?? '0') ?>">
                </div>

                <div class="erp-col-4">
                    <label class="erp-label">نوع الرصيد</label>
                    <select name="balance_type" class="erp-select">
                        <option value="debit" <?= (($old['balance_type'] ?? 'debit') === 'debit') ? 'selected' : '' ?>>مدين</option>
                        <option value="credit" <?= (($old['balance_type'] ?? '') === 'credit') ? 'selected' : '' ?>>دائن</option>
                    </select>
                </div>

                <div class="erp-col-4">
                    <label class="erp-label">الحالة</label>
                    <select name="status" class="erp-select">
                        <option value="1" <?= (($old['status'] ?? '1') === '1') ? 'selected' : '' ?>>نشط</option>
                        <option value="0" <?= (($old['status'] ?? '') === '0') ? 'selected' : '' ?>>غير نشط</option>
                    </select>
                </div>

                <div class="erp-col-12">
                    <label class="erp-label">العنوان</label>
                    <textarea name="address" class="erp-textarea"><?= e($old['address'] ?? '') ?></textarea>
                </div>

                <div class="erp-col-12">
                    <label class="erp-label">ملاحظات</label>
                    <textarea name="notes" class="erp-textarea"><?= e($old['notes'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="erp-actions mt-4">
                <button type="submit" class="erp-btn erp-btn-primary">حفظ السجل</button>
                <a href="<?= url('customers') ?>" class="erp-btn erp-btn-outline">إلغاء</a>
            </div>
        </form>
    </div>
</section>