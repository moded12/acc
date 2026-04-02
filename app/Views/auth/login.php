<?php
// Path: /app/Views/auth/login.php

$errors = $errors ?? [];
$old = $_SESSION['_old'] ?? [];
?>

<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 mt-5">
            <div class="card-body p-4">
                <h1 class="fw-bold text-center mb-2">تسجيل الدخول</h1>
                <p class="text-muted text-center mb-4">ابدأ بإدارة نظام ERP</p>

                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-danger">
                        <?= e($errors['general'][0]) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= url('login') ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?= e($old['email'] ?? 'admin@erp.local') ?>"
                            required
                        >
                        <?php if (!empty($errors['email'])): ?>
                            <div class="text-danger small mt-1"><?= e($errors['email'][0]) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">كلمة المرور</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            value=""
                            required
                        >
                        <?php if (!empty($errors['password'])): ?>
                            <div class="text-danger small mt-1"><?= e($errors['password'][0]) ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">دخول</button>
                </form>

                <div class="alert alert-info mt-4 mb-0">
                    <strong>الدخول التجريبي:</strong><br>
                    admin@erp.local<br>
                    123@123_*
                </div>
            </div>
        </div>
    </div>
</div>