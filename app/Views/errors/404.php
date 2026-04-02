<?php
// Path: /app/Views/errors/404.php
?>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body text-center py-5">
        <h1 class="display-5 fw-bold text-danger mb-3">404</h1>
        <h4 class="fw-bold mb-3"><?= e($title ?? 'الصفحة غير موجودة') ?></h4>
        <p class="text-muted mb-4"><?= e($message ?? 'تعذر العثور على الصفحة المطلوبة.') ?></p>
        <a href="<?= url('dashboard') ?>" class="btn btn-primary">العودة إلى لوحة التحكم</a>
    </div>
</div>