<?php
// Path: /modules/Settings/Models/Setting.php

declare(strict_types=1);

namespace Modules\Settings\Models;

use App\Core\Model;

class Setting extends Model
{
    protected string $table = 'settings';

    public function allKeyed(): array
    {
        $rows = $this->db()->query('SELECT setting_key, setting_value FROM settings')->fetchAll();
        $data = [];
        foreach ($rows as $row) {
            $data[$row['setting_key']] = $row['setting_value'];
        }
        return $data;
    }

    public function saveMany(array $settings): void
    {
        $sql = 'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)';
        $stmt = $this->db()->prepare($sql);
        foreach ($settings as $key => $value) {
            $stmt->execute([$key, $value]);
        }
    }
}
