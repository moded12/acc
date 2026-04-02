<?php
// Path: /app/Views/users/create.php

$errors = $errors ?? [];
$old = $old ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">إضافة مستخدم</h1>
        <p class="text-muted mb-0">إنشاء مستخدم جديد مع دور وصلاحيات مرتبطة</p>
    </div>
    <a href="<?= url('users') ?>" class="btn btn-outline-secondary">عودة</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="POST" action="<?= url('users/store') ?>">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الاسم</label>
                    <input type="text" name="name" class="form-control" value="<?= e($old['name'] ?? '') ?>">
                    <?php if (!empty($errors['name'])): ?>
                        <div class="text-danger small mt-1"><?= e($errors['name'][0]) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="<?= e($old['email'] ?? '') ?>">
                    <?php if (!empty($errors['email'])): ?>
                        <div class="text-danger small mt-1"><?= e($errors['email'][0]) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control">
                    <?php if (!empty($errors['password'])): ?>
                        <div class="text-danger small mt-1"><?= e($errors['password'][0]) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-3">
                    <label class="form-label">الدور</label>
                    <select name="role_id" class="form-select">
                        <option value="">اختر الدور</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= (int) $role['id'] ?>" <?= ((string) ($old['role_id'] ?? '') === (string) $role['id']) ? 'selected' : '' ?>>
                                <?= e($role['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['role_id'])): ?>
                        <div class="text-danger small mt-1"><?= e($errors['role_id'][0]) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="1" <?= ((string) ($old['status'] ?? '1') === '1') ? 'selected' : '' ?>>نشط</option>
                        <option value="0" <?= ((string) ($old['status'] ?? '') === '0') ? 'selected' : '' ?>>غير نشط</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary">حفظ المستخدم</button>
            </div>
        </form>
    </div>
</div>