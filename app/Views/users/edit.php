<?php
// Path: /app/Views/users/edit.php

$errors = $errors ?? [];
$old = $old ?? [];
?>

<div class="mb-4">
    <h1 class="fw-bold">تعديل المستخدم</h1>
</div>

<div class="card p-4">
    <form method="POST" action="<?= url('users/update/' . $user['id']) ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label>الاسم</label>
            <input name="name" class="form-control"
                value="<?= e($old['name'] ?? $user['name']) ?>">
        </div>

        <div class="mb-3">
            <label>البريد</label>
            <input name="email" class="form-control"
                value="<?= e($old['email'] ?? $user['email']) ?>">
        </div>

        <div class="mb-3">
            <label>كلمة المرور (اختياري)</label>
            <input name="password" type="password" class="form-control">
        </div>

        <div class="mb-3">
            <label>الدور</label>
            <select name="role_id" class="form-select">
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['id'] ?>"
                        <?= ($role['id'] == ($old['role_id'] ?? $user['role_id'])) ? 'selected' : '' ?>>
                        <?= e($role['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>الحالة</label>
            <select name="status" class="form-select">
                <option value="1" <?= ($user['status'] == 1) ? 'selected' : '' ?>>نشط</option>
                <option value="0" <?= ($user['status'] == 0) ? 'selected' : '' ?>>غير نشط</option>
            </select>
        </div>

        <button class="btn btn-primary">حفظ</button>
    </form>
</div>