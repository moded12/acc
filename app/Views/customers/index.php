<?php
// Path: /app/Views/customers/index.php

$type = $type ?? null;
$records = $records ?? [];
$totals = $totals ?? [
    'customers_count' => 0,
    'suppliers_count' => 0,
    'active_count' => 0,
];

function customer_type_label(?string $typeValue): string
{
    return $typeValue === 'supplier' ? 'مورد' : 'عميل';
}
?>

<section class="erp-page-header">
    <div>
        <h1 class="erp-page-title">العملاء والموردون</h1>
        <p class="erp-page-subtitle">إدارة جهات التعامل والأرصدة الافتتاحية بشكل احترافي ومتجاوب</p>
    </div>

    <div class="erp-actions">
        <a href="<?= url('customers/create?type=customer') ?>" class="erp-btn erp-btn-primary">إضافة عميل</a>
        <a href="<?= url('customers/create?type=supplier') ?>" class="erp-btn erp-btn-outline">إضافة مورد</a>
    </div>
</section>

<section class="row g-4">
    <div class="col-12 col-md-4">
        <div class="erp-card erp-stat-card">
            <div class="erp-card-body">
                <div class="erp-stat-label">العملاء</div>
                <div class="erp-stat-value"><?= (int) ($totals['customers_count'] ?? 0) ?></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="erp-card erp-stat-card">
            <div class="erp-card-body">
                <div class="erp-stat-label">الموردون</div>
                <div class="erp-stat-value"><?= (int) ($totals['suppliers_count'] ?? 0) ?></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="erp-card erp-stat-card">
            <div class="erp-card-body">
                <div class="erp-stat-label">السجلات النشطة</div>
                <div class="erp-stat-value"><?= (int) ($totals['active_count'] ?? 0) ?></div>
            </div>
        </div>
    </div>
</section>

<section class="erp-card">
    <div class="erp-card-body">
        <div class="erp-filter-bar">
            <a href="<?= url('customers') ?>" class="erp-btn <?= $type === null ? 'erp-btn-dark' : 'erp-btn-outline' ?>">الكل</a>
            <a href="<?= url('customers?type=customer') ?>" class="erp-btn <?= $type === 'customer' ? 'erp-btn-primary' : 'erp-btn-outline' ?>">العملاء</a>
            <a href="<?= url('customers?type=supplier') ?>" class="erp-btn <?= $type === 'supplier' ? 'erp-btn-dark' : 'erp-btn-outline' ?>">الموردون</a>
        </div>
    </div>
</section>

<section class="erp-card">
    <div class="erp-card-body">
        <div class="erp-data-table-wrap">
            <table class="erp-data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>النوع</th>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>البريد</th>
                        <th>الرصيد الافتتاحي</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?= (int) $record['id'] ?></td>
                            <td>
                                <span class="erp-badge <?= ($record['type'] ?? '') === 'supplier' ? 'erp-badge-secondary' : 'erp-badge-primary' ?>">
                                    <?= e(customer_type_label($record['type'] ?? 'customer')) ?>
                                </span>
                            </td>
                            <td>
                                <div class="erp-row-title"><?= e($record['name']) ?></div>
                                <?php if (!empty($record['company_name'])): ?>
                                    <div class="erp-row-subtitle"><?= e($record['company_name']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= e($record['phone'] ?? '-') ?></td>
                            <td><?= e($record['email'] ?? '-') ?></td>
                            <td>
                                <div class="erp-row-title"><?= number_format((float) ($record['opening_balance'] ?? 0), 2) ?></div>
                                <div class="erp-row-subtitle"><?= e(($record['balance_type'] ?? 'debit') === 'credit' ? 'دائن' : 'مدين') ?></div>
                            </td>
                            <td>
                                <?php if ((int) ($record['status'] ?? 1) === 1): ?>
                                    <span class="erp-badge erp-badge-success">نشط</span>
                                <?php else: ?>
                                    <span class="erp-badge erp-badge-secondary">غير نشط</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="erp-action-group">
                                    <a href="<?= url('customers/edit/' . (int) $record['id']) ?>" class="erp-btn erp-btn-warning">تعديل</a>

                                    <form method="POST" action="<?= url('customers/delete/' . (int) $record['id']) ?>" class="m-0">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="erp-btn erp-btn-danger" onclick="return confirm('حذف السجل؟')">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($records)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">لا توجد سجلات حتى الآن.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="erp-mobile-cards">
            <?php foreach ($records as $record): ?>
                <article class="erp-record-card">
                    <div class="erp-record-head">
                        <div>
                            <div class="erp-record-title"><?= e($record['name']) ?></div>
                            <?php if (!empty($record['company_name'])): ?>
                                <div class="erp-row-subtitle"><?= e($record['company_name']) ?></div>
                            <?php endif; ?>
                        </div>

                        <span class="erp-badge <?= ($record['type'] ?? '') === 'supplier' ? 'erp-badge-secondary' : 'erp-badge-primary' ?>">
                            <?= e(customer_type_label($record['type'] ?? 'customer')) ?>
                        </span>
                    </div>

                    <div class="erp-record-meta">
                        <div class="erp-meta-box">
                            <div class="erp-meta-label">الهاتف</div>
                            <div class="erp-meta-value"><?= e($record['phone'] ?? '-') ?></div>
                        </div>

                        <div class="erp-meta-box">
                            <div class="erp-meta-label">البريد</div>
                            <div class="erp-meta-value"><?= e($record['email'] ?? '-') ?></div>
                        </div>

                        <div class="erp-meta-box">
                            <div class="erp-meta-label">الرصيد الافتتاحي</div>
                            <div class="erp-meta-value">
                                <?= number_format((float) ($record['opening_balance'] ?? 0), 2) ?>
                                - <?= e(($record['balance_type'] ?? 'debit') === 'credit' ? 'دائن' : 'مدين') ?>
                            </div>
                        </div>

                        <div class="erp-meta-box">
                            <div class="erp-meta-label">الحالة</div>
                            <div class="erp-meta-value">
                                <?php if ((int) ($record['status'] ?? 1) === 1): ?>
                                    <span class="erp-badge erp-badge-success">نشط</span>
                                <?php else: ?>
                                    <span class="erp-badge erp-badge-secondary">غير نشط</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="erp-action-group">
                        <a href="<?= url('customers/edit/' . (int) $record['id']) ?>" class="erp-btn erp-btn-warning">تعديل</a>

                        <form method="POST" action="<?= url('customers/delete/' . (int) $record['id']) ?>" class="m-0">
                            <?= csrf_field() ?>
                            <button type="submit" class="erp-btn erp-btn-danger" onclick="return confirm('حذف السجل؟')">حذف</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>

            <?php if (empty($records)): ?>
                <div class="erp-record-card text-center text-muted">لا توجد سجلات حتى الآن.</div>
            <?php endif; ?>
        </div>
    </div>
</section>