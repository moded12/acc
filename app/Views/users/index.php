<?php
// Path: /app/Views/users/index.php
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">المستخدمون</h1>
        <p class="text-muted mb-0">إدارة مستخدمي النظام وربطهم بالأدوار</p>
    </div>
    <a href="<?= url('users/create') ?>" class="btn btn-primary">إضافة مستخدم</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الدور</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= (int) $user['id'] ?></td>
                        <td><?= e($user['name']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= e($user['role_name'] ?? '-') ?></td>
                        <td>
                            <?php if ((int) ($user['status'] ?? 1) === 1): ?>
                                <span class="badge bg-success">نشط</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">غير نشط</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= url('users/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning">تعديل</a>
                            <form method="POST" action="<?= url('users/delete/' . $user['id']) ?>" style="display:inline;">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-danger" onclick="return confirm('حذف المستخدم؟')">
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">لا يوجد مستخدمون.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>