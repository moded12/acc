<?php
// Path: /app/Views/roles/index.php

$errors = $errors ?? [];
$old = $old ?? [];
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <h4 class="fw-bold mb-3">إضافة دور</h4>

                <form method="POST" action="<?= url('roles/store') ?>">
                    <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

                    <div class="mb-3">
                        <label class="form-label">اسم الدور</label>
                        <input type="text" name="name" class="form-control" value="<?= e($old['name'] ?? '') ?>">
                        <?php if (!empty($errors['name'])): ?>
                            <div class="text-danger small mt-1"><?= e($errors['name'][0]) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الصلاحيات</label>
                        <div class="border rounded-3 p-3" style="max-height: 260px; overflow:auto;">
                            <?php foreach ($permissions as $permission): ?>
                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="permissions[]"
                                        value="<?= (int) $permission['id'] ?>"
                                        id="perm-create-<?= (int) $permission['id'] ?>"
                                        <?= in_array((int) $permission['id'], array_map('intval', $old['permissions'] ?? []), true) ? 'checked' : '' ?>
                                    >
                                    <label class="form-check-label" for="perm-create-<?= (int) $permission['id'] ?>">
                                        <?= e($permission['label']) ?>
                                        <span class="text-muted small d-block"><?= e($permission['name']) ?></span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100">حفظ الدور</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <h4 class="fw-bold mb-3">إضافة صلاحية</h4>

                <form method="POST" action="<?= url('permissions/store') ?>">
                    <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

                    <div class="mb-3">
                        <label class="form-label">الاسم البرمجي</label>
                        <input type="text" name="name" class="form-control" placeholder="مثال: invoices.view">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <input type="text" name="label" class="form-control" placeholder="مثال: عرض الفواتير">
                    </div>

                    <button class="btn btn-outline-dark w-100">حفظ الصلاحية</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <h4 class="fw-bold mb-3">الأدوار الحالية</h4>

                <?php if (empty($roles)): ?>
                    <div class="text-muted">لا توجد أدوار.</div>
                <?php else: ?>
                    <?php foreach ($roles as $role): ?>
                        <div class="border rounded-4 p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1"><?= e($role['name']) ?></h5>
                                    <div class="text-muted small">عدد المستخدمين: <?= (int) ($role['users_count'] ?? 0) ?></div>
                                </div>
                            </div>

                            <form method="POST" action="<?= url('roles/permissions/' . (int) $role['id']) ?>">
                                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

                                <div class="row">
                                    <?php foreach ($permissions as $permission): ?>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check border rounded-3 p-2">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="permissions[]"
                                                    value="<?= (int) $permission['id'] ?>"
                                                    id="role-<?= (int) $role['id'] ?>-perm-<?= (int) $permission['id'] ?>"
                                                    <?= in_array((int) $permission['id'], $role['permission_ids'] ?? [], true) ? 'checked' : '' ?>
                                                >
                                                <label class="form-check-label w-100" for="role-<?= (int) $role['id'] ?>-perm-<?= (int) $permission['id'] ?>">
                                                    <?= e($permission['label']) ?>
                                                    <span class="text-muted small d-block"><?= e($permission['name']) ?></span>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-success">تحديث الصلاحيات</button>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>