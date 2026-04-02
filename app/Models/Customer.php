<?php
// Path: /app/Models/Customer.php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Customer extends Model
{
    protected string $table = 'customers';

    public function getAll(?string $type = null): array
    {
        if ($type !== null && in_array($type, ['customer', 'supplier'], true)) {
            $stmt = $this->db()->prepare("
                SELECT *
                FROM customers
                WHERE type = :type
                ORDER BY id DESC
            ");
            $stmt->execute(['type' => $type]);
            return $stmt->fetchAll();
        }

        $stmt = $this->db()->query("
            SELECT *
            FROM customers
            ORDER BY id DESC
        ");

        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db()->prepare("
            SELECT *
            FROM customers
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db()->prepare("
            INSERT INTO customers (
                type,
                name,
                company_name,
                phone,
                email,
                tax_number,
                address,
                opening_balance,
                balance_type,
                status,
                notes,
                created_at
            ) VALUES (
                :type,
                :name,
                :company_name,
                :phone,
                :email,
                :tax_number,
                :address,
                :opening_balance,
                :balance_type,
                :status,
                :notes,
                NOW()
            )
        ");

        return $stmt->execute([
            'type' => $data['type'],
            'name' => $data['name'],
            'company_name' => $data['company_name'] ?: null,
            'phone' => $data['phone'] ?: null,
            'email' => $data['email'] ?: null,
            'tax_number' => $data['tax_number'] ?: null,
            'address' => $data['address'] ?: null,
            'opening_balance' => (float) ($data['opening_balance'] ?: 0),
            'balance_type' => $data['balance_type'] ?: 'debit',
            'status' => (int) ($data['status'] ?? 1),
            'notes' => $data['notes'] ?: null,
        ]);
    }

    public function updateCustomer(int $id, array $data): bool
    {
        $stmt = $this->db()->prepare("
            UPDATE customers
            SET
                type = :type,
                name = :name,
                company_name = :company_name,
                phone = :phone,
                email = :email,
                tax_number = :tax_number,
                address = :address,
                opening_balance = :opening_balance,
                balance_type = :balance_type,
                status = :status,
                notes = :notes
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'type' => $data['type'],
            'name' => $data['name'],
            'company_name' => $data['company_name'] ?: null,
            'phone' => $data['phone'] ?: null,
            'email' => $data['email'] ?: null,
            'tax_number' => $data['tax_number'] ?: null,
            'address' => $data['address'] ?: null,
            'opening_balance' => (float) ($data['opening_balance'] ?: 0),
            'balance_type' => $data['balance_type'] ?: 'debit',
            'status' => (int) ($data['status'] ?? 1),
            'notes' => $data['notes'] ?: null,
        ]);
    }

    public function deleteCustomer(int $id): bool
    {
        $stmt = $this->db()->prepare("
            DELETE FROM customers
            WHERE id = :id
        ");

        return $stmt->execute(['id' => $id]);
    }

    public function existsByPhone(string $phone, ?int $ignoreId = null): bool
    {
        if ($phone === '') {
            return false;
        }

        $sql = "
            SELECT id
            FROM customers
            WHERE phone = :phone
        ";

        $params = ['phone' => $phone];

        if ($ignoreId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $ignoreId;
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);

        return (bool) $stmt->fetch();
    }

    public function totals(): array
    {
        $stmt = $this->db()->query("
            SELECT
                SUM(CASE WHEN type = 'customer' THEN 1 ELSE 0 END) AS customers_count,
                SUM(CASE WHEN type = 'supplier' THEN 1 ELSE 0 END) AS suppliers_count,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS active_count
            FROM customers
        ");

        return $stmt->fetch() ?: [
            'customers_count' => 0,
            'suppliers_count' => 0,
            'active_count' => 0,
        ];
    }
}