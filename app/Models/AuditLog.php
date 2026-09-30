<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'event', 'auditable_type', 'auditable_id', 'before', 'after', 'ip_address', 'user_agent'])]
class AuditLog extends Model
{
    protected function casts(): array
    {
        return [
            'after' => 'array',
            'before' => 'array',
        ];
    }
}
