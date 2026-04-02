<?php // Path: /modules/Users/Views/create.php ?>
<h1 class="mb-4">إضافة مستخدم</h1>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?= e(base_url('users')) ?>">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الاسم</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الدور</label>
                    <select name="role_id" class="form-select" required>
                        <option value="">اختر الدور</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= e($role['id']) ?>"><?= e($role['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-success">حفظ</button>
                </div>
            </div>
        </form>
    </div>
</div>
