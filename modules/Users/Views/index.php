<?php // Path: /modules/Users/Views/index.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="m-0">إدارة المستخدمين</h1>
    <a href="<?= e(base_url('users/create')) ?>" class="btn btn-primary">إضافة مستخدم</a>
</div>
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد</th>
                    <th>الدور</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= e($user['id']) ?></td>
                        <td><?= e($user['name']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= e($user['role_name'] ?? '-') ?></td>
                        <td><?= e($user['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
