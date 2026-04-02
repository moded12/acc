<?php
// Path: /modules/Users/Models/Role.php

declare(strict_types=1);

namespace Modules\Users\Models;

use App\Core\Model;

class Role extends Model
{
    protected string $table = 'roles';

    public function all(): array
    {
        return $this->db()->query('SELECT * FROM roles ORDER BY id ASC')->fetchAll();
    }
}
