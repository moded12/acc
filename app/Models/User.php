<?php
// Path: /app/Models/User.php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db()->prepare("
            SELECT u.*, r.name AS role_name
            FROM users u
            LEFT JOIN roles r ON r.id = u.role_id
            WHERE u.email = :email
            LIMIT 1
        ");

        $stmt->execute([
            'email' => $email
        ]);

        return $stmt->fetch();
    }

    public function getAllWithRoles(): array
    {
        $stmt = $this->db()->query("
            SELECT u.*, r.name AS role_name
            FROM users u
            LEFT JOIN roles r ON r.id = u.role_id
            ORDER BY u.id DESC
        ");

        return $stmt->fetchAll();
    }

    public function findWithRole(int $id): array|false
    {
        $stmt = $this->db()->prepare("
            SELECT u.*, r.name AS role_name
            FROM users u
            LEFT JOIN roles r ON r.id = u.role_id
            WHERE u.id = :id
            LIMIT 1
        ");

        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db()->prepare("
            INSERT INTO users (name, email, password, role_id, status, created_at)
            VALUES (:name, :email, :password, :role_id, :status, NOW())
        ");

        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role_id' => (int) $data['role_id'],
            'status' => (int) ($data['status'] ?? 1),
        ]);
    }

    public function updateUser(int $id, array $data): bool
    {
        $sql = "
            UPDATE users
            SET name = :name,
                email = :email,
                role_id = :role_id,
                status = :status
        ";

        $params = [
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => (int) $data['role_id'],
            'status' => (int) ($data['status'] ?? 1),
        ];

        if (!empty($data['password'])) {
            $sql .= ", password = :password";
            $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->db()->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteUser(int $id): bool
    {
        $stmt = $this->db()->prepare("
            DELETE FROM users
            WHERE id = :id
        ");

        return $stmt->execute(['id' => $id]);
    }

    public function hasPermission(int $userId, string $permission): bool
    {
        $stmt = $this->db()->prepare("
            SELECT COUNT(*) AS total
            FROM users u
            INNER JOIN role_permissions rp ON rp.role_id = u.role_id
            INNER JOIN permissions p ON p.id = rp.permission_id
            WHERE u.id = :user_id
              AND p.name = :permission
            LIMIT 1
        ");

        $stmt->execute([
            'user_id' => $userId,
            'permission' => $permission,
        ]);

        $result = $stmt->fetch();
        return ((int) ($result['total'] ?? 0)) > 0;
    }
}