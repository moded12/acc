<?php
// Path: /app/Models/Role.php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Role extends Model
{
    protected string $table = 'roles';

    public function getAll(): array
    {
        $stmt = $this->db()->query("
            SELECT r.*,
                   (SELECT COUNT(*) FROM users u WHERE u.role_id = r.id) AS users_count
            FROM roles r
            ORDER BY r.id DESC
        ");

        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db()->prepare("
            INSERT INTO roles (name, created_at)
            VALUES (:name, NOW())
        ");

        return $stmt->execute([
            'name' => $data['name']
        ]);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db()->prepare("
            SELECT *
            FROM roles
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function updateRole(int $id, array $data): bool
    {
        $stmt = $this->db()->prepare("
            UPDATE roles
            SET name = :name
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name']
        ]);
    }

    public function deleteRole(int $id): bool
    {
        $stmt = $this->db()->prepare("
            DELETE FROM roles
            WHERE id = :id
        ");

        return $stmt->execute(['id' => $id]);
    }

    public function getPermissions(int $roleId): array
    {
        $stmt = $this->db()->prepare("
            SELECT p.*
            FROM permissions p
            INNER JOIN role_permissions rp ON rp.permission_id = p.id
            WHERE rp.role_id = :role_id
            ORDER BY p.label ASC, p.name ASC
        ");

        $stmt->execute(['role_id' => $roleId]);
        return $stmt->fetchAll();
    }

    public function getPermissionIds(int $roleId): array
    {
        $stmt = $this->db()->prepare("
            SELECT permission_id
            FROM role_permissions
            WHERE role_id = :role_id
        ");

        $stmt->execute(['role_id' => $roleId]);
        return array_map('intval', array_column($stmt->fetchAll(), 'permission_id'));
    }

    public function syncPermissions(int $roleId, array $permissionIds): void
    {
        $deleteStmt = $this->db()->prepare("
            DELETE FROM role_permissions
            WHERE role_id = :role_id
        ");
        $deleteStmt->execute(['role_id' => $roleId]);

        if (empty($permissionIds)) {
            return;
        }

        $insertStmt = $this->db()->prepare("
            INSERT INTO role_permissions (role_id, permission_id, created_at)
            VALUES (:role_id, :permission_id, NOW())
        ");

        foreach ($permissionIds as $permissionId) {
            $insertStmt->execute([
                'role_id' => $roleId,
                'permission_id' => (int) $permissionId
            ]);
        }
    }
}