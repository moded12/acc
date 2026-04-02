<?php
// Path: /app/Models/Permission.php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Permission extends Model
{
    protected string $table = 'permissions';

    public function getAll(): array
    {
        $stmt = $this->db()->query("
            SELECT *
            FROM permissions
            ORDER BY label ASC, name ASC
        ");

        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db()->prepare("
            INSERT INTO permissions (name, label, created_at)
            VALUES (:name, :label, NOW())
        ");

        return $stmt->execute([
            'name' => $data['name'],
            'label' => $data['label']
        ]);
    }

    public function existsByName(string $name): bool
    {
        $stmt = $this->db()->prepare("
            SELECT id
            FROM permissions
            WHERE name = :name
            LIMIT 1
        ");

        $stmt->execute(['name' => $name]);
        return (bool) $stmt->fetch();
    }
}